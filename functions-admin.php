<?php
/*
 * functions-admin.php
 * 
 * Functions for the Admin pages
 * 
 */


// ===================== ADMIN DASHBOARD SETTINGS =====================

function qnrwp_admin_settings() {
  /**
   * ----------------------- QNRWP settings on Media page
   * 
   * The Regenerate Images record is first created in the construction
   * of the UI, populated with start time on cron schedule, and fully
   * populated by the callback function. The settings options are
   * recorded in the record on saving to db, then read in the callback,
   * testing whether they are changed, for the sake of starting from
   * where a failed process ended, or starting anew.
   * 
   */
  
  add_settings_section(
    'qnrwp_media_section',      // ID attribute of tags
    'QNRWP Media Settings',     // Section title
    'qnrwp_media_section',      // Callback to echo section content
    'media'                     // Admin page to use
  );
  // JPEG Quality
  add_settings_field(
    'qnrwp_jpeg_quality',       // ID attribute of tags
    'JPEG Quality',             // Field title
    'qnrwp_jpeg_quality',       // Callback to echo input control
    'media',                    // Admin page to use
    'qnrwp_media_section'       // Section to use
  );
  // Regenerate Images
  add_settings_field(
    'qnrwp_regenerate_images',  // ID attribute of tags
    'Regenerate Images',        // Field title
    'qnrwp_regenerate_images',  // Callback to echo input control
    'media',                    // Admin page to use
    'qnrwp_media_section'       // Section to use
  );
  register_setting('media', 'qnrwp_jpeg_quality');
  register_setting('media', 'qnrwp_regenerate_images');
}
add_action('admin_init', 'qnrwp_admin_settings');

function qnrwp_media_section() {
  // Callback function for the section; display info about two additional sizes
  ?>
  <div style="font-size:1.1em"><p>This theme defines and makes available three additional sizes not listed above:</p>
  <ul>
  <li><b>QNRWP-Larger</b> - 1600px width, height proportional</li>
  <li><b>QNRWP-Largest</b> - 2000px width, height proportional</li>
  <li><b>QNRWP-Extra</b> - 2500px width, height proportional</li>
  </ul>
  <p>In addition, this theme exposes to the user the fixed and "hidden" <b>Medium Large</b> size, 768px width, height proportional.</p>
  </div>
  <?php
}

function qnrwp_jpeg_quality() {
  // Echo the input control for this option
  ?>
  <p>Enter the JPEG quality setting (1-100) to use when Wordpress generates thumbnails and intermediate image sizes from the upload.</p>
  <label><input type="number" name="qnrwp_jpeg_quality" id="qnrwp_jpeg_quality" min="1" max="100" required 
                maxlength="3" size="4" class="small-text" value="<?php echo get_option('qnrwp_jpeg_quality', $default='60'); ?>"></label>
  <?php
}

