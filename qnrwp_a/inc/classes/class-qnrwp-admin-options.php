<?php

defined('ABSPATH') || exit;

/**
 * Admin options class (a singleton)
 */
class QNRWP_Admin_Options {
    
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
    // Theme menu / submenus
    add_action('admin_menu', array($this, 'theme_menu'));
    add_action('admin_menu', array($this, 'theme_submenus'));
    
    // Admin scripts
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_admin'));
    
    add_action('admin_init', array($this, 'admin_init'));
  }
  
  
  /**
   * Theme menu
   */
  public function theme_menu() {
    add_menu_page(
                      __('QNRWP Theme', 'qnrwp'),                       // Page title
                      __('QNRWP Theme', 'qnrwp'),                       // Menu title
                      'manage_options',                                 // User capability
                      'qnrwp_theme_menu',                               // Menu slug (same as submenu...)
                      array($this, 'theme_menu_tools_page')             // Callback (same as submenu...)
                      );
  }
  
  
  /**
   * Theme submenus
   */
  public function theme_submenus() {
    
    // ----------------------- QNRWP submenus
    add_submenu_page(
                      'qnrwp_theme_menu',                               // Parent menu slug
                      __('Tools', 'qnrwp'),                             // Page title
                      __('Tools', 'qnrwp'),                             // Submenu title
                      'manage_options',                                 // User capability
                      'qnrwp_theme_menu',                               // Submenu slug (same as menu...)
                      array($this, 'theme_menu_tools_page')             // Callback (same as menu...)
                      );
    add_submenu_page(
                      'qnrwp_theme_menu',                               // Parent menu slug
                      __('Documentation', 'qnrwp'),                     // Page title
                      __('Documentation', 'qnrwp'),                     // Submenu title
                      'manage_options',                                 // User capability
                      'qnrwp_theme_documentation_submenu',              // Submenu slug
                      array($this, 'theme_menu_documentation_page')     // Callback
                      );
    
    // ----------------------- Options / Settings submenu
    add_submenu_page(
                      'options-general.php',                            // Parent menu slug
                      __('QNRWP Theme Settings', 'qnrwp'),              // Page title
                      __('QNRWP Theme Settings', 'qnrwp'),              // Submenu title
                      'manage_options',                                 // User capability
                      'qnrwp_theme_options_submenu',                    // Submenu slug
                      array($this, 'theme_options_submenu_page')        // Callback
                      );
  }

  
  /**
   * Output the tools page
   */
  public function theme_menu_tools_page() {
    if (!current_user_can('manage_options')) wp_die(__('You do not have permission to access this page.', 'qnrwp'));
    else require_once QNRWP_DIR . 'inc/admin/tools.php';
  }

  
  /**
   * Output the documentation page
   */
  public function theme_menu_documentation_page() {
    if (!current_user_can('manage_options')) wp_die(__('You do not have permission to access this page.', 'qnrwp'));
    else require_once QNRWP_DIR . 'inc/admin/documentation.php';
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
    if (QNRWP::get_setting('admin-wider-editor') !== 0 && $hook == 'post.php') {
      wp_enqueue_style('qnrwp-admin-wider-editor-stylesheet', get_template_directory_uri() . '/res/css/qnrwp-admin-wider-block-editor.css', null, null);
    }
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
    $featureBootstrap = isset($input['feature-bootstrap']) ? $input['feature-bootstrap'] : 0;
    
    $featureContactForms = isset($input['feature-contactforms']) ? $input['feature-contactforms'] : 0;
    $featureCarousels = isset($input['feature-carousels']) ? $input['feature-carousels'] : 0;
    $featureSubheaders = isset($input['feature-subheaders']) ? $input['feature-subheaders'] : 0;
    $featureSampleCards = isset($input['feature-samplecards']) ? $input['feature-samplecards'] : 0;
    $featureMetaTags = isset($input['feature-metatags']) ? $input['feature-metatags'] : 0;
    $featureMainNavMenu = isset($input['feature-mainnavmenu']) ? $input['feature-mainnavmenu'] : 0;
    $featureMenuShortcode = isset($input['feature-menushortcode']) ? $input['feature-menushortcode'] : 0;
    
    $cookieNotice = isset($input['cookie-notice']) ? $input['cookie-notice'] : 0;
    $cookieNoticeText = isset($input['cookie-notice-text']) ? $input['cookie-notice-text'] : '';
    $cookieNoticePostid = isset($input['cookie-notice-postid']) ? intval($input['cookie-notice-postid']) : '';
    $cookieNoticeLinkText = isset($input['cookie-notice-linktext']) ? $input['cookie-notice-linktext'] : '';
    $cookieNoticePlacement = isset($input['cookie-notice-placement']) ? $input['cookie-notice-placement'] : '';
    $cookieNoticeCssPosition = isset($input['cookie-notice-cssposition']) ? $input['cookie-notice-cssposition'] : '';
    
    $headerFixed = isset($input['header-fixed']) ? $input['header-fixed'] : 0;
    $maxPageWidth = isset($input['max-page-width']) ? min(2500,max(1200, intval($input['max-page-width']))) : 1600;
    
    $codeCombine = isset($input['code-combine']) ? $input['code-combine'] : 0;
    
    $newsFeaturedImage = isset($input['news-featured-image']) ? $input['news-featured-image'] : 0;
    $newsWPEmojisDisabled = isset($input['news-wpemojisdisabled']) ? $input['news-wpemojisdisabled'] : 0;
    
    $adminSimplify = isset($input['admin-simplify']) ? $input['admin-simplify'] : 0;
    $adminWiderEditor = isset($input['admin-wider-editor']) ? $input['admin-wider-editor'] : 0;
    
    $widgetVisibility = isset($input['widget-visibility']) ? $input['widget-visibility'] : 0;
    
    // Return validated setting array
    $valArray = array();
    $valArray['feature-bootstrap'] = $featureBootstrap;
    
    $valArray['feature-contactforms'] = $featureContactForms;
    $valArray['feature-carousels'] = $featureCarousels;
    $valArray['feature-subheaders'] = $featureSubheaders;
    $valArray['feature-samplecards'] = $featureSampleCards;
    $valArray['feature-metatags'] = $featureMetaTags;
    $valArray['feature-mainnavmenu'] = $featureMainNavMenu;
    $valArray['feature-menushortcode'] = $featureMenuShortcode;
    
    $valArray['cookie-notice'] = $cookieNotice;
    $valArray['cookie-notice-text'] = $cookieNoticeText;
    $valArray['cookie-notice-postid'] = $cookieNoticePostid;
    $valArray['cookie-notice-linktext'] = $cookieNoticeLinkText;
    $valArray['cookie-notice-placement'] = $cookieNoticePlacement;
    $valArray['cookie-notice-cssposition'] = $cookieNoticeCssPosition;
    
    $valArray['header-fixed'] = $headerFixed;
    $valArray['max-page-width'] = $maxPageWidth;
    
    $valArray['code-combine'] = $codeCombine;
    
    $valArray['news-featured-image'] = $newsFeaturedImage;
    $valArray['news-wpemojisdisabled'] = $newsWPEmojisDisabled;
    
    $valArray['admin-simplify'] = $adminSimplify;
    $valArray['admin-wider-editor'] = $adminWiderEditor;
    
    $valArray['widget-visibility'] = $widgetVisibility;
    return $valArray;
  }

} // End QNRWP_Admin_Options

