<?php
/*
 * functions-admin.php
 * 
 * Functions for the Admin pages and Ajax
 * 
 */

defined( 'ABSPATH' ) || exit;

// ===================== ADMIN DASHBOARD SETTINGS =====================

/**
 * Meta tags admin settings setup and registration
 */
function qnrwp_admin_settings_metatags() {
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
    __('QNRWP Meta Tag Settings', 'qnrwp'),     // Section title
    'qnrwp_metatags_section',      // Callback to echo section content
    'writing'                      // Admin page to use
  );
  
  // Use Meta Tags
  add_settings_field(
    'qnrwp_use_meta_tags',          // ID attribute of tags
    __('Use Meta Tags', 'qnrwp'),                // Field title
    'qnrwp_use_meta_tags',          // Callback to echo input control
    'writing',                      // Admin page to use
    'qnrwp_metatags_section'        // Section to use
  );
  // Meta Description
  add_settings_field(
    'qnrwp_meta_description',       // ID attribute of tags
    __('Meta Description', 'qnrwp'),             // Field title
    'qnrwp_meta_description',       // Callback to echo input control
    'writing',                      // Admin page to use
    'qnrwp_metatags_section'        // Section to use
  );
  // Meta Keywords
  add_settings_field(
    'qnrwp_meta_keywords',         // ID attribute of tags
    __('Meta Keywords', 'qnrwp'),               // Field title
    'qnrwp_meta_keywords',         // Callback to echo input control
    'writing',                     // Admin page to use
    'qnrwp_metatags_section'       // Section to use
  );
  // Meta Author
  add_settings_field(
    'qnrwp_meta_author',           // ID attribute of tags
    __('Meta Author', 'qnrwp'),                 // Field title
    'qnrwp_meta_author',           // Callback to echo input control
    'writing',                     // Admin page to use
    'qnrwp_metatags_section'       // Section to use
  );
  // Use OpenGraph Tags
  add_settings_field(
    'qnrwp_use_opengraph_tags',          // ID attribute of tags
    __('Use OpenGraph Tags', 'qnrwp'),                // Field title
    'qnrwp_use_opengraph_tags',          // Callback to echo input control
    'writing',                           // Admin page to use
    'qnrwp_metatags_section'             // Section to use
  );
  // OpenGraph Title
  add_settings_field(
    'qnrwp_opengraph_title',         // ID attribute of tags
    __('OpenGraph Title', 'qnrwp'),               // Field title
    'qnrwp_opengraph_title',         // Callback to echo input control
    'writing',                       // Admin page to use
    'qnrwp_metatags_section'         // Section to use
  );
  // OpenGraph Description
  add_settings_field(
    'qnrwp_opengraph_description',         // ID attribute of tags
    __('OpenGraph Description', 'qnrwp'),               // Field title
    'qnrwp_opengraph_description',         // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // OpenGraph Image URL
  add_settings_field(
    'qnrwp_opengraph_imageurl',            // ID attribute of tags
    __('OpenGraph Image URL', 'qnrwp'),                 // Field title
    'qnrwp_opengraph_imageurl',            // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Use Twitter Tags
  add_settings_field(
    'qnrwp_use_twitter_tags',          // ID attribute of tags
    __('Use Twitter Tags', 'qnrwp'),                // Field title
    'qnrwp_use_twitter_tags',          // Callback to echo input control
    'writing',                         // Admin page to use
    'qnrwp_metatags_section'           // Section to use
  );
  // Twitter Title
  add_settings_field(
    'qnrwp_twitter_title',           // ID attribute of tags
    __('Twitter Title', 'qnrwp'),                 // Field title
    'qnrwp_twitter_title',           // Callback to echo input control
    'writing',                       // Admin page to use
    'qnrwp_metatags_section'         // Section to use
  );
  // Twitter Description
  add_settings_field(
    'qnrwp_twitter_description',           // ID attribute of tags
    __('Twitter Description', 'qnrwp'),                 // Field title
    'qnrwp_twitter_description',           // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Twitter Image URL
  add_settings_field(
    'qnrwp_twitter_imageurl',              // ID attribute of tags
    __('Twitter Image URL', 'qnrwp'),                   // Field title
    'qnrwp_twitter_imageurl',              // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
  );
  // Use Twitter Large Image
  add_settings_field(
    'qnrwp_use_twitter_largeimage',         // ID attribute of tags
    __('Use Twitter Large Image', 'qnrwp'),              // Field title
    'qnrwp_use_twitter_largeimage',         // Callback to echo input control
    'writing',                              // Admin page to use
    'qnrwp_metatags_section'                // Section to use
  );
  // Twitter Site
  add_settings_field(
    'qnrwp_twitter_site',                  // ID attribute of tags
    __('Twitter Site', 'qnrwp'),                        // Field title
    'qnrwp_twitter_site',                  // Callback to echo input control
    'writing',                             // Admin page to use
    'qnrwp_metatags_section'               // Section to use
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
}

/**
 * Media admin settings setup and registration
 */
function qnrwp_admin_settings_media() {
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
    __('QNRWP Media Settings', 'qnrwp'),     // Section title
    'qnrwp_media_section',      // Callback to echo section content
    'media'                     // Admin page to use
  );
  // JPEG Quality
  add_settings_field(
    'qnrwp_jpeg_quality',       // ID attribute of tags
    __('JPEG Quality', 'qnrwp'),             // Field title
    'qnrwp_jpeg_quality',       // Callback to echo input control
    'media',                    // Admin page to use
    'qnrwp_media_section'       // Section to use
  );
  // Regenerate Images
  add_settings_field(
    'qnrwp_regenerate_images',  // ID attribute of tags
    __('Regenerate Images', 'qnrwp'),        // Field title
    'qnrwp_regenerate_images',  // Callback to echo input control
    'media',                    // Admin page to use
    'qnrwp_media_section'       // Section to use
  );
  register_setting('media', 'qnrwp_jpeg_quality');
  register_setting('media', 'qnrwp_regenerate_images');
}

if (QNRWP::get_setting('feature-metatags') !== 0) {
  add_action('admin_init', 'qnrwp_admin_settings_metatags');
}
add_action('admin_init', 'qnrwp_admin_settings_media');


// ----------------------- MEDIA

/**
 * Callback function for the section; display info about additional sizes
 */
function qnrwp_media_section() {
  ?>
  <div style="font-size:1.1em"><p><?php esc_html_e('This theme defines and makes available three additional sizes not listed above:', 'qnrwp'); ?></p>
  <ul>
  <li><b><?php esc_html_e('QNRWP-Larger', 'qnrwp'); ?></b> - <?php esc_html_e('1600px width, height proportional', 'qnrwp'); ?></li>
  <li><b><?php esc_html_e('QNRWP-Largest', 'qnrwp'); ?></b> - <?php esc_html_e('2000px width, height proportional', 'qnrwp'); ?></li>
  <li><b><?php esc_html_e('QNRWP-Extra', 'qnrwp'); ?></b> - <?php esc_html_e('2500px width, height proportional', 'qnrwp'); ?></li>
  </ul>
  <p><?php _e('In addition, this theme exposes to the user the fixed and "hidden" <b>Medium Large</b> size, 768px width, height proportional.', 'qnrwp'); ?></p>
  </div>
  <?php
}

/**
 * Echos the input control for this option
 */
function qnrwp_jpeg_quality() {
  ?>
  <p><?php esc_html_e('Enter the JPEG quality setting (1-100) to use when Wordpress generates thumbnails and intermediate image sizes from the upload.', 'qnrwp'); ?></p>
  <label><input type="number" name="qnrwp_jpeg_quality" id="qnrwp_jpeg_quality" min="1" max="100" required 
                maxlength="3" size="4" class="small-text" value="<?php echo get_option('qnrwp_jpeg_quality', $default='60'); ?>"></label>
  <?php
}

/**
 * Echos the UI, the work is done by regenerate_images_cron() in QNRWP_Imaging
 */
function qnrwp_regenerate_images() {
  ?>
  <p><?php _e('Tick the checkbox below so that when you press the <code>Save Changes</code> button, a Wordpress cron job will be started, working in the background on the server, regenerating thumbnail and intermediate size images from their JPEG, PNG and GIF full-size originals, using the settings on this page. Existing images of same sizes as specified on this page will be overwritten, but any other sizes will be left untouched.', 'qnrwp'); ?></p>
  <hr>
  <p><?php esc_html_e('In addition, JPEG, PNG and GIF full-size originals wider than 2500px will be reduced and overwritten, matching the QNRWP-Extra size - 2500px width, height proportional. To ensure higher JPEG quality, compression quality will be at the half point between the setting on this page and 100. For example, if the JPEG Quality setting on this page is 60, then 80 will be used for the reduction of full-size originals. This reduction also happens during image upload.', 'qnrwp'); ?></p>
  <hr>
  <p><span style="color:red;"><?php _e('Processing cannot be cancelled or undone, and depending on the size and number of images, may take up to several hours or days.</span> The website will continue to function normally during this time. Processing may fail if your server employs a timeout preventing long-running scripts - check your server configuration before proceeding. If processing fails part way through, it will start where it left off on the next run, provided that the settings on this page are unchanged; otherwise processing will start again from the beginning. If starting from the beginning after a failed partial run, the previously achieved tally will be shown below for a few minutes before starting from 0 again. Any plugins that change how Wordpress cron jobs work may interfere with image regeneration.', 'qnrwp'); ?></p>
  <hr>
  <p id="regenerate-images-last"><?php esc_html_e('Details of the most recent regeneration will be shown here when the cron job is started.', 'qnrwp'); ?></p>
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
      $rio .= esc_html__('Last regeneration started', 'qnrwp').': ' . $riSavedOptions['start-time'] . '<br>' . PHP_EOL;
      $rio .= esc_html__('Last regeneration ended', 'qnrwp').': ' 
              . (($riSavedOptions['end-time']) 
              ? $riSavedOptions['end-time'] 
              : ((time() - intval($riSavedOptions['last-update']) >= 1800) 
              ? '<em>'.esc_html__('image regeneration has stopped prematurely', 'qnrwp').'</em>' 
              : '<em>'.esc_html__('image regeneration is in progress', 'qnrwp').'</em>')) . '<br>' . PHP_EOL;
      $rio .= sprintf(esc_html__('Processed %d of %d full-size original images', 'qnrwp'), count($riSavedOptions['processed-ids']), $riSavedOptions['images-count']);
      //$rio .= 'Processed ' . count($riSavedOptions['processed-ids']) . ' of ' . $riSavedOptions['images-count'] . ' full-size original images';
      if ($riSavedOptions['error']) $rio .= '<br>' . PHP_EOL; // Prepare the error line if needed
    }
    if ($riSavedOptions['error']) $rio .= '<strong style="color:red;">ERROR: ' . $riSavedOptions['error'] . '</strong>';
    $rio .= PHP_EOL;
    echo $rio;
  }
  ?>
  </p>
  <hr>
  <p><em><?php esc_html_e('Note that the checkbox is always saved in its unchecked state', 'qnrwp'); ?><?php echo ($riSavedOptions['start-time'] && !$riSavedOptions['end-time'] && time() - intval($riSavedOptions['last-update']) < 1800) ? '. '.esc_html__('The checkbox is disabled during image regeneration.', 'qnrwp') : ''; ?></em></p>
  <p><label><input type="checkbox" value="1" <?php checked(get_option('qnrwp_regenerate_images', $default=0), 1); echo ($riSavedOptions['start-time'] && !$riSavedOptions['end-time'] && time() - intval($riSavedOptions['last-update']) < 1800) ? ' disabled' : ''; ?> name="qnrwp_regenerate_images" id="qnrwp_regenerate_images"><?php esc_html_e('Regenerate thumbnails and intermediate image sizes, and reduce originals', 'qnrwp'); ?></label></p>
  <?php
}


