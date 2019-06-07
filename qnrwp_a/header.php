<?php
/*
 * header.php
 */

defined( 'ABSPATH' ) || exit;

?>
<!DOCTYPE html>
<html <?php if (QNRWP::get_setting('feature-bootstrap') == 1 && apply_filters('qnrwp_bootstrap_class_html_and_body', false)) echo 'class="bootstrap431" '; language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if (class_exists('QNRWP_Meta_Tags')) echo QNRWP_Meta_Tags::meta_opengraph_twitter_tags(); // Feature may be disabled

wp_head(); // Required for title-tag and site icons
?>
</head>
<body <?php
  $bodyClass = 'qnr-winscroller';
  if (has_header_image()) $bodyClass .= ' has-header-image';
  if (QNRWP::get_setting('feature-bootstrap') == 1 && apply_filters('qnrwp_bootstrap_class_html_and_body', false)) $bodyClass .= ' bootstrap431';
  body_class($bodyClass);
  ?> data-qnr-offset="-4">
<?php if (QNRWP_DEBUG) echo '<!-- Error reporting: ' . error_reporting() . ' -->' . PHP_EOL; // Show we're reporting errors only in debug mode ?>
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