function qnrwp_regenerate_images() {
  // Echo the UI, the work is done by qnrwp_regenerate_images_cron
  ?>
  <p>Tick the checkbox below so that when you press the <code>Save Changes</code> button, a Wordpress cron job will be started, working in the background on the server, regenerating thumbnail and intermediate size images from their JPEG, PNG and GIF full-size originals, using the settings on this page. Existing images of same sizes as specified on this page will be overwritten, but any other sizes will be left untouched.</p>
  <hr>
  <p>In addition, JPEG, PNG and GIF full-size originals wider than 2500px will be reduced and overwritten, matching the QNRWP-Extra size - 2500px width, height proportional. To ensure higher JPEG quality, compression quality will be at the half point between the setting on this page and 100. For example, if the JPEG Quality setting on this page is 60, then 80 will be used for the reduction of full-size originals. This reduction also happens during image upload.</p>
  <hr>
  <p><span style="color:red;">Processing cannot be cancelled or undone, and depending on the size and number of images, may take up to several hours or days.</span> The website will continue to function normally during this time. Processing may fail if your server employs a timeout preventing long-running scripts - check your server configuration before proceeding. If processing fails part way through, it will start where it left off on the next run, provided that the settings on this page are unchanged; otherwise processing will start again from the beginning. If starting from the beginning after a failed partial run, the previously achieved tally will be shown below for a few minutes before starting from 0 again. Any plugins that change how Wordpress cron jobs work may interfere with image regeneration.</p>
  <hr>
  <p id="regenerate-images-last">Details of the most recent regeneration will be shown here when the cron job is started.</p>
  <p style="margin-left:2em;">
  <?php
  // Get the saved options array from database, or create it
  $riSavedOptions = get_option('qnrwp_regenerate_images_record');
  if ($riSavedOptions === false || count($riSavedOptions) != 7) { // Test for both existence of the option and number of items in it
    $riSavedOptions = array(  'start-time' => 0,
                              'end-time' => 0,
                              'images-count' => 0,
                              'processed-ids' => 0,
                              'settings-used' => 0,
                              'last-update' => time(),
                              'error' => 0);
    update_option('qnrwp_regenerate_images_record', $riSavedOptions); // Will add the option if it doesn't exist
  }
  // Create the feedback block, taking care of (lack of) end time and whether the record has been updated within 30 minutes
  if ($riSavedOptions['start-time'] || $riSavedOptions['error']) {
    $rio = '';
    if ($riSavedOptions['start-time']) {
      $rio .= 'Last regeneration started: ' . $riSavedOptions['start-time'] . '<br>' . PHP_EOL;
      $rio .= 'Last regeneration ended: ' . (($riSavedOptions['end-time']) ? $riSavedOptions['end-time'] : ((time() - intval($riSavedOptions['last-update']) >= 1800) ? '<em>image regeneration has stopped prematurely</em>' : '<em>image regeneration is in progress</em>')) . '<br>' . PHP_EOL;
      $rio .= 'Processed ' . count($riSavedOptions['processed-ids']) . ' of ' . $riSavedOptions['images-count'] . ' full-size original images';
      if ($riSavedOptions['error']) $rio .= '<br>' . PHP_EOL; // Prepare the error line if needed
    }
    if ($riSavedOptions['error']) $rio .= '<strong style="color:red;">ERROR: ' . $riSavedOptions['error'] . '</strong>';
    $rio .= PHP_EOL;
    echo $rio;
  }
  ?>
  </p>
  <hr>
  <p><em>Note that the checkbox is always saved in its unchecked state<?php echo ($riSavedOptions['start-time'] && !$riSavedOptions['end-time'] && time() - intval($riSavedOptions['last-update']) < 1800) ? '. The checkbox is disabled during image regeneration.' : ''; ?></em></p>
  <p><label><input type="checkbox" value="1" <?php checked(get_option('qnrwp_regenerate_images', $default=0), 1); echo ($riSavedOptions['start-time'] && !$riSavedOptions['end-time'] && time() - intval($riSavedOptions['last-update']) < 1800) ? ' disabled' : ''; ?> name="qnrwp_regenerate_images" id="qnrwp_regenerate_images">Regenerate thumbnails and intermediate image sizes, and reduce originals</label></p>
  <?php
}


// ===================== FILTERS =====================


// ----------------------- Regenerate Images

function qnrwp_pre_update_option_qnrwp_regenerate_images($value, $old_value, $option) {
  // We use this hook instead of 'updated_option' because the latter does not catch unchanged values
  // Test for Regenerate Images checkbox being ticked
  //qnrwp_debug_printout(array('Regenerate images value in pre update call::',$value), $append=false); // TEST TODO
  if ($value === 1 || $value === true || $value === 'on' || $value === '1') {
    // Get the saved options/record array from database, assumed already created as part of UI code
    $riSavedOptions = get_option('qnrwp_regenerate_images_record');
    //qnrwp_debug_printout(array('Regenerate images saved options/record in pre update call::',$riSavedOptions)); // TEST TODO
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
      $riSavedOptions['error'] = 'No images found';
    } else { // Images found
      // Set up the Regenerate Images cron
      if (!wp_next_scheduled('qnrwp_regenerate_images_hook')) { // Hooked function declared in functions.php
        $rSched = wp_schedule_single_event(time() + 10, 'qnrwp_regenerate_images_hook');
        if ($rSched === false) {
          $riSavedOptions['error'] = 'Regenerate Images cron job could not be scheduled';
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
        $riSavedOptions['error'] = 'A Regenerate Images cron job is already scheduled';
      }
    }
    // Save the options/record
    if (!update_option('qnrwp_regenerate_images_record', $riSavedOptions)) wp_die('ERROR: Database options for Regenerate Images could not be saved.');
  } // Else checkbox not ticked, do nothing
  // Always save checkbox unchecked
  return 0;
}
add_filter('pre_update_option_qnrwp_regenerate_images', 'qnrwp_pre_update_option_qnrwp_regenerate_images', 10, 3);


// ----------------------- Other filters

// Empty the annoying admin footer
add_filter('admin_footer_text', function($text){return '';}, 11);
add_filter('update_footer', function($content){return '';}, 11);


// ===================== ENQUEUE =====================

// ----------------------- Admin stylesheet, hiding WP footer
function qnrwp_enqueue_admin_scripts($hook) {
  // Enqueue admin stylesheet
  wp_enqueue_style('qnrwp_a-admin-stylesheet', get_template_directory_uri() . '/qnrwp_a-admin-style.css', null, null);
  // Media Screen JS
  $currentScreen = get_current_screen();
  if($currentScreen->id == 'options-media') {
    wp_enqueue_script('qnrwp_a-admin-media-js', get_template_directory_uri() . '/qnrwp_a-admin-media.js', null, null);
  }
}
add_action('admin_enqueue_scripts', 'qnrwp_enqueue_admin_scripts');