// ----------------------- WRITING

// ----------------------- Sections

/**
 * Callback function for the meta tags section; display info about meta tags
 */
function qnrwp_metatags_section() {
  ?>
  <div style="font-size:1.1em"><p><?php esc_html_e('This theme supports dynamic generation of meta and OpenGraph / Twitter card tags in the HTML head of every page and post. The settings below define the defaults for the site. On individual posts, the title, description and URL tags will be derived from the post and its Featured Image. If the user prefers to use another image for the tags, a Custom Field may be created in the post interface, named "OpenGraph-Twitter-Card-Image", with the image URL as its value.', 'qnrwp'); ?></p>
  </div>
  <?php
}


// ----------------------- Options

/**
 * Echoes the input control for meta tags option
 */
function qnrwp_use_meta_tags() {
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_meta_tags'), true); ?>
                name="qnrwp_use_meta_tags" id="qnrwp_use_meta_tags"><?php esc_html_e('Enable the use of meta tags. If unchecked, the following meta tag settings will have no effect, except that they may be used in OpenGraph / Twitter card tags as defined below.', 'qnrwp'); ?></label></p>
  <?php
}

/**
 * Echoes the input control for OpenGraph tags option
 */
function qnrwp_use_opengraph_tags() {
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_opengraph_tags'), true); ?>
                name="qnrwp_use_opengraph_tags" id="qnrwp_use_opengraph_tags"><?php esc_html_e('Enable the use of OpenGraph tags. If unchecked, the following OpenGraph settings will have no effect.', 'qnrwp'); ?></label></p>
  <?php
}

