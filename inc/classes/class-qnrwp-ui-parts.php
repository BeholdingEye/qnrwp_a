<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP UI parts class
 */
class QNRWP_UI_Parts {
  
  /**
   * Renders the site logo
   */
  public static function site_logo() {
    $blockOpen = '<div id="header-site-logo" class="header-site-logo qnr-font-resize" data-qnr-font-min="70" data-qnr-win-max-width="1024">';
    $blockOpen = apply_filters('qnrwp_site_logo_block_open', $blockOpen);
    $blockClose = apply_filters('qnrwp_site_logo_block_close', '</div>');
    $logoLinkURL = apply_filters('qnrwp_site_logo_link_url', esc_attr(trim(get_bloginfo('url'))));
    $logoLinkHTML = apply_filters('qnrwp_site_logo_link_html', esc_html(get_bloginfo('name')));
    $linkedLogo = apply_filters('qnrwp_site_logo_linked_logo', $linkedLogo);
    echo $blockOpen .  '<a href="'. $logoLinkURL .'">' . $logoLinkHTML . '</a>' . $blockClose;
  }
  
  
  /**
   * Renders the header main navigation menu
   */
  public static function main_navigation_menu() {
    wp_nav_menu(array('theme_location' => 'header_nav_menu', 'container_class' => 'qnr-navmenu', 'fallback_cb' => false));
  }
  
  
  /**
   * Renders cookie notice as per theme options
   */
  public static function cookie_notice() {
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

} // End QNRWP_UI_Parts class
