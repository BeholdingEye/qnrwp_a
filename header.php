<?php
/*
 * header.php
 */

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
echo QNRWP_Meta_Tags::meta_opengraph_twitter_tags();
wp_head(); // Required for title-tag and site icons
?>
</head>
<body <?php body_class('qnr-winscroller'); ?> data-qnr-offset="-4" style="visibility:hidden;opacity:0;">
<!-- Error reporting: <?php echo error_reporting(); // Show in source that we're reporting errors ?> -->
<!-- Header Row -->
<header id="header-row" class="header-row widget-area<?php echo QNRWP::get_setting('header-fixed', $default=1)
                                                              ?' qnrwp-has-fixed-header'
                                                              :''; ?>">
  <?php 
  QNRWP_UI_Parts::render_cookie_notice();
  if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-header')) {
      dynamic_sidebar('qnrwp-row-header');
  }
  ?>
  <div id="header-nav-row" class="<?php echo apply_filters('qnrwp_header_nav_row_class', 'header-nav-row flex-block flex-vertical-bottom-content'); ?>">
    <?php 
    QNRWP_UI_Parts::render_site_logo();

    QNRWP_UI_Parts::render_main_navigation_menu();
    ?>
  </div>
</header><!-- End of Header -->
<!-- Content Row -->
<div id="content-row" class="content-row<?php echo QNRWP::get_setting('header-fixed', $default=1)?' qnrwp-has-fixed-header':''; ?>">
<?php 
QNRWP::get_layout(); // Record layout type in our global