/**
 * Echoes the input control for Twitter tags option
 */
function qnrwp_use_twitter_tags() {
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_twitter_tags'), true); ?>
                name="qnrwp_use_twitter_tags" id="qnrwp_use_twitter_tags"><?php esc_html_e('Enable the use of Twitter tags. If unchecked, the following Twitter settings will have no effect.', 'qnrwp'); ?></label></p>
  <?php
}

/**
 * Echoes the input control for meta description tag
 */
function qnrwp_meta_description() {
  ?>
  <p><?php esc_html_e('Enter the site description for use in the meta description tag. If left blank, the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post, up to 255 characters in length.', 'qnrwp'); ?></p>
  <textarea id="qnrwp_meta_description" name="qnrwp_meta_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_meta_description', $default=''); ?></textarea>
  <?php
}

/**
 * Echoes the input control for meta keywords tag
 */
function qnrwp_meta_keywords() {
  ?>
  <p><?php esc_html_e('Enter the keywords for use in the meta keywords tag, up to 255 characters in total, comma separated. If left blank, a keywords meta tag will not be used.', 'qnrwp'); ?></p>
  <textarea id="qnrwp_meta_keywords" name="qnrwp_meta_keywords" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_meta_keywords', $default=''); ?></textarea>
  <?php
}

