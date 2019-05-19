<?php
/**
 * QNRWP-A functions.php
 */

defined('ABSPATH') || exit; // Exit if accessed directly


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

