<?php
/**
 * QNRWP-A functions.php
 */

defined('ABSPATH') || exit; // Exit if accessed directly

// ----------------------- Error / Exception handling

add_filter('wp_should_handle_php_error', function($bool, $error) {
  // This doesn't do much in WP 5.2, we roll our own below
  //if (isset($error['type']) && in_array($error['type'], array(E_ALL, E_DEPRECATED, E_NOTICE, E_WARNING))) return true;
  return $bool;
});

// Enable debug mode on author server and allow child theme to filter
if (preg_match('/^(www\.)?beholdingeye\.com$/', $_SERVER['SERVER_NAME'])) {
  define('QNRWP_DEBUG', apply_filters('qnrwp_debug_constant', true));
} else {
  define('QNRWP_DEBUG', apply_filters('qnrwp_debug_constant', false));
}

if (QNRWP_DEBUG) {

  error_reporting(E_ALL); // Report all PHP errors
  //error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
  ini_set('display_errors', 1); // Change default PHP config

  /**
   * Error-to-exception handler
   */
  function qnrwp_exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
      // This error code is not included in error_reporting
      return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
  }
  set_error_handler('qnrwp_exception_error_handler');
  
  /**
   * Uncaught exception handler
   */
  function qnrwp_exception_handler($exception) {
    wp_die('Uncaught exception occurred: ' . strval($exception) . "\n");
  }
  set_exception_handler('qnrwp_exception_handler');
  
}

try {
  
  // ----------------------- Theme functionality

  /**
   * Singleton trait to use by classes that should have one instance only
   */
  trait QNRWP_Singleton_Trait {
    /**
     * The single instance of the class
     */
    protected static $_instance = null;
    
    /**
     * Ensures only one instance of the class is loaded or can be loaded
     */
    public static function instance() {
      if (is_null(self::$_instance)) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }
  }


  // Include our main class
  if (!class_exists('QNRWP')) {
    require_once trailingslashit(__DIR__) . 'inc/classes/class-qnrwp.php';
  }
  
  
  /**
   * Returns main instance of QNRWP class
   */
  function qnrwp() {
    return QNRWP::instance();
  }

  $QNRWP = qnrwp();

} catch (Exception $e) {
  if (QNRWP_DEBUG) wp_die(strval($e));
}