/**
 * Echoes the input control for meta author tag
 */
function qnrwp_meta_author() {
  ?>
  <p><?php esc_html_e('Enter the author name for use in the meta author tag. If left blank, an author meta tag will not be used.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_meta_author" id="qnrwp_meta_author" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_meta_author', $default=''); ?>">
  <?php
}

/**
 * Echoes the input control for OpenGraph title tag
 */
function qnrwp_opengraph_title() {
  ?>
  <p><?php esc_html_e('Enter the site title for the OpenGraph title tag. If left blank, Site Title from General Settings will be used. On individual posts, this tag will be set to the title of the post.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_opengraph_title" id="qnrwp_opengraph_title" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_opengraph_title', $default=''); ?>">
  <?php
}

/**
 * Echoes the input control for OpenGraph description
 */
function qnrwp_opengraph_description() {
  ?>
  <p><?php esc_html_e('Enter the site description for use in the OpenGraph description tag. If left blank, the meta description will be used, if it is set above, otherwise the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post.', 'qnrwp'); ?></p>
  <textarea id="qnrwp_opengraph_description" name="qnrwp_opengraph_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_opengraph_description', $default=''); ?></textarea>
  <?php
}

/**
 * Echoes the input control for OpenGraph image URL
 */
function qnrwp_opengraph_imageurl() {
  ?>
  <p><?php esc_html_e('Enter the OpenGraph image URL. If left blank, this tag will not be used. On individual posts, a post image will be used as explained in the section introduction above.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_opengraph_imageurl" id="qnrwp_opengraph_imageurl" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_opengraph_imageurl', $default=''); ?>">
  <?php
}

/**
 * Echoes the input control for Twitter title tag
 */
