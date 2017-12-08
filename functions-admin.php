<?php
/*
 * functions-admin.php
 * 
 * Functions for the Admin pages and Ajax
 * 
 */



// ===================== ADMIN DASHBOARD SETTINGS =====================

function qnrwp_admin_settings() {
  /**
   * ----------------------- QNRWP settings on Writing page
   * 
   * Settings for generation of meta and OpenGraph / Twitter card tags:
   *  meta description
   *  meta keywords
   *  meta author
   *  OG title
   *  OG description
   *  OG image URL
   *  Twitter title
   *  Twitter description
   *  Twitter image URL
   *  Twitter large image
   * 
   */
  
  add_settings_section(
    'qnrwp_metatags_section',      // ID attribute of tags
    'QNRWP Meta Tag Settings',     // Section title
    'qnrwp_metatags_section',      // Callback to echo section content
    'writing'                      // Admin page to use
  );
  add_settings_section(
    'qnrwp_favicon_section',      // ID attribute of tags
    'QNRWP Favicon Settings',     // Section title
    'qnrwp_favicon_section',      // Callback to echo section content
    'writing'                     // Admin page to use
  );
  // Use Meta Tags
  add_settings_field(
    'qnrwp_use_meta_tags',          // ID attribute of tags
    'Use Meta Tags',                // Field title
    'qnrwp_use_meta_tags',          // Callback to echo input control
    'writing',                      // Admin page to use
    'qnrwp_metatags_section'        // Section to use
  );
  // Meta Description
  add_settings_field(
    'qnrwp_meta_description',       // ID attribute of tags
    'Meta Description',             // Field title
    'qnrwp_meta_description',       // Callback to echo input control
    'writing',                      // Admin page to use
    'qnrwp_metatags_section'        // Section to use
  );
  // Meta Keywords
  add_settings_field(
    'qnrwp_meta_keywords',         // ID attribute of tags
    'Meta Keywords',               // Field title
    'qnrwp_meta_keywords',         // Callback to echo input control
    'writing',                     // Admin page to use
    'qnrwp_metatags_section'       // Section to use
  );
  // Meta Author
  add_settings_field(
    'qnrwp_meta_author',           // ID attribute of tags
    'Meta Author',                 // Field title
    'qnrwp_meta_author',           // Callback to echo input control
    'writing',                     // Admin page to use
    'qnrwp_metatags_section'       // Section to use
  );
  // Use OpenGraph Tags
  add_settings_field(
    'qnrwp_use_opengraph_tags',          // ID attribute of tags
    'Use OpenGraph Tags',                // Field title
    'qnrwp_use_opengraph_tags',          // Callback to echo input control
    'writing',                           // Admin page to use
    'qnrwp_metatags_section'             // Section to use
  );
  // OpenGraph Title
  add_settings_field(
    'qnrwp_opengraph_title',         // ID attribute of tags
    'OpenGraph Title',               // Field title
    'qnrwp_opengraph_title',         // Callback to echo input control
    'writing',                       // Admin page to use
    'qnrwp_metatags_section'         // Section to use
  );
  // OpenGraph Description
  add_settings_field(
    'qnrwp_opengraph_description',         // ID attribute of tags
    'OpenGraph Description',               // Field title
    'qnrwp_opengraph_description',         // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // OpenGraph Image URL
  add_settings_field(
    'qnrwp_opengraph_imageurl',            // ID attribute of tags
    'OpenGraph Image URL',                 // Field title
    'qnrwp_opengraph_imageurl',            // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Use Twitter Tags
  add_settings_field(
    'qnrwp_use_twitter_tags',          // ID attribute of tags
    'Use Twitter Tags',                // Field title
    'qnrwp_use_twitter_tags',          // Callback to echo input control
    'writing',                         // Admin page to use
    'qnrwp_metatags_section'           // Section to use
  );
  // Twitter Title
  add_settings_field(
    'qnrwp_twitter_title',           // ID attribute of tags
    'Twitter Title',                 // Field title
    'qnrwp_twitter_title',           // Callback to echo input control
    'writing',                       // Admin page to use
    'qnrwp_metatags_section'         // Section to use
  );
  // Twitter Description
  add_settings_field(
    'qnrwp_twitter_description',           // ID attribute of tags
    'Twitter Description',                 // Field title
    'qnrwp_twitter_description',           // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Twitter Image URL
  add_settings_field(
    'qnrwp_twitter_imageurl',              // ID attribute of tags
    'Twitter Image URL',                   // Field title
    'qnrwp_twitter_imageurl',              // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Use Twitter Large Image
  add_settings_field(
    'qnrwp_use_twitter_largeimage',         // ID attribute of tags
    'Use Twitter Large Image',              // Field title
    'qnrwp_use_twitter_largeimage',         // Callback to echo input control
    'writing',                              // Admin page to use
    'qnrwp_metatags_section'                // Section to use
  );
  // Twitter Site
  add_settings_field(
    'qnrwp_twitter_site',                  // ID attribute of tags
    'Twitter Site',                        // Field title
    'qnrwp_twitter_site',                  // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Favicon URL
  add_settings_field(
    'qnrwp_favicon_url',                  // ID attribute of tags
    'Favicon URL',                        // Field title
    'qnrwp_favicon_url',                  // Callback to echo input control
    'writing',                            // Admin page to use
    'qnrwp_favicon_section'               // Section to use
  );
  // Apple Icon URL
  add_settings_field(
    'qnrwp_appleicon_url',                   // ID attribute of tags
    'Apple Icon URL',                        // Field title
    'qnrwp_appleicon_url',                   // Callback to echo input control
    'writing',                               // Admin page to use
    'qnrwp_favicon_section'                  // Section to use
  );
  register_setting('writing', 'qnrwp_use_meta_tags');
  register_setting('writing', 'qnrwp_use_opengraph_tags');
  register_setting('writing', 'qnrwp_use_twitter_tags');
  register_setting('writing', 'qnrwp_meta_description');
  register_setting('writing', 'qnrwp_meta_keywords');
  register_setting('writing', 'qnrwp_meta_author');
  register_setting('writing', 'qnrwp_opengraph_title');
  register_setting('writing', 'qnrwp_opengraph_description');
  register_setting('writing', 'qnrwp_opengraph_imageurl');
  register_setting('writing', 'qnrwp_twitter_title');
  register_setting('writing', 'qnrwp_twitter_description');
  register_setting('writing', 'qnrwp_twitter_imageurl');
  register_setting('writing', 'qnrwp_use_twitter_largeimage');
  register_setting('writing', 'qnrwp_twitter_site');
  register_setting('writing', 'qnrwp_favicon_url');
  register_setting('writing', 'qnrwp_appleicon_url');
  
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


// ----------------------- Media

function qnrwp_media_section() {
  // Callback function for the section; display info about additional sizes
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


// ----------------------- Writing

function qnrwp_metatags_section() {
  // Callback function for the section; display info about meta tags
  ?>
  <div style="font-size:1.1em"><p>This theme supports dynamic generation of meta and OpenGraph / Twitter card tags in the HTML head of every page and post. The settings below define the defaults for the site. On individual posts, the title, description and URL tags will be derived from the post and its Featured Image. If the user prefers to use another image for the tags, a Custom Field may be created in the post interface, named "OpenGraph-Twitter-Card-Image", with the image URL as its value.</p>
  </div>
  <?php
}

function qnrwp_favicon_section() {
  // Callback function for the section
  ?>
  <div style="font-size:1.1em"><p>This theme does not support the Site Icon API, offering a simpler, lighter solution. The small favicon and the larger Apple icon are supported. They should be uploaded to the Media Library and their URLs entered in the fields below.</p>
  </div>
  <?php
}

function qnrwp_use_meta_tags() {
  // Echo the input control for this option
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_meta_tags'), true); ?>
                name="qnrwp_use_meta_tags" id="qnrwp_use_meta_tags">Enable the use of meta tags. If unchecked, the following meta tag settings will have no effect, except that they may be used in OpenGraph / Twitter card tags as defined below.</label></p>
  <?php
}

function qnrwp_use_opengraph_tags() {
  // Echo the input control for this option
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_opengraph_tags'), true); ?>
                name="qnrwp_use_opengraph_tags" id="qnrwp_use_opengraph_tags">Enable the use of OpenGraph tags. If unchecked, the following OpenGraph settings will have no effect.</label></p>
  <?php
}

function qnrwp_use_twitter_tags() {
  // Echo the input control for this option
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_twitter_tags'), true); ?>
                name="qnrwp_use_twitter_tags" id="qnrwp_use_twitter_tags">Enable the use of Twitter tags. If unchecked, the following Twitter settings will have no effect.</label></p>
  <?php
}

function qnrwp_meta_description() {
  // Echo the input control for this option
  ?>
  <p>Enter the site description for use in the meta description tag. If left blank, the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post, up to 255 characters in length.</p>
  <textarea id="qnrwp_meta_description" name="qnrwp_meta_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_meta_description', $default=''); ?></textarea>
  <?php
}

function qnrwp_meta_keywords() {
  // Echo the input control for this option
  ?>
  <p>Enter the keywords for use in the meta keywords tag, up to 255 characters in total, comma separated. If left blank, a keywords meta tag will not be used.</p>
  <textarea id="qnrwp_meta_keywords" name="qnrwp_meta_keywords" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_meta_keywords', $default=''); ?></textarea>
  <?php
}

function qnrwp_meta_author() {
  // Echo the input control for this option
  ?>
  <p>Enter the author name for use in the meta author tag. If left blank, an author meta tag will not be used.</p>
  <input type="text" name="qnrwp_meta_author" id="qnrwp_meta_author" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_meta_author', $default=''); ?>">
  <?php
}

function qnrwp_opengraph_title() {
  // Echo the input control for this option
  ?>
  <p>Enter the site title for the OpenGraph title tag. If left blank, Site Title from General Settings will be used. On individual posts, this tag will be set to the title of the post.</p>
  <input type="text" name="qnrwp_opengraph_title" id="qnrwp_opengraph_title" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_opengraph_title', $default=''); ?>">
  <?php
}

function qnrwp_opengraph_description() {
  // Echo the input control for this option
  ?>
  <p>Enter the site description for use in the OpenGraph description tag. If left blank, the meta description will be used, if it is set above, otherwise the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post.</p>
  <textarea id="qnrwp_opengraph_description" name="qnrwp_opengraph_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_opengraph_description', $default=''); ?></textarea>
  <?php
}

function qnrwp_opengraph_imageurl() {
  // Echo the input control for this option
  ?>
  <p>Enter the OpenGraph image URL. If left blank, this tag will not be used. On individual posts, a post image will be used as explained in the section introduction above.</p>
  <input type="text" name="qnrwp_opengraph_imageurl" id="qnrwp_opengraph_imageurl" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_opengraph_imageurl', $default=''); ?>">
  <?php
}

function qnrwp_twitter_title() {
  // Echo the input control for this option
  ?>
  <p>Enter the site title for the Twitter title tag. If left blank, Site Title from General Settings will be used. On individual posts, this tag will be set to the title of the post.</p>
  <input type="text" name="qnrwp_twitter_title" id="qnrwp_twitter_title" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_twitter_title', $default=''); ?>">
  <?php
}

function qnrwp_twitter_description() {
  // Echo the input control for this option
  ?>
  <p>Enter the site description for use in the Twitter description tag. If left blank, the meta description will be used, if it is set, otherwise the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post.</p>
  <textarea id="qnrwp_twitter_description" name="qnrwp_twitter_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_twitter_description', $default=''); ?></textarea>
  <?php
}

function qnrwp_twitter_imageurl() {
  // Echo the input control for this option
  ?>
  <p>Enter the Twitter image URL. If left blank, this tag will not be used. On individual posts, a post image will be used as explained in the section introduction above.</p>
  <input type="text" name="qnrwp_twitter_imageurl" id="qnrwp_twitter_imageurl" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_twitter_imageurl', $default=''); ?>">
  <?php
}

function qnrwp_use_twitter_largeimage() {
  // Echo the input control for this option
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_twitter_largeimage'), true); ?>
                name="qnrwp_use_twitter_largeimage" id="qnrwp_use_twitter_largeimage">Use 'summary_large_image' meta tag instead of 'summary', for a larger image.</label></p>
  <?php
}

function qnrwp_twitter_site() {
  // Echo the input control for this option
  ?>
  <p>Enter the Twitter account handle (like "@twitter", without the quotes) to associate with this website. If left blank, this tag will not be used.</p>
  <input type="text" name="qnrwp_twitter_site" id="qnrwp_twitter_site" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_twitter_site', $default=''); ?>">
  <?php
}

function qnrwp_favicon_url() {
  // Echo the input control for this option
  // Use a HR for separation from the meta tag settings
  ?>
  <p>Enter the favicon.ico URL. If left blank, a favicon will not be used. Dimensions should be 32px x 32px.</p>
  <input type="text" name="qnrwp_favicon_url" id="qnrwp_favicon_url" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_favicon_url', $default=''); ?>">
  <?php
}

function qnrwp_appleicon_url() {
  // Echo the input control for this option
  ?>
  <p>Enter the Apple icon URL. If left blank, an Apple icon will not be used. Dimensions should be 256px x 256px, and the file in PNG format.</p>
  <input type="text" name="qnrwp_appleicon_url" id="qnrwp_appleicon_url" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_appleicon_url', $default=''); ?>">
  <?php
}


// ===================== FILTERS =====================


// ----------------------- Regenerate Images

function qnrwp_pre_update_option_qnrwp_regenerate_images($value, $old_value, $option) {
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

// ----------------------- Admin stylesheet and script
add_action('admin_enqueue_scripts', function($hook) {
  // Enqueue admin stylesheet
  wp_enqueue_style('qnrwp_a-admin-stylesheet', get_template_directory_uri() . '/qnrwp_a-admin-style.css', null, null);
  // Media Screen JS (as this is an admin page, we don't bother placing the script at end of page)
  $currentScreen = get_current_screen();
  if($currentScreen->id == 'options-media') {
    wp_enqueue_script('qnrwp_a-admin-media-js', get_template_directory_uri() . '/qnrwp_a-admin-media.js', null, null);
  }
});


// ===================== SIMPLIFY DASHBOARD FOR NON-ADMINS =====================

if (!current_user_can('manage_options')) {

  add_action('admin_init', function() {
    
    // ----------------------- Redirect from Dashboard to Posts
    if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/index.php') !== false) {
      wp_redirect('edit.php');
      exit;
    }
    
    // ----------------------- Remove 'Drag boxes here' on Dashboard
    // NOT USED, as we now redirect from Dashboard (and this spoils collapse bars generally)
    // Disable the feature by deregistering script
    //wp_deregister_script('postbox');
    
    // ----------------------- Prepare global for hiding widget definition pages
    // We do this here to avoid impossible recursion problem in pre_get_posts below,
    //   taking care to control the flow with state of the qnrwpWidgetPageIDs global
    if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/edit.php') !== false && strpos($_SERVER['QUERY_STRING'], 'post_type=page') !== false) {
      if (!isset($GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'])) {
        // Get IDs of widget pages
        $allPages = get_posts(array(
                      'numberposts' => -1,
                      'nopaging' => true,
                      'no_found_rows' => true,
                      'post_type' => 'page',
                      'fields' => 'ids',
                      'suppress_filters' => true, // Doesn't seem to work...
                    ));
        $GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'] = [];
        foreach ($allPages as $pageID) {
          $page = get_post($pageID);
          if (strpos(get_the_title($pageID), 'QNRWP-Widget-') !== false) {
            $GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'][] = $pageID;
          } else if ($page->post_parent !== 0) {
            if (strpos(get_the_title($page->post_parent), 'QNRWP-Widget-') !== false) {
              $GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'][] = $pageID;
            }
          }
        }
      } // Else do nothing, the global is set
    }
  }); // End of admin_init
  
  // ----------------------- Hide Pages defining QNRWP Widgets
  // $query is global $wp_query object passed by reference
  add_action('pre_get_posts', function($query) {
    if ($query->is_admin && !$query->in_the_loop && strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/edit.php') !== false 
            && strpos($_SERVER['QUERY_STRING'], 'post_type=page') !== false && isset($GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'])) {
      // If we pass the test, we assume we're in WP_Posts_List_Table, can change query
      $query->set('post__not_in', $GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs']);
    }
  });
  
  // ----------------------- Change posts count to actual
  add_filter('wp_count_posts', function($counts, $type='page', $perm='readable') {
    if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin/edit.php') !== false && strpos($_SERVER['QUERY_STRING'], 'post_type=page') !== false 
            && isset($GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs'])) {
      //qnrwp_debug_printout($counts, $append=false);
      $counts->publish -= count($GLOBALS['QNRWP_GLOBALS']['qnrwpWidgetPageIDs']); // We assume widget definition pages are published
    }
    return $counts;
  }, 10, 3);
  
  // ----------------------- Remove from vertical menu on the left
  add_action('admin_menu', function() {
    remove_menu_page('index.php');                  // Dashboard
    remove_menu_page('jetpack');                    // Jetpack*
    //remove_menu_page('edit.php');                   // Posts
    //remove_menu_page('upload.php');                 // Media
    //remove_menu_page('edit.php?post_type=page');    // Pages
    remove_menu_page('edit-comments.php');          // Comments
    remove_menu_page('themes.php');                 // Appearance
    remove_menu_page('plugins.php');                // Plugins
    remove_menu_page('users.php');                  // Users
    remove_menu_page('tools.php');                  // Tools
    remove_menu_page('options-general.php');        // Settings TODO ??
    remove_menu_page('link-manager.php');           // Links
    // Remove Category submenu under Posts
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    // Remove Tags submenu under Posts
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
  }, 999);
    
  // ----------------------- Edit top Toolbar
  add_action('admin_bar_menu', function($wp_admin_bar) {
    // Remove comments item as we don't use comments
    $wp_admin_bar->remove_node('comments');
    // Remove new-user item from New menu
    $wp_admin_bar->remove_node('new-user');
    $wp_admin_bar->remove_node('user-info');
    // Edit 'Howdy,' on the right
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle = str_replace('Howdy,', 'Logged in as', $my_account->title);
    // add_node() will update existing
    $wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $newtitle));
  }, 999);

  // ----------------------- Edit At a Glance in Dashboard (not needed any more)
  // Return empty to get rid of 'WordPress xx running xx theme'
  //add_filter('update_right_now_text', function() {return '';});
  
  // ----------------------- Remove Screen Options tab
  add_filter('screen_options_show_screen', function() {return false;});
} // End of simplifying

