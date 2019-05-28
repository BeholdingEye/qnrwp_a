<?php
/*
 * header.php
 */

defined( 'ABSPATH' ) || exit;

// -----------------------  Error-to-exception Handler

//error_reporting(E_ALL); // Report all PHP errors
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
ini_set('display_errors', 1); // Change default PHP config

function exception_error_handler($severity, $message, $file, $line) {
  if (!(error_reporting() & $severity)) {
    // This error code is not included in error_reporting
    return;
  }
  throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('exception_error_handler');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if (class_exists('QNRWP_Meta_Tags')) echo QNRWP_Meta_Tags::meta_opengraph_twitter_tags(); // Feature may be disabled

wp_head(); // Required for title-tag and site icons
?>
</head>
<body <?php if (has_header_image()) body_class('has-header-image qnr-winscroller'); else body_class('qnr-winscroller'); // TODO ?> data-qnr-offset="-4">
<!-- Error reporting: <?php echo error_reporting(); // Show in source that we're reporting errors ?> -->
<!-- Header Row -->
<header id="header-row" class="header-row widget-area<?php echo QNRWP::get_setting('header-fixed', $default=1)
                                                              ?' qnrwp-has-fixed-header'
                                                              :''; ?>">
  <?php 
  QNRWP_UI_Parts::render_header_image();

  if (QNRWP::get_setting('cookie-notice') == 1 && QNRWP::get_setting('cookie-notice-placement') != 1) {
    QNRWP_UI_Parts::render_cookie_notice();
  }

  if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-header')) {
      dynamic_sidebar('qnrwp-row-header');
  }
  
  /**
   * Render QNR Navbar menu or a third-party solution
   */
  if (QNRWP::get_setting('feature-mainnavmenu') !== 0):
  ?>
  <div class="<?php echo apply_filters('qnrwp_header_qnr_nav_row_class', 'qnr-navbar-header qnr-style-light'); ?>">
    <?php 
    
    QNRWP_UI_Parts::render_site_logo();
    
    do_action('qnrwp_header_qnr_nav_row_before_menu');

    QNRWP_UI_Parts::render_main_navigation_menu();
    
    do_action('qnrwp_header_qnr_nav_row_after_menu');
    
    QNRWP_UI_Parts::render_main_navigation_menu_hamburger();
    ?>
  </div>
  <?php else: ?>
  <div class="<?php echo apply_filters('qnrwp_header_generic_nav_row_class', 'header-nav-row'); ?>">
    <?php 
    
    QNRWP_UI_Parts::render_site_logo();
    
    do_action('qnrwp_header_generic_nav_row_before_menu');

    QNRWP_UI_Parts::render_main_navigation_menu();
    
    do_action('qnrwp_header_generic_nav_row_after_menu');
    ?>
  </div>
  <?php endif; ?>
</header><!-- End of Header -->
<!-- Content Row -->
<div id="content-row" class="content-row<?php echo QNRWP::get_setting('header-fixed', $default=1)?' qnrwp-has-fixed-header':''; ?>">
<?php 
QNRWP::get_layout(); // Record layout type in our global