function qnrwp_twitter_title() {
  ?>
  <p><?php esc_html_e('Enter the site title for the Twitter title tag. If left blank, Site Title from General Settings will be used. On individual posts, this tag will be set to the title of the post.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_twitter_title" id="qnrwp_twitter_title" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_twitter_title', $default=''); ?>">
  <?php
}

/**
 * Echoes the input control for Twitter site description
 */
function qnrwp_twitter_description() {
  ?>
  <p><?php esc_html_e('Enter the site description for use in the Twitter description tag. If left blank, the meta description will be used, if it is set, otherwise the Tagline from General Settings will be used. On individual posts, this tag will be set to the opening paragraph of the post.', 'qnrwp'); ?></p>
  <textarea id="qnrwp_twitter_description" name="qnrwp_twitter_description" 
                class="large-text code" rows="2"><?php echo get_option('qnrwp_twitter_description', $default=''); ?></textarea>
  <?php
}

/**
 * Echoes the input control for Twitter image URL
 */
function qnrwp_twitter_imageurl() {
  ?>
  <p><?php esc_html_e('Enter the Twitter image URL. If left blank, this tag will not be used. On individual posts, a post image will be used as explained in the section introduction above.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_twitter_imageurl" id="qnrwp_twitter_imageurl" 
                maxlength="255" class="regular-text" value="<?php echo get_option('qnrwp_twitter_imageurl', $default=''); ?>">
  <?php
}

/**
 * Echoes the input control for Twitter large image option
 */
function qnrwp_use_twitter_largeimage() {
  ?>
  <p><label><input type="checkbox" value="1" <?php checked(1, get_option('qnrwp_use_twitter_largeimage'), true); ?>
                name="qnrwp_use_twitter_largeimage" id="qnrwp_use_twitter_largeimage"><?php esc_html_e('Use "summary_large_image" meta tag instead of "summary", for a larger image', 'qnrwp'); ?></label></p>
  <?php
}

/**
 * Echoes the input control for Twitter account handle
 */
function qnrwp_twitter_site() {
  ?>
  <p><?php esc_html_e('Enter the Twitter account handle (like "@twitter", without the quotes) to associate with this website. If left blank, this tag will not be used.', 'qnrwp'); ?></p>
  <input type="text" name="qnrwp_twitter_site" id="qnrwp_twitter_site" 
                maxlength="100" class="regular-text" value="<?php echo get_option('qnrwp_twitter_site', $default=''); ?>">
  <?php
}


// ===================== FILTERS =====================

/**
 * Remove the annoying admin footer parts
 */
function qnrwp_remove_admin_footer_text($text) {
  return '';
}
add_filter('admin_footer_text', 'qnrwp_remove_admin_footer_text', 11);
function qnrwp_remove_update_footer($content) {
  return '';
}
add_filter('update_footer', 'qnrwp_remove_update_footer', 11);



// ===================== ADMIN MENUS =====================

/**
 * Edit posts menus
 */
function qnrwp_edit_posts_menus() {
  //qnrwp_debug_printout(get_categories(), $append=false);
  // Get categories for main menus
  $cats = get_categories();
  $catCount = 0;
  foreach ($cats as $key => $cat) {
    // Use only categories with no parent and some posts assigned
    if (!$cat->parent && $cat->count) {
      // Get posts matching category
      $catPosts = get_posts(array(
                                  'post_type' => 'post',
                                  'post_status' => 'publish,future,draft,pending,private',
                                  'nopaging' => false,
                                  'posts_per_page' => 20,
                                  'paged' => 1,
                                  'category_name' => $cat->slug,
                                  ));
      // Create main menu for this category
      $catCount += 1;
      //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
      add_menu_page(sprintf(__('Edit %s Posts', 'qnrwp'), esc_attr($cat->name)), // Page title
                    sprintf(__('%s Posts', 'qnrwp'), esc_attr($cat->name)), // Menu title (no 'Edit')
                    'edit_posts',
                    'edit.php?category_name='.$cat->slug, '', 'dashicons-edit', 5+$catCount);
      foreach ($catPosts as $key => $catPost) {
        if ($key == 0) {
          // Account for the first submenu-as-duplicate of new top menu problem, neatly solving as "All CAT Posts"
          add_submenu_page('edit.php?category_name='.$cat->slug, 
                            __('Posts', 'qnrwp'), 
                            sprintf(__('All %s Posts', 'qnrwp'), esc_attr($cat->name)), 
                            'edit_posts', 
                            'edit.php?category_name='.$cat->slug, 
                            '');
        }
        add_submenu_page('edit.php?category_name='.$cat->slug, 
                          __('Edit Post', 'qnrwp'), 
                          esc_attr($catPost->post_title), 
                          'edit_posts', 
                          'post.php?post='.$catPost->ID.'&action=edit', 
                          '');
      }
    }
  }
}
add_action('admin_menu', 'qnrwp_edit_posts_menus');


/**
 * Edit pages menus
 */
function qnrwp_edit_pages_menus() {
  $pages = get_posts(array(
                              'post_type' => 'page',
                              'post_status' => 'publish,future,draft,pending,private',
                              'nopaging' => true,
                              ));
  $pageCount = 0;
  foreach ($pages as $key => $page) {
    if ($pageCount < 20) {
      add_submenu_page(
                        'edit.php?post_type=page',                  // Parent menu slug
                        __('Edit Page', 'qnrwp'),                   // Page title
                        esc_attr($page->post_title),                // Submenu title
                        'edit_pages',                               // User capability
                        'post.php?post='.$page->ID.'&action=edit',  // Submenu slug
                        ''                                          // Callback
                        );
      $pageCount += 1;
    }
  }
}
add_action('admin_menu', 'qnrwp_edit_pages_menus');


/**
 * Filter submenu NOT USED as it only affects highlighting of submenu item
 * 
 * TODO
 * 
 * https://stackoverflow.com/questions/2308569/manually-highlight-wordpress-admin-menu-item
 */
function qnrwp_filter_submenu($submenu_file, $parent_file) {
  //qnrwp_debug_printout('Submenus debug');
  //qnrwp_debug_printout($submenu_file, $append=true);
  //qnrwp_debug_printout($parent_file, $append=true);
  //$submenu_file = 'edit-tags.php?taxonomy=post_tag';
  //qnrwp_debug_printout("geting", $append=false);
  //qnrwp_debug_printout($_GET, $append=true);
  if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
    $submenu_file = 'post.php?post='.$_GET['post'].'&action=edit';
  } else if (isset($_GET['category_name'])) {
    $submenu_file = 'edit.php?category_name='.$_GET['category_name'];
  }
  return $submenu_file;
}
add_filter('submenu_file', 'qnrwp_filter_submenu', 10, 2);


