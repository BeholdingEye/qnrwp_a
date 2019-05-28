<?php

defined( 'ABSPATH' ) || exit;

/**
 * Our main class (a singleton)
 */
final class QNRWP {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * QNRWP_Admin_Options instance
   */
  public $admin = null;
  
  
  /**
   * QNRWP_Widgets instance
   */
  public $widgets = null;
  
  
  /**
   * QNRWP_News instance
   */
  public $news = null;
  
  
  /**
   * QNRWP_Shortcodes instance
   */
  public $shortcodes = null;
  
  
  /**
   * QNRWP_Imaging instance
   */
  public $imaging = null;
  
  
  /**
   * QNRWP_Metabox_User_Class instance
   */
  public $metabox_user_class = null;
  
  
  /**
   * QNRWP_Samples instance
   */
  public $samples = null;
  
  
  /**
   * QNRWP_Subheader instance
   */
  public $subheader = null;
  
  
  /**
   * QNRWP_Carousel instance
   */
  public $carousel = null;
  
  
  /**
   * QNRWP_Customizer instance NOT USED
   */
  //public $customizer = null;
  
  
  /**
   * List JS files, in load order
   */
  public $jsFilesL = array( 
                            'qnrwp-startup-js'        => '/res/js/qnrwp-startup.js',
                            'qnrwp-interface-js'      => '/res/js/qnr-interface.js',
                            'qnrwp-navmenu-hmenu-js'  => '/res/js/qnr-navmenu-hmenu.js',
                            'qnrwp-contact-js'        => '/res/js/qnrwp-contact.js',
                            'qnrwp-ajax-js'           => '/res/js/qnrwp-ajax.js',
                            'qnrwp-samples-js'        => '/res/js/qnrwp-samples.js',
                            'qnrwp-cookie-notice-js'  => '/res/js/qnrwp-cookie-notice.js',
                            'qnrwp-main-js'           => '/res/js/qnrwp-main.js',
                            );
  
  
  /**
   * List CSS files, in load order
   */
  public $cssFilesL = array(
                            'qnrwp-interface-css'     => '/res/css/qnr-interface.css',
                            'qnrwp-navmenu-hmenu-css' => '/res/css/qnr-navmenu-hmenu.css',
                            'qnrwp-contact-css'       => '/res/css/contact.css',
                            'qnrwp-style-css'         => '/style.css',
                            );
  
  
  /**
   * List translated strings for JS, set by the constructor
   */
  public $JS_i18n = null;
  
  
  /**
   * List $JS_i18n and other data in global for JS, set by the constructor
   */
  public $JS_Global = null;
  
  
  /**
   * Record if Javascript files to enqueue are changed
   */
  public $JavascriptChanged = false;
  
  
  /**
   * Record if CSS files to enqueue are changed
   */
  public $CSSChanged = false;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->constants();
    $this->set_JS_i18n();
    $this->set_JS_Global();
    $this->global_settings();
    $this->edit_vars();
    $this->includes();
    $this->hooks();
  }
  
  
  /**
   * Edits variables according to what features are enabled
   */
  private function edit_vars() {
    if (self::get_setting('feature-menushortcode') !== 1) {
      unset($this->jsFilesL['qnrwp-navmenu-hmenu-js']);
      unset($this->cssFilesL['qnrwp-navmenu-hmenu-css']);
    }
    if (self::get_setting('feature-contactforms') === 0) {
      unset($this->jsFilesL['qnrwp-contact-js']);
      unset($this->cssFilesL['qnrwp-contact-css']);
    }
    if (self::get_setting('feature-samplecards') === 0) {
      unset($this->jsFilesL['qnrwp-samples-js']);
    }
    if (self::get_setting('cookie-notice') !== 1) {
      unset($this->jsFilesL['qnrwp-cookie-notice-js']);
    }
    if ($this->jsFilesL != self::get_setting('jsFilesL')) {
      $this->JavascriptChanged = true;
      $GLOBALS['QNRWP_GLOBALS']['settingsArray']['jsFilesL'] = $this->jsFilesL;
      update_option('qnrwp_settings_array', $GLOBALS['QNRWP_GLOBALS']['settingsArray']);
    }
    if ($this->cssFilesL != self::get_setting('cssFilesL')) {
      $this->CSSChanged = true;
      $GLOBALS['QNRWP_GLOBALS']['settingsArray']['cssFilesL'] = $this->cssFilesL;
      update_option('qnrwp_settings_array', $GLOBALS['QNRWP_GLOBALS']['settingsArray']);
    }
  }
  
  
  /**
   * Sets multidimensional array of translated strings for JS, an array for each JS script
   */
  private function set_JS_i18n() {
    $this->JS_i18n = array(
      'ajax' => array(
                      'error1' => esc_html__('Invalid data', 'qnrwp'),
                      ),
                      
      'interface' => array(
                      'error1' => esc_html__('Carousel requires at least 2 DIV or IMG items.', 'qnrwp'),
                      'error2' => esc_html__('At least one DIV is required for slider widget.', 'qnrwp'),
                      'error3' => esc_html__('At least two IMG tags required for image animator widget.', 'qnrwp'),
                      'error4' => esc_html__('Invalid HTML.', 'qnrwp'),
                      ),
                      
      'contact' => array(
                      'note1'  => esc_html__('Max 500 characters', 'qnrwp'),
                      
                      'alert1' => esc_html__('Your message cannot be submitted', 'qnrwp'),
                      'alert2' => esc_html__('Please correct and try again.', 'qnrwp'),
                      'alert3' => esc_html__('Page session has expired. Reload the page to restart the session.', 'qnrwp'),
                      'alert4' => esc_html__('Not a valid email address.', 'qnrwp'),
                      'alert5' => esc_html__('Not a valid message.', 'qnrwp'),
                      
                      'confirm1' => esc_html__('Please check that %s is the correct email address. Click OK to send or Cancel to abort.', 'qnrwp'),
                      ),
                      
      'admin' => array(
                      'media' => array(
                                        'confirm1' => esc_html__('Are you sure you want to Regenerate Images?', 'qnrwp'),
                                        ),
                      ),
      );
  }
  
  
  /**
   * Combines array of translated strings for JS with Ajax URL for global object for JS
   */
  private function set_JS_Global() {
    $this->JS_Global = array(
                            'i18n' => $this->JS_i18n,
                            
                            'Ajax' => array(
                                            'url' => admin_url('admin-ajax.php'),
                                            ),
                            );
  }
  
  
  /**
   * Define theme constants
   */
  private function constants() {
    $this->define('QNRWP_DIR', trailingslashit(get_template_directory()));
  }
  
  
  /**
   * Define constant if not already set
   */
  private function define($name, $value) {
    if (!defined($name)) {
      define($name, $value);
    }
  }
  
  
  /**
   * Sets our global of theme settings, and $content_width, and starting our global var
   */
  private function global_settings() {
    $GLOBALS['QNRWP_GLOBALS']['pageTemplate'] = 'unknown'; // Avoid it being undefined TODO
    $themeSettings = get_option('qnrwp_settings_array'); // TODO theme mods
    if ($themeSettings) $GLOBALS['QNRWP_GLOBALS']['settingsArray'] = $themeSettings; 
    // Set the content width to the max width specified by theme settings
    global $content_width;
    if (!isset($content_width)) {
      try {
        $content_width = min(2500,max(1200,intval(self::get_setting('max-page-width', 1600))));
      } catch (Exception $e) {
        $content_width = 1600;
      }
    }
  }
  
  
  /**
   * Returns a theme setting from our global, called statically, handling empty() with care
   */
  public static function get_setting($setting, $default=null) {
    if (isset($GLOBALS['QNRWP_GLOBALS']['settingsArray'][$setting])) {
      $sv = $GLOBALS['QNRWP_GLOBALS']['settingsArray'][$setting];
      if ((is_string($sv) && $sv !== '')
            || (is_array($sv) && !empty($sv))
            || is_bool($sv)
            || is_int($sv)
            || is_float($sv)) {
        if ($sv === '0') $sv = 0;
        else if ($sv === '1') $sv = 1;
        return $sv;
      }
    }
    return $default;
  }
  
  /**
   * Includes code files, instantiates classes
   */
  public function includes() {
    // Functions
    require_once QNRWP_DIR . 'inc/functions-utility.php';
    
    if (is_admin()) { // Also when calling admin-ajax.php?
      require_once QNRWP_DIR . 'inc/functions-admin.php';
    
      // Admin
      if (!class_exists('QNRWP_Admin_Options')) {
        require_once QNRWP_DIR . 'inc/classes/class-qnrwp-admin-options.php';
        $this->admin = QNRWP_Admin_Options::instance();
      }
    }
    
    // Meta tags
    if (self::get_setting('feature-metatags') !== 0 && !class_exists('QNRWP_Meta_Tags')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-meta-tags.php';
    }
    
    // UI parts
    if (!class_exists('QNRWP_UI_Parts')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-ui-parts.php';
    }
      
    // Widgets
    if (!class_exists('QNRWP_Widgets')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widgets.php';
      $this->widgets = QNRWP_Widgets::instance();
    }
    
    // News
    if (!class_exists('QNRWP_News')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-news.php';
      $this->news = QNRWP_News::instance();
    }
    
    // Shortcodes
    if (!class_exists('QNRWP_Shortcodes')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-shortcodes.php';
      $this->shortcodes = QNRWP_Shortcodes::instance();
    }
    
    // Imaging
    if (!class_exists('QNRWP_Imaging')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-imaging.php';
      $this->imaging = QNRWP_Imaging::instance();
    }
    
    // Metaboxes
    //if (!class_exists('QNRWP_Metabox_User_Class')) {
      //require_once QNRWP_DIR . 'inc/classes/class-qnrwp-metabox-user-class.php';
      //$this->metabox_user_class = QNRWP_Metabox_User_Class::instance();
    //}
    
    // Samples CPT
    if (self::get_setting('feature-samplecards') !== 0 && !class_exists('QNRWP_Samples')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-samples.php';
      $this->samples = QNRWP_Samples::instance();
    }
    
    // Subheader CPT
    if (self::get_setting('feature-subheaders') !== 0 && !class_exists('QNRWP_Subheader')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-subheader.php';
      $this->subheader = QNRWP_Subheader::instance();
    }
    
    // Carousel CPT
    if (self::get_setting('feature-carousels') !== 0 && !class_exists('QNRWP_Carousel')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-carousel.php';
      $this->carousel = QNRWP_Carousel::instance();
    }
    
    // Customizer NOT USED
    //if (!class_exists('QNRWP_Customizer')) {
      //require_once QNRWP_DIR . 'inc/classes/class-qnrwp-customizer.php';
      //$this->customizer = QNRWP_Customizer::instance();
    //}
    
  }
  
  
  /**
   * Filter and action hooks
   */
  private function hooks() {
    add_action('after_setup_theme', array($this, 'setup'));
    
    // Ajax
    add_action('wp_ajax_qnrwp_ajax_handler', array($this, 'ajax_handler'));
    add_action('wp_ajax_nopriv_qnrwp_ajax_handler', array($this, 'ajax_handler'));
    
    // Enqueues
    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts')); // Also sets up cookies/Ajax security
    // Admin enqueue done in admin options class
    
    // Menus
    if (self::get_setting('feature-mainnavmenu') !== 0) {
      add_filter('nav_menu_css_class', array($this, 'menu_classes'), 10, 4);
      //apply_filters( 'nav_menu_submenu_css_class', string[] $classes, stdClass $args, int $depth );
      add_filter('nav_menu_submenu_css_class', array($this, 'sub_menu_classes'), 10, 3);
      //apply_filters( 'wp_nav_menu_objects', array $sorted_menu_items, stdClass $args )
      add_filter('wp_nav_menu_objects', array($this, 'menu_objects'), 10, 2);
      //apply_filters( 'wp_nav_menu', string $nav_menu, stdClass $args )
      add_filter('wp_nav_menu', array($this, 'wp_nav_menu'), 10, 2);
    }
    
    // Rest API
    add_filter('rest_authentication_errors', array($this, 'rest_api_authentication'));
    
    // Sidebars & widgets
    add_action('widgets_init', array($this, 'widgets_init'));
    
    // Disable WP emojis
    if (self::get_setting('news-wpemojisdisabled') !== 0) { // Disable emojis if setting returned is 1 or 'null', not 0
      add_action('init', array($this, 'disable_wp_emojis'));
    }
    
  }
  
  
  /**
   * Filters nav menu HTML
   */
  public function wp_nav_menu($nav_menu, $args) {
    //if ($args->menu->slug == 'qnrwp-main-nav-menu' || $args->container_class == 'qnr-navbar-menu' || $args->theme_location == 'header_nav_menu') {
    if ($args->theme_location == 'header_nav_menu') {
      // After previous filters have added some QNR Navbar classes, we use regex to complete them, done easier than a custom menu walker
      $nav_menu = preg_replace('/^<div [^>]*?>(<ul [^>]*?class=")/', '$1qnr-navbar-menu ', $nav_menu);
      $nav_menu = preg_replace('/<\/div>$/', '', $nav_menu);
      $nav_menu = preg_replace('/(<li [^>]*?class=")/', '$1qnr-navbar-menu-item ', $nav_menu);
      $nav_menu = preg_replace('/(<a )/', '$1class="qnr-style-interactive" ', $nav_menu);
      $nav_menu = preg_replace('/(<li .*? class="[^"]*?qnr-navbar-submenu-holder[^"]*"[^>]*>[^<]*<a class=")/', '$1qnr-navbar-submenu-toggle ', $nav_menu);
    }
    return $nav_menu;
  }
  
  
  /**
   * Filters menu objects
   */
  public function menu_objects($sorted_menu_items, $args) {
    //if ($args->menu->slug == 'qnrwp-main-nav-menu') {
    if ($args->theme_location == 'header_nav_menu') {
      //qnrwp_debug_printout($args, $append=false);
      //qnrwp_debug_printout($sorted_menu_items, $append=true);
    }
    return $sorted_menu_items;
  }
  
  
  /**
   * Filters nav menu submenu UL
   */
  public function sub_menu_classes($classes, $args, $depth) {
    //qnrwp_debug_printout($classes, $append=false);
    //qnrwp_debug_printout($args, $append=true);
    $classes[] = 'qnr-navbar-submenu';
    return $classes;
  }
  
  
  /**
   * Makes submenu of header main nav menu a QNR Navbar submenu
   * 
   * Note that args and its menu are objects in this hook
   */
  public function menu_classes($classes, $item, $args, $depth) {
    //qnrwp_debug_printout($classes, $append=true);
    $menuLocations = get_nav_menu_locations();
    if ($menuLocations) {
      if ($args->menu == wp_get_nav_menu_object($menuLocations['header_nav_menu']) && 
                $depth == 0 && in_array('menu-item-has-children', $classes)) {
        $classes[] = 'qnr-navbar-submenu-holder';
      }
    }
    return $classes;
  }
  
  
  /**
   * Makes submenu of header main nav menu a QNR Hmenu NOT USED
   * 
   * Note that args and its menu are objects in this hook
   */
  public function menu_classesOLD($classes, $item, $args, $depth) {
    $menuLocations = get_nav_menu_locations();
    if ($menuLocations) {
      if ($args->menu == wp_get_nav_menu_object($menuLocations['header_nav_menu']) && 
                $depth == 0 && in_array('menu-item-has-children', $classes)) {
        $classes[] = 'qnr-hmenu';
      }
    }
    return $classes;
  }
  
  
  /**
   * Sets up the theme
   * 
   * Methods called from hooks must be public
   */
  public function setup() {
    // Bail if no UA set, we need it for Ajax protection
    if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
      wp_die('ERROR: '.__('No user agent detected.', 'qnrwp'));
    }
    
    // Load theme text domain. First-loaded translation file overrides any following
    //   ones if the same translation is present
    // wp-content/themes/child-theme/languages/it_IT.mo
    load_theme_textdomain('qnrwp', get_stylesheet_directory() . '/languages');
    // wp-content/themes/parent-theme/languages/it_IT.mo
    load_theme_textdomain('qnrwp', get_template_directory() . '/languages');
    
    add_theme_support('post-thumbnails');
    
    add_theme_support('title-tag');
    
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));
    
    add_theme_support('custom-background', array(
      'default-color' => '#f6f6f6',
    ));
    
    global $content_width;
    add_theme_support('custom-header', array(
      'width'         => $content_width,
      'height'        => intval(0.1*$content_width),
      'header-text'   => false,
      'flex-width'    => true,
      'flex-height'   => true,
    ));
    
    add_theme_support('custom-logo');
    
    // Theme menu (locations) support is automatically declared by this function
    register_nav_menu('header_nav_menu', __('Header main navigation menu', 'qnrwp'));
    
    $this->woocommerce_theme_support();
  }
  
  /**
   * Declares WooCommerce support
   */
  public function woocommerce_theme_support() {
    add_theme_support('woocommerce', apply_filters('qnrwp_woocommerce_args', array(
      
      //'single_image_width'    => 416,
      //'thumbnail_image_width' => 324,

      // Product grid theme settings
      'product_grid'      => array(
        'default_rows'    => 4,
        'min_rows'        => 1,
        'max_rows'        => 8,
        
        'default_columns' => 3,
        'min_columns'     => 1,
        'max_columns'     => 6,
      ),
    )));
    
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
  }
  
  
  /**
   * Handles JS Ajax calls, securely, routing to different actions
   * 
   * $_POST will contain data passed in Ajax call, actiontype, cookie and datajson
   * 
   * We also check for the site session cookie that should be set on first page load
   */
  public function ajax_handler() {
    try {
      if (!isset($_POST['actiontype']) 
            || !isset($_POST['qnrwp_ajax_cookie'])
            || !isset($_COOKIE['qnrwp_site_cookie'])
            || !isset($_COOKIE['qnrwp_ajax_cookie'])
            || !isset($_POST['datajson'])) {
        echo rawurlencode('ERROR: '.__('Invalid Ajax call', 'qnrwp'));
        //return; // TODO ?? no...
      } else {
        // Security level is decided per device type below (not any more), but first we check the generics
        // This test may be considered redundant but we keep it
        if ($_POST['qnrwp_ajax_cookie'] !== $_COOKIE['qnrwp_ajax_cookie']) wp_die();
          
        // Decide action according to actiontype; a bunch of conversions is required...
        $actionType = stripslashes(rawurldecode($_POST['actiontype']));
        
        if ($actionType == 'email' && self::get_setting('feature-contactforms') !== 0) {
          
          // ----------------------- EMAIL
          $ajaxTrans = get_transient('qnrwp_ajax_temp_salt_'.$_POST['qnrwp_ajax_cookie']);
          // JS will have caught session expiry before we get here (with same message)
          if (!$ajaxTrans) {
            echo rawurlencode('ERROR: '.__('Page session has expired. Reload the page to restart the session.', 'qnrwp'));
            wp_die();
          }
          
          // ----------------------- Security check
          // Get time component of cookie value (used for session timing client-side)
          // If no UA, we would have exited on load in setup_theme
          $timeComp = 'T' . preg_split('/T/', $_POST['qnrwp_ajax_cookie'])[1];
          // NEW, the same for desktop and mobile
          if ($_POST['qnrwp_ajax_cookie'] !== md5(crypt($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'], $ajaxTrans)).$timeComp) {
            echo rawurlencode('ERROR: '.__('Your IP or user agent has changed. Reload the page to restart the session.', 'qnrwp'));
            wp_die();
          }
          // OLD:
          //if (qnrwp_deviceIsMobile()) {
            //if ($_POST['qnrwp_ajax_cookie'] !== md5(crypt($_SERVER['HTTP_USER_AGENT'], $ajaxTrans)).$timeComp) wp_die();
          //} else { // Desktop
            //if ($_POST['qnrwp_ajax_cookie'] !== md5(crypt($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'], $ajaxTrans)).$timeComp) wp_die();
          //}
          
          // Echo returns to Ajax caller; encode again to be sure
          // On error, returned string will begin with "ERROR:", else "Success:" (not for samples)
          if (!class_exists('QNRWP_Contact_Form')) {
            require_once QNRWP_DIR . 'inc/classes/class-qnrwp-contact-form.php';
          }
          echo rawurlencode(QNRWP_Contact_Form::send_email(stripslashes(rawurldecode($_POST['datajson']))));
          
        } else if ($actionType == 'samples') {
          
          // ----------------------- SAMPLES
          if (class_exists('QNRWP_Samples')) echo rawurlencode(QNRWP_Samples::ajax_more_samples(stripslashes(rawurldecode($_POST['datajson']))));
          
        } else {
          echo rawurlencode('ERROR: '.__('Invalid action', 'qnrwp').': '.$actionType);
          wp_die();
        }
      }
      
      // ----------------------- End
      wp_die(); // Must end like this
    }
    catch (Exception $e) {
      echo rawurlencode('ERROR: ' . $e->getMessage());
      wp_die();
    }
  }
  
  
  /**
   * Enqueues styles, first combining them into a minified file
   * 
   * Loads parent stylesheet before child's
   */
  public function enqueue_styles() {
    // Custom CSS overriding max-width, tying us in with custom-header image dimensions
    global $content_width;
    $customCSS = '.header-row, .content-row, .middle-row, .content-box, .footer-row {max-width:'.$content_width.'px;}'.PHP_EOL;
    
    $minify = isset(get_option('qnrwp_settings_array')['code-combine']) ? get_option('qnrwp_settings_array')['code-combine'] : 0;
    if ($minify) {
      $cfURI = $this->combine_minify_stylesheets();
      wp_enqueue_style('qnrwp-combo-stylesheet', $cfURI, null, null);
      wp_add_inline_style('qnrwp-combo-stylesheet', $customCSS);
    } else {
      $lastKey = '';
      foreach ($this->cssFilesL as $key => $cssFileURI) {
        wp_enqueue_style($key, get_template_directory_uri() . $cssFileURI, null, null);
        $lastKey = $key;
      }
      if (!is_child_theme()) wp_add_inline_style($lastKey, $customCSS); // If not minified and no child theme, we override last enqueued CSS
      if (is_child_theme()) {
        wp_enqueue_style('qnrwp-child-css', get_stylesheet_uri(), null, null); // Child theme style.css
        if (file_exists(get_stylesheet_directory() . '/child-style.css')) {
          wp_enqueue_style('qnrwp-child-child-css', get_stylesheet_directory_uri() . '/child-style.css', null, null); // Child theme actual, child-style.css
          wp_add_inline_style('qnrwp-child-child-css', $customCSS); // If not minified, we override child CSS
        } else {
          wp_add_inline_style('qnrwp-child-css', $customCSS);
        }
      }
    }
  }
  

  /**
   * Enqueues JS scripts, first combining them into a minified file
   */
  public function enqueue_scripts() {
    $minify = isset(get_option('qnrwp_settings_array')['code-combine']) ? get_option('qnrwp_settings_array')['code-combine'] : 0;
    if ($minify) {
      $cfURI = $this->combine_minify_js();
      wp_enqueue_script('qnrwp-combo-js', $cfURI, null, null, true); // In footer
      wp_localize_script('qnrwp-combo-js', 'QNRWP_JS_Global', $this->JS_Global);
    } else {
      $runs = 0;
      foreach ($this->jsFilesL as $key => $jsFileURI) {
        wp_enqueue_script($key, get_template_directory_uri() . $jsFileURI, null, null, true); // In footer
        if (!$runs) wp_localize_script($key, 'QNRWP_JS_Global', $this->JS_Global);
        $runs += 1;
      }
    }
    
    $this->cookie_ajax_security_setup(); // Must run after scripts are enqueued
  }
  
  
  /**
   * Combines theme/child-theme CSS stylesheet files, from class property list, into a minified combo file and returns its URI
   */
  public function combine_minify_stylesheets() {
    // Create array of stylesheet file paths, theme files first
    $tF = get_template_directory();
    foreach ($this->cssFilesL as $style) $stylesheetPathsL[] = $tF . $style;
    // Add child stylesheet file; child 'res/css' files are not included in combo, 
    //   enqueued conditionally in child functions.php
    if (is_child_theme()) $stylesheetPathsL[] = get_stylesheet_directory() . '/style.css';
    // If 'child-style.css' exists, load it (useful for clearer file identification in Dev Tools)
    if (file_exists(get_stylesheet_directory() . '/child-style.css')) $stylesheetPathsL[] = get_stylesheet_directory() . '/child-style.css';
    // Test for combo file, in child folder or main
    $fX = false; // File exists?
    $updateCombo = false; // Update combo file?
    if (is_child_theme()) {
      $cfPath = get_stylesheet_directory() . '/combo-style.css';
      $cfURI = get_stylesheet_directory_uri() . '/combo-style.css';
    }
    else {
      $cfPath = get_template_directory() . '/combo-style.css';
      $cfURI = get_template_directory_uri() . '/combo-style.css';
    }
    $fX = file_exists($cfPath);
    if ($fX) { // Combo file exists
      // Test if modification date of any stylesheets is newer than combo file
      $cT = filemtime($cfPath);
      foreach ($stylesheetPathsL as $stylesheet) {
        if (filemtime($stylesheet) > $cT) {
          // We have a stylesheet newer than combo file
          $updateCombo = true;
          break;
        }
      }
    }
    if (!$fX || $updateCombo || $this->CSSChanged) { // Create the combo file if it doesn't exist or must be updated
      
      $comboFull = '';
      foreach ($stylesheetPathsL as $stylesheet) {
        $combo = file_get_contents($stylesheet) . "\n";
        if (!preg_match('#\.min\.css$#', $stylesheet)) {
          // Assume we have no ".min.css" files in "/res/" that would need URIs edited
          // Also assume that "child-style.css" is not linking out of child theme with "../"
          // Replace relative URIs to other assets in main theme res folder (only...!)
          if (is_child_theme()) $combo = preg_replace('/(url\([\'"])\.\.\//', '$1../qnrwp_a/res/', $combo);
          else $combo = preg_replace('/(url\([\'"])\.\.\//', '$1res/', $combo);
          // Minify, using core function
          $combo = qnrwp_minify_css($combo);
        }
        $comboFull .= $combo;
      }
      
      file_put_contents($cfPath, $comboFull);
      
    }
    return $cfURI; // Used by enqueueing caller
  }



  /**
   * Combines and minifies theme Javascript files, from class property list, returning URI to combo file
   */
  public function combine_minify_js() {
    // Create array of full JS file paths
    $tF = get_template_directory();
    foreach ($this->jsFilesL as $jsF) $jsPathsL[] = $tF . $jsF;
    // Test for combo file, in main
    $fX = false; // File exists?
    $updateCombo = false; // Update combo file?
    $cfPath = get_template_directory() . '/combo-js.js';
    $cfURI = get_template_directory_uri() . '/combo-js.js';
    $fX = file_exists($cfPath);
    if ($fX) { // Combo file exists
      // Test if modification date of any JS files is newer than combo file
      $cT = filemtime($cfPath);
      foreach ($jsPathsL as $jsF) {
        if (filemtime($jsF) > $cT) {
          // We have a JS file newer than combo file
          $updateCombo = true;
          break;
        }
      }
    }
    if (!$fX || $updateCombo || $this->JavascriptChanged) { // Create the combo file if it doesn't exist or must be updated
      require_once QNRWP_DIR . 'inc/JsMin.php'; // Use JsMin from Ryan Grove to minify
      $comboFull = '';
      foreach ($jsPathsL as $jsF) {
        $script = file_get_contents($jsF);
        $script = preg_replace('/"use strict";/', '', $script);
        if (!preg_match('#\.min\.js$#', $jsF)) {
          $script = JsMin::minify($script);
        }
        $comboFull .= $script . "\n";
      }
      file_put_contents($cfPath, '"use strict";' . "\n" . $comboFull);
    }
    return $cfURI; // Used by enqueueing caller
  }


  /**
   * Cookie / Ajax security setup (not relevant to all action types)
   */
  public function cookie_ajax_security_setup() {
    try {
      // Create or use a site session cookie that will survive across page loads, unlike the Ajax cookie
      if (!isset($_COOKIE['qnrwp_site_cookie']) || !$_COOKIE['qnrwp_site_cookie']) {
        $siteCookieVal = md5(openssl_random_pseudo_bytes(30));
        // Set session cookie, HTTP-Only
        if ($_SERVER['HTTPS']) setCookie('qnrwp_site_cookie', $siteCookieVal, 0, '/', '', true, true);
        else setCookie('qnrwp_site_cookie', $siteCookieVal, 0, '/', '', false, true);
      }
      // Ajax cookie
      $ajaxSalt = bin2hex(openssl_random_pseudo_bytes(30)); // Create random salt
      // We use cookie value as token; derived from UA on mobiles, UA and IP on desktop, plus T and secs from epoch start for JS control
      $timeComp = 'T'.time();
      // NEW, the same for desktop and mobile
      $cookieAndTrans = md5(crypt($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'], $ajaxSalt)).$timeComp;
      // OLD:
      //if (qnrwp_deviceIsMobile()) $cookieAndTrans = md5(crypt($_SERVER['HTTP_USER_AGENT'], $ajaxSalt)).$timeComp;
      //else $cookieAndTrans = md5(crypt($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'], $ajaxSalt)).$timeComp; // Desktop
      if ($_SERVER['HTTPS']) setCookie('qnrwp_ajax_cookie', $cookieAndTrans, 0, '/', '', true, false); // Set session cookie, for JS Ajax caller to echo back to us
      else setCookie('qnrwp_ajax_cookie', $cookieAndTrans, 0, '/', '', false, false);
      set_transient('qnrwp_ajax_temp_salt_'.$cookieAndTrans, $ajaxSalt, 15 * MINUTE_IN_SECONDS); // Save salt for 15 mins (also used by JS)
    } catch (Exception $e) {
      wp_die($e->getMessage());
    }
  }
  
  
  /**
   * Require authenticated user for REST API
   */
  public function rest_api_authentication($result) {
    if (!empty($result)) {
      return $result;
    }
    if (!is_user_logged_in()) {
      return new WP_Error('rest_not_logged_in', __('You are not logged in.', 'qnrwp'), array('status' => 401));
    }
    return $result;
  }
  
  
  /**
   * Registers sidebars and widgets
   * 
   * NOTE: Unlike other widgets, the Text and Custom HTML widgets are wrapped in an inner DIV
   * classed "textwidget" and "textwidget custom-html-widget" respectively, in addition to 
   * the 'before-widget' DIV specifying ID and class as defined below.
   */
  public function widgets_init() {
    // ----------------------- Header Row
    register_sidebar(array(
      'name'          => __('Header Row', 'qnrwp'),
      'id'            => 'qnrwp-row-header',
      'description'   => __('This area will always render as the first row on the page, displaying the site logo and the menu assigned to the "Header main navigation menu" location. Any widgets placed here will appear above the logo and the menu. If a cookie notice is set to display, it will appear first, before everything else.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Sub Header Row (within possibly narrower content & sidebars row)
    register_sidebar(array(
      'name'          => __('Sub Header Row', 'qnrwp'),
      'id'            => 'qnrwp-subrow-header',
      'description'   => __('Widgets in this area will be shown as a row under the header and above the main content and any sidebars. If the content row is styled to be narrower than the header, this subheader will be narrower too. If none of its widgets are set to appear on a page, this area will not render.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Left Sidebar
    register_sidebar(array(
      'name'          => __('Left Sidebar', 'qnrwp'),
      'id'            => 'qnrwp-sidebar-left',
      'description'   => __('Widgets in this area will be shown as a sidebar to the left of the main content. If none of its widgets are set to appear on a page, this area will not render, and the layout will adjust.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Right Sidebar
    register_sidebar(array(
      'name'          => __('Right Sidebar', 'qnrwp'),
      'id'            => 'qnrwp-sidebar-right',
      'description'   => __('Widgets in this area will be shown as a sidebar to the right of the main content. If none of its widgets are set to appear on a page, this area will not render, and the layout will adjust.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Sub Content Row
    register_sidebar(array(
      'name'          => __('Sub Content Row', 'qnrwp'),
      'id'            => 'qnrwp-subrow-content',
      'description'   => __('Widgets in this area will be shown as a row after the main content and any sidebars and above the footer. If none of its widgets are set to appear on a page, this area will not render.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Footer Row Upper
    register_sidebar(array(
      'name'          => __('Footer Row Upper', 'qnrwp'),
      'id'            => 'qnrwp-row-footer-upper',
      'description'   => __('The Footer will always render as the last row on the page, but may be empty of none of these Footer areas will appear. Widgets in this area will be shown as the upper part of the footer, and will be arranged in rows. If none of its widgets are set to appear on a page, this area will not render.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Footer Row Middle
    register_sidebar(array(
      'name'          => __('Footer Row Middle', 'qnrwp'),
      'id'            => 'qnrwp-row-footer-middle',
      'description'   => __('Widgets in this area will be shown as the middle part of the footer, and will be arranged in columns. Up to 3 columns are supported. If more are needed, the widget(s) should create their own sub-columns. If none of its widgets are set to appear on a page, this area will not render.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    // ----------------------- Footer Row Lower
    register_sidebar(array(
      'name'          => __('Footer Row Lower', 'qnrwp'),
      'id'            => 'qnrwp-row-footer-lower',
      'description'   => __('Widgets in this area will be shown as the lower part of the footer, and will be arranged in rows. If none of its widgets are set to appear on a page, this area will not render.', 'qnrwp'),
      'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
      'after_widget'  => "\n</div>\n",
      //'before_widget' => "<!-- Widget -->\n",
      //'after_widget'  => '',
      'before_title'  => "<h2 class=\"widget-title\">",
      'after_title'   => "</h2>\n",
    ));
    //if (!class_exists('QNRWP_Widget_Custom')) {
      //require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widget-custom.php';
    //}
    if (self::get_setting('feature-subheaders') !== 0 && !class_exists('QNRWP_Widget_Subheader')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widget-subheader.php';
    }
    if (self::get_setting('feature-carousels') !== 0 && !class_exists('QNRWP_Widget_Carousel')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widget-carousel.php';
    }
    if (!class_exists('QNRWP_Widget_Featured_News')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widget-featured-news.php';
    }
    //register_widget('QNRWP_Widget_Custom');
    if (self::get_setting('feature-subheaders') !== 0) register_widget('QNRWP_Widget_Subheader');
    if (self::get_setting('feature-carousels') !== 0) register_widget('QNRWP_Widget_Carousel');
    register_widget('QNRWP_Widget_Featured_News');
  }
  
  
  /**
   * Disables WP emojis
   */
  public function disable_wp_emojis() {
    // Remove all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    //// Remove TinyMCE emojis
    //add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
    
    add_filter('emoji_svg_url', '__return_false');
  }

  
  /**
   * Records layout type in our global
   */
  public static function get_layout() {
    // Get layout type according to active sidebars and their widgets
    $layout = 'single';
    $leftSidebar = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-sidebar-left');
    $rightSidebar = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-sidebar-right');
    if ($leftSidebar && $rightSidebar) $layout = 'three-cols';
    else if ($leftSidebar) $layout = 'left-sidebar';
    else if ($rightSidebar) $layout = 'right-sidebar';
    $GLOBALS['QNRWP_GLOBALS']['layout'] = $layout;
  }
  
} // End QNRWP class


