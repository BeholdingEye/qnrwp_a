<?php

defined('ABSPATH') || exit;

/**
 * Admin options class (a singleton)
 */
final class QNRWP_Admin_Options {
    
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks
   */
  private function hooks() {
    // Theme submenu
    add_action('admin_menu', array($this, 'theme_options_submenu'));
    // Admin scripts
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_admin'));
    
    add_action('admin_init', array($this, 'admin_init'));
  }
  
  
  /**
   * Theme options submenu
   */
  public function theme_options_submenu() {
    add_submenu_page(
                      'options-general.php',                            // Parent menu slug
                      __('QNRWP Theme', 'qnrwp'),               // Page title
                      __('QNRWP Theme', 'qnrwp'),               // Submenu title
                      'manage_options',                                 // User capability
                      'qnrwp_theme_options_submenu',                    // Submenu slug
                      array($this, 'theme_options_submenu_page')        // Callback
                      );
  }

  
  /**
   * Output the options page
   */
  public function theme_options_submenu_page() {
    if (!current_user_can('manage_options')) wp_die(__('You do not have permission to access this page.', 'qnrwp'));
    else require_once QNRWP_DIR . 'inc/admin/settings.php';
  }
  
  
  /**
   * Enqueues admin stylesheets and scripts
   * 
   * @param   int   $hook   Current admin page slug
   */
  public function enqueue_scripts_admin($hook) {
    //wp_die($hook); // To find the screen slug of page we're on
    wp_enqueue_style('qnr-interface-stylesheet', get_template_directory_uri() . '/res/css/qnr-interface.css', null, null);
    wp_enqueue_style('qnrwp-admin-stylesheet', get_template_directory_uri() . '/res/css/qnrwp-admin-style.css', null, null);
    if ($hook == 'settings_page_qnrwp_theme_options_submenu') {
      wp_enqueue_script('jquery-ui-core');
      wp_enqueue_script('jquery-effects-core');
      wp_enqueue_script('jquery-ui-widget');
      //wp_enqueue_script('jquery-ui-accordion');
      wp_enqueue_script('jquery-ui-tabs');
    }
    wp_enqueue_script('qnrwp-admin-interface-js', get_template_directory_uri() . '/res/js/qnr-interface.js', null, null);
    wp_localize_script('qnrwp-admin-interface-js', 'QNRWP_JS_Global', qnrwp()->JS_Global);
    wp_enqueue_script('qnrwp-admin-widgets-js', get_template_directory_uri() . '/res/js/qnrwp-admin-widgets.js', null, null);
    wp_localize_script('qnrwp-admin-interface-js', 'QNRWP_JS_Global', qnrwp()->JS_Global);
    if(get_current_screen()->id == 'options-media') {
      wp_enqueue_script('qnrwp-admin-media-js', get_template_directory_uri() . '/res/js/qnrwp-admin-media.js', null, null);
      wp_localize_script('qnrwp-admin-media-js', 'QNRWP_JS_Global', qnrwp()->JS_Global);
    }
  }
  
  
  /**
   * Register settings for this theme, all options in one value
   */
  public function admin_init() {
    // We don't use Settings API to create sections and fields, controlling
    //   the structure more simply with ready-made HTML in settings.php
    register_setting('qnrwp-group', 'qnrwp_settings_array', array($this, 'sanitize_settings_array'));
  }
  
  
  /**
   * Sanitize callback for register_setting in admin_init
   */
  public function sanitize_settings_array($input) {
    $cookieNotice = isset($input['cookie-notice']) ? $input['cookie-notice'] : 0;
    $cookieNoticeText = isset($input['cookie-notice-text']) ? $input['cookie-notice-text'] : '';
    $cookieNoticePostid = isset($input['cookie-notice-postid']) ? $input['cookie-notice-postid'] : 0;
    $cookieNoticeLinkText = isset($input['cookie-notice-linktext']) ? $input['cookie-notice-linktext'] : '';
    
    $faviconURL = isset($input['favicon-url']) ? $input['favicon-url'] : '';
    $appleiconURL = isset($input['appleicon-url']) ? $input['appleicon-url'] : '';
    
    $headerFixed = isset($input['header-fixed']) ? $input['header-fixed'] : 0;
    
    $codeCombine = isset($input['code-combine']) ? $input['code-combine'] : 0;
    
    $newsFeaturedImage = isset($input['news-featured-image']) ? $input['news-featured-image'] : 0;
    
    $adminSimplify = isset($input['admin-simplify']) ? $input['admin-simplify'] : 0;
    
    $widgetVisibility = isset($input['widget-visibility']) ? $input['widget-visibility'] : 0;
    
    // Return validated setting array
    $valArray = array();
    $valArray['cookie-notice'] = $cookieNotice;
    $valArray['cookie-notice-text'] = $cookieNoticeText;
    $valArray['cookie-notice-postid'] = $cookieNoticePostid;
    $valArray['cookie-notice-linktext'] = $cookieNoticeLinkText;
    
    $valArray['favicon-url'] = $faviconURL;
    $valArray['appleicon-url'] = $appleiconURL;
    
    $valArray['header-fixed'] = $headerFixed;
    
    $valArray['code-combine'] = $codeCombine;
    
    $valArray['news-featured-image'] = $newsFeaturedImage;
    
    $valArray['admin-simplify'] = $adminSimplify;
    
    $valArray['widget-visibility'] = $widgetVisibility;
    return $valArray;
  }

} // End QNRWP_Admin_Options