// ===================== SIMPLIFY DASHBOARD FOR NON-ADMINS =====================

if (!current_user_can('manage_options') && (isset(get_option('qnrwp_settings_array')['admin-simplify']) && get_option('qnrwp_settings_array')['admin-simplify'] == 1)) {
  
  /**
   * Remove from vertical menu on the left
   */
  function qnrwp_remove_admin_menu_items() {
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
  }
  add_action('admin_menu', 'qnrwp_remove_admin_menu_items', 999);
  
  
  /**
   * Edit top Toolbar
   */
  function qnrwp_edit_top_toolbar($wp_admin_bar) {
    // Remove comments item as we don't use comments
    $wp_admin_bar->remove_node('comments');
    // Remove new-user item from New menu
    $wp_admin_bar->remove_node('new-user');
    $wp_admin_bar->remove_node('user-info');
    // Edit 'Howdy,' on the right
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle = str_replace('Howdy,', __('Logged in as', 'qnrwp'), $my_account->title);
    // add_node() will update existing
    $wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $newtitle));
  }
  add_action('admin_bar_menu', 'qnrwp_edit_top_toolbar', 999);

  // ----------------------- Edit At a Glance in Dashboard (not needed any more)
  // Return empty to get rid of 'WordPress xx running xx theme'
  //add_filter('update_right_now_text', function() {return '';});
  
  
  /**
   * Remove Screen Options tab
   */
  function qnrwp_remove_screen_options_tab() {
    return false;
  }
  add_filter('screen_options_show_screen', 'qnrwp_remove_screen_options_tab');
  
} // End of simplifying

