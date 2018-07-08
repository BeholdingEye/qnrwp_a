<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP UI parts class
 */
class QNRWP_UI_Parts {
  
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
    wp_nav_menu(array('theme_location' => 'header_nav_menu', 'container_class' => 'qnr-navmenu', 'fallback_cb' => false));
  }
  
  
  /**
   * Renders cookie notice as per theme options
   */
  public static function render_cookie_notice() {
    if (!is_user_logged_in() && isset(get_option('qnrwp_settings_array')['cookie-notice']) && get_option('qnrwp_settings_array')['cookie-notice'] == 1): ?>
      <div class="qnrwp-cookie-notice">
        <p><?php 
        // Notice
        if (isset(get_option('qnrwp_settings_array')['cookie-notice-text']) && !empty(get_option('qnrwp_settings_array')['cookie-notice-text'])) {
          $cookieNotice = esc_html(get_option('qnrwp_settings_array')['cookie-notice-text']);
        } else $cookieNotice = esc_html__('By using this site you agree to our use of cookies.', 'qnrwp');
        
        // Link
        if (isset(get_option('qnrwp_settings_array')['cookie-notice-postid']) && !empty(get_option('qnrwp_settings_array')['cookie-notice-postid'])) {
          if (isset(get_option('qnrwp_settings_array')['cookie-notice-linktext']) && !empty(get_option('qnrwp_settings_array')['cookie-notice-linktext'])) {
            $cookiePostID = get_option('qnrwp_settings_array')['cookie-notice-postid'];
            if (get_post($cookiePostID)) {
              $cookieLink = ' <a href="'.get_permalink($cookiePostID).'">'.esc_html(get_option('qnrwp_settings_array')['cookie-notice-linktext']).'</a>';
              $cookieNotice .= $cookieLink;
            }
            echo apply_filters('qnrwp_cookie_notice_html', $cookieNotice);
          }
        }
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

