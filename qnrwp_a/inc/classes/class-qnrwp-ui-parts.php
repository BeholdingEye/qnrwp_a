<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP UI parts class
 */
class QNRWP_UI_Parts {
  
  /**
   * Renders post thumbnail in a sizer, to reduce flashing of content on page load
   */
  public static function render_post_thumbnail($postID) {
    // Get post thumbnail dimensions so we can style width/height of IMG element
    $ptID = get_post_thumbnail_id($postID);
    $attMeta = wp_get_attachment_metadata($ptID);
    $availSizes = array('qnrwp-extra', 'qnrwp-largest', 'qnrwp-larger', 'large', 'medium_large', 'medium');
    foreach ($availSizes as $size) {
      if (isset($attMeta['sizes'][$size])) {
        $ptW = $attMeta['sizes'][$size]['width'];
        $ptH = $attMeta['sizes'][$size]['height'];
        $ptHPercent = ($ptH/$ptW)*100;
        break;
      }
    }
    
    // We use a CSS trick: bottom padding making up the height of the sizer wrap; its percentage - read as being of width - serving as proportional height
    echo '<div class="post-thumbnail-sizer-wrap" style="display:block;position:relative;width:100%;margin:1em 0;padding-bottom:'. $ptHPercent .'%;">';
    echo '<div class="post-thumbnail-sizer" style="display:block;position:absolute;top:0;bottom:0;left:0;right:0;margin:0;padding:0;">';
    
    /**
     * Because the size that will be served is conditioned by viewport 
     * size rather than the actual display size of the image, we limit 
     * to the Large size of 1024px wide. We allow child theme override
     * if larger images must be supported. See commented-out version.
     */
    
    $largestPostThumb = apply_filters('qnrwp_largest_post_thumbnail', 'large');
    $postThumbSizes = apply_filters('qnrwp_post_thumbnail_sizes', array('sizes' => '(max-width:360px) 360px, (max-width:768px) 768px, (max-width:1024px) 1024px, 1024px'));
    
    the_post_thumbnail($largestPostThumb, $postThumbSizes);
    
    /**
     * This commented-out version is for inspiration only, it should not
     * be used.
     */
    //the_post_thumbnail('qnrwp-largest', array(
          //'sizes' => '(max-width:360px) 360px, (max-width:768px) 768px, (max-width:1024px) 1024px, (max-width:1600px) 1600px, (max-width:2000px) 2000px, 2000px'
          //));
    
    echo '</div></div>';
  }
  
  
  /**
   * Renders header image
   */
  public static function render_header_image() {
    if (has_header_image()) {
      ?>
      <div id="header-image-container" class="header-image-container flex-block flex-vertical-center-content flex-horizontal-center-content">
        <?php echo get_header_image_tag(); ?>
      </div>
      <?php 
    }
  }
  
  
  /**
   * Renders the site logo
   */
  public static function render_site_logo() {
    $class = '';
    if (QNRWP::get_setting('feature-mainnavmenu') !== 0) $class = 'qnr-navbar-logo';
    else $class = 'header-site-logo';
    $class = apply_filters('qnrwp_header_site_logo_class', $class);
    ?>
    <div class="<?php echo $class; ?>">
      <?php if (has_custom_logo()): the_custom_logo(); ?>
      <?php else: ?>
      <a href="<?php echo esc_attr(trim(get_bloginfo('url'))); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
      <?php endif; ?>
    </div>
    <?php 
  }
  
  
  /**
   * Renders the site logo UNUSED
   */
  public static function render_site_logoOLD() {
    ?>
    <div id="header-site-logo" class="header-site-logo qnr-font-resize" data-qnr-font-min="70" data-qnr-win-max-width="1024">
      <?php if (has_custom_logo()): the_custom_logo(); ?>
      <?php else: ?>
      <a href="<?php echo esc_attr(trim(get_bloginfo('url'))); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
      <?php endif; ?>
    </div>
    <?php 
  }
  
  
  /**
   * Renders the header main navigation menu
   */
  public static function render_main_navigation_menu() {
    // This will be edited with regex later
    $class = '';
    if (QNRWP::get_setting('feature-mainnavmenu') !== 0) $class = 'qnr-navbar-menu';
    $class = apply_filters('qnrwp_header_nav_menu_container_class', $class);
    wp_nav_menu(array('theme_location' => 'header_nav_menu', 'container_class' => $class, 'fallback_cb' => false));
  }
  
  
  /**
   * Renders hamburger for main nav menu
   */
  public static function render_main_navigation_menu_hamburger() {
    ?>
    <div class="qnr-navbar-menu-toggle-wrap">
      <span class="qnr-navbar-menu-toggle qnr-glyph qnr-glyph-menuicon qnr-style-interactive"></span>
    </div>
    <?php
  }
  
  
  /**
   * Renders the header main navigation menu UNUSED
   */
  public static function render_main_navigation_menuOLD() {
    wp_nav_menu(array('theme_location' => 'header_nav_menu', 'container_class' => 'qnr-navmenu', 'fallback_cb' => false));
  }
  
  
  /**
   * Renders cookie notice as per theme options
   */
  public static function render_cookie_notice() {
    if (!is_user_logged_in() && QNRWP::get_setting('cookie-notice') == 1):
      $cookieNoticeAddClass = '';
      if (QNRWP::get_setting('cookie-notice-placement') == 1) {
        $cookieNoticeAddClass .= ' qnrwp-cookie-notice-footer';
      }
      if (QNRWP::get_setting('cookie-notice-cssposition') == 1) {
        $cookieNoticeAddClass .= ' qnrwp-cookie-notice-fixed';
      }
    ?>
      <div class="qnrwp-cookie-notice<?php echo $cookieNoticeAddClass; ?>">
        <p><?php 
        // Notice
        if (QNRWP::get_setting('cookie-notice-text') !== null) {
          $cookieNotice = esc_html(get_option('qnrwp_settings_array')['cookie-notice-text']);
        } else $cookieNotice = esc_html__('By using this site you agree to our use of cookies.', 'qnrwp');
        
        // Link
        if (QNRWP::get_setting('cookie-notice-postid')) {
          if (QNRWP::get_setting('cookie-notice-linktext') !== null) {
            $cookiePostID = intval(QNRWP::get_setting('cookie-notice-postid'));
            if (get_post($cookiePostID)) {
              $cookieNotice .= ' <a href="'.get_permalink($cookiePostID).'">'.esc_html(QNRWP::get_setting('cookie-notice-linktext')).'</a>';
            }
          }
        }
        echo apply_filters('qnrwp_cookie_notice_html', $cookieNotice);
        ?></p>
        <span class="qnr-glyph qnr-glyph-xicon" onclick="QNRWP.CookieNotice.close_cookie_notice(this, event);"></span>
      </div>
    <?php endif;
  }
  
  
  /**
   * Returns content box classes, accounting for sidebars
   */
  public static function get_content_box_classes() {
    if (!isset($GLOBALS['QNRWP_GLOBALS']['layout'])) QNRWP::get_layout();
    $contentBoxClass = 'content-box';
    if ($GLOBALS['QNRWP_GLOBALS']['layout'] == 'three-cols') $contentBoxClass .= ' three-col-content';
    else if ($GLOBALS['QNRWP_GLOBALS']['layout'] == 'left-sidebar') $contentBoxClass .= ' two-col-content-right';
    else if ($GLOBALS['QNRWP_GLOBALS']['layout'] == 'right-sidebar') $contentBoxClass .= ' two-col-content-left';
    else if ($GLOBALS['QNRWP_GLOBALS']['layout'] == 'single') $contentBoxClass .= ' single-col-content';
    return $contentBoxClass;
  }
  
} // End QNRWP_UI_Parts class

