<?php

defined( 'ABSPATH' ) || exit;

/**
 * Imaging setup class (a singleton)
 */
class QNRWP_Imaging {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks related to image handling
   */
  private function hooks() {
    add_action('after_setup_theme', array($this, 'setup'));
    
    // Add SVG support
    add_filter( 'upload_mimes', array($this, 'upload_mimes'));
    
    add_filter('wp_handle_upload', array($this, 'reduce_uploaded_image'));
    add_filter('image_size_names_choose', array($this, 'custom_image_sizes'));
    
    add_filter('wp_image_editors', array($this, 'qnrwp_image_editor_init')); // Loads QNRWP_Image_Editor
    
    add_filter('jpeg_quality', array($this, 'jpeg_quality'));
    
    add_filter('pre_update_option_qnrwp_regenerate_images', array($this, 'pre_update_option_qnrwp_regenerate_images'), 10, 3);
    
    add_action('qnrwp_regenerate_images_hook', array($this, 'regenerate_images_cron'), 10); // Our own hook
  }
  
  
  /**
   * Adds support for SVG uploads
   */
  public function upload_mimes($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    return $mimes;
  }
  
  
  /**
   * Sets up theme image handling
   * 
   * Methods called from hooks must be public
   */
  public function setup() {
    // ----------------------- Add new image sizes
    add_image_size('qnrwp-larger', 1600, 0, false);
    add_image_size('qnrwp-largest', 2000, 0, false);
    add_image_size('qnrwp-extra', 2500, 0, false);
  }
  
  
  /**
   * Reduces uploaded image to 2500px width
   * 
   * @param   array    $upload   Array of 'file', 'url', 'type'
   */
  public function reduce_uploaded_image($upload) {
    // There is also a $context argument, but we don't care about it
    if (in_array($upload['type'], array('image/jpg', 'image/jpeg', 'image/gif', 'image/png'))) {
      try {
        // Load uploaded image into editor object
        $uploaded_image_location = $upload['file'];
        $imageFull = wp_get_image_editor($uploaded_image_location);
        if ($imageFull->get_size()['width'] > 2500) {
          // Set JPEG quality to half way between Media setting and 100, for better quality
          $jpegQ = get_option('qnrwp_jpeg_quality', $default=60);
          $imageFull->set_quality(intval((100 - $jpegQ)/2) + $jpegQ);
          $imageFull->resize(2500, null, false);
          $imageFull->save($uploaded_image_location);
          unset($imageFull); // Just in case...
        }
        return $upload;
      }
      catch (Exception $e) {
        wp_die($e->getMessage());
      }
    } else return $upload;
  }
  
  
  /**
   * Adds registered custom image sizes to Dashboard, plus post-thumbnail's Medium Large
   */
  function custom_image_sizes($sizes) {
      return array_merge($sizes, array(
          'medium_large' => __('Medium Large', 'qnrwp'),
          'qnrwp-larger' => __('QNRWP-Larger', 'qnrwp'),
          'qnrwp-largest' => __('QNRWP-Largest', 'qnrwp'),
          'qnrwp-extra' => __('QNRWP-Extra', 'qnrwp'),
      ));
  }
  
  
  /**
   * Overrides ImageMagick image engine with our own sub-class
   */
  public function qnrwp_image_editor_init($editors) {
    if (!class_exists('QNRWP_Image_Editor')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-image-editor.php';
    }
    array_unshift($editors, 'QNRWP_Image_Editor');
    return $editors;
  }
  
  
  /**
   * Sets quality with filter hook and settings option rather than hardcoding in class
   */
  public function jpeg_quality($args) {
    return get_option('qnrwp_jpeg_quality', $default='60');
  }
  
  
  /**
   * Regenerate Images setup and control
   */
  public function pre_update_option_qnrwp_regenerate_images($value, $old_value, $option) {
    // We use this hook instead of 'updated_option' because the latter does not catch unchanged values
    // Test for Regenerate Images checkbox being ticked
    if ($value === 1 || $value === true || $value === 'on' || $value === '1') {
      // Get the saved options/record array from database, assumed already created as part of UI code
      $riSavedOptions = get_option('qnrwp_regenerate_images_record');
      // Return if RI cron is already running (this should be redundant, checkbox should be disabled)
      if ($riSavedOptions['start-time'] && !$riSavedOptions['end-time'] && time() - intval($riSavedOptions['last-update']) < 1800) return 0;
      // Get the image attachments from database
      $images_query = new WP_Query(array( 'post_type' => 'attachment', 
                                          'post_status' => 'any', 
                                          'nopaging' => true, 
                                          'post_mime_type' => array('image/gif','image/jpeg','image/png')));
      $amL = []; // List post IDs
      foreach ($images_query->posts as $post) {
        $amL[] = $post->ID;
      }
      unset($images_query); // Get rid of the query object, no longer needed
      // Set the error if no attachments found
      if (count($amL) == 0) {
        $riSavedOptions['error'] = __('No images found', 'qnrwp');
      } else { // Images found
        // Set up the Regenerate Images cron
        if (!wp_next_scheduled('qnrwp_regenerate_images_hook')) {
          $rSched = wp_schedule_single_event(time() + 10, 'qnrwp_regenerate_images_hook');
          if ($rSched === false) {
            $riSavedOptions['error'] = __('Regenerate Images cron job could not be scheduled', 'qnrwp');
          } else {
            // Successfully scheduled, update options record for the cron job
            $riSavedOptions['start-time'] = date('r');
            if ($riSavedOptions['end-time']) $riSavedOptions['processed-ids'] = 0; // Reset processed-ids if previous run ended fully
            $riSavedOptions['end-time'] = 0; // Now we can reset end-time as well
            $riSavedOptions['images-count'] = count($amL);
            $riSavedOptions['last-update'] = time();
            $riSavedOptions['error'] = 0;
          }
        } else { // Hook already scheduled
          $riSavedOptions['error'] = __('A Regenerate Images cron job is already scheduled', 'qnrwp');
        }
      }
      // Save the options/record
      if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) wp_die('ERROR: '.__('Database options for Regenerate Images could not be saved.', 'qnrwp'));
    } // Else checkbox not ticked, do nothing
    // Always save checkbox unchecked
    return 0;
  }
  
  
  /**
   * Regenerate image cron job
   * 
   * Must be here, not in functions-admin.php, because called by cron, not from admin UI
   */
  public function regenerate_images_cron() {
    ini_set('max_execution_time', 0); // Unlimit max execution time
    try {
      if (!function_exists('wp_generate_attachment_metadata')) require_once(ABSPATH . 'wp-admin/includes/image.php'); // Required
      // Get the saved options/record array from database, assumed already created as part of UI code and populated partly by scheduler
      $riSavedOptions = get_option('qnrwp_regenerate_images_record');
      // Get the image attachments from database
      $images_query = new WP_Query(array( 'post_type'       => 'attachment', 
                                          'post_status'     => 'any', 
                                          'nopaging'        => true, 
                                          'post_mime_type'  => array('image/gif','image/jpeg','image/png')));
      $amL = []; // Attachment metadata list
      foreach ($images_query->posts as $post) {
        $amL[$post->ID] = wp_get_attachment_metadata($post->ID);
      }
      unset($images_query); // Get rid of the query object, no longer needed
      if (count($amL) == 0) { // Set the error if no attachments found
        $riSavedOptions['error'] = __('No images found', 'qnrwp');
      }
      // Read the Media admin settings options to compare with previous run
      // We assume settings have been saved just before the cron run 
      $mSetsL = array(  'thumbnail_size_w'                => get_option('thumbnail_size_w'),
                        'thumbnail_size_h'                => get_option('thumbnail_size_h'),
                        'thumbnail_crop'                  => get_option('thumbnail_crop'),
                        'medium_size_w'                   => get_option('medium_size_w'),
                        'medium_size_h'                   => get_option('medium_size_h'),
                        'large_size_w'                    => get_option('large_size_w'),
                        'large_size_h'                    => get_option('large_size_h'),
                        'uploads_use_yearmonth_folders'   => get_option('uploads_use_yearmonth_folders'),
                        'qnrwp_jpeg_quality'              => get_option('qnrwp_jpeg_quality'));
      // If there was a previous run and did not finish, and settings are unchanged, complete it
      if (!$riSavedOptions['end-time'] && $riSavedOptions['settings-used'] == $mSetsL && $riSavedOptions['processed-ids']) {
        $partDone = true;
      } else { // Start from the beginning
        $partDone = false;
      }
      // Regenerate sized images
      // Set in-progress options/record
      $riSavedOptions['end-time'] = 0; // Reset in case a previous run completed (should be redundant, set to 0 by scheduler)
      $riSavedOptions['images-count'] = count($amL); // Update just in case
      if (!$partDone) $riSavedOptions['processed-ids'] = [];
      $riSavedOptions['settings-used'] = $mSetsL;
      $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
      //$debugCount = 0; // TEST
      foreach ($amL as $attach_id => $image_data) {
        if (!$partDone || ($partDone && !in_array($attach_id, $riSavedOptions['processed-ids']))) {
          //$debugCount += 1;
          $uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
          $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image_location); // May include a filter hook update
          if ($image_data != $attach_data) wp_update_attachment_metadata($attach_id, $attach_data); // May be redundant, unavoidably
          $riSavedOptions['processed-ids'][] = $attach_id;
          $riSavedOptions['last-update'] = time();
          
          // NEW: we re-make the original regardless of size, to avoid poorly compressed originals being served
          
          // OLD: Reduce full-size original if larger than 2500px width (may be redundant if filter hook being used)
          //if ($attach_data['width'] > 2500) { // Due to the above, we are now testing possibly updated metadata, including by any filter hook
            $imageFull = wp_get_image_editor($uploaded_image_location);
            // Set JPEG quality to half way between Media setting and 100, for better quality
            $imageFull->set_quality(intval((100 - $mSetsL['qnrwp_jpeg_quality'])/2) + $mSetsL['qnrwp_jpeg_quality']);
            //qnrwp_debug_printout(array('TEST in loop::', $imageFull, $uploaded_image_location, $attach_data)); // TEST
            
          if ($attach_data['width'] > 2500) { // Due to the above, we are now testing possibly updated metadata, including by any filter hook
            $imageFull->resize(2500, null, false);
          }
            
            $imageFull->save($uploaded_image_location);
            // Update the metadata array again
            $attach_data['width'] = $imageFull->get_size()['width'];
            $attach_data['height'] = $imageFull->get_size()['height'];
            wp_update_attachment_metadata($attach_id, $attach_data);
            unset($imageFull); // Just in case...
          //}
          
          // Save the options/record
          if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) wp_die(__('Database options during processing for Regenerate Images could not be saved.', 'qnrwp'));
        }
      }
      // Start-time was set by scheduler in functions-admin.php
      $riSavedOptions['last-update'] = time();
      $riSavedOptions['end-time'] = date('r');
      // Save the options/record
      if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) wp_die(__('Database options for Regenerate Images could not be saved.', 'qnrwp'));
      //qnrwp_debug_printout(array('Regenerate Images cron run', date('r'), $riSavedOptions)); // TEST
    }
    catch (Exception $e) {
      error_log(  'WP cron error:  '.$e->getMessage().' in '.PHP_EOL.$e->getFile().':'.$e->getLine().PHP_EOL
                  .'Stack trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.'  thrown in '.$e->getFile().' on line '.$e->getLine()  );
    }
    return 0;
    
    //// List all sized images NOT USED, but kept for reference
    //$upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
    //foreach ($amL as $image_data) {
      //$uploaded_image_location = $upload_dir['basedir'] . '/' . $image_data['file'];
      //$sized_images_dir = $upload_dir['basedir'] . '/' . substr($image_data['file'], 0, strrpos($image_data['file'], '/'));
      //foreach ($image_data['sizes'] as $sized_image) {
        //$siL[] = $sized_images_dir . '/' . $sized_image['file'];
      //}
    //}
    
    ////qnrwp_debug_printout(array('Pre-option saving::',$option,$old_value,$value,$amL,wp_upload_dir()));
    //qnrwp_debug_printout(array('Pre-option saving::',$option,$old_value,$value));
    //return 0;
  }


  
} // End QNRWP_Imaging class
