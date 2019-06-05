<?php
// ----------------------- QNRWP CHILD THEME FUNCTIONS

/**
 * You do not have to enqueue child stylesheet files "style.css" and 
 * "child-style.css", they are enqueued by the main theme.
 * 
 * The theme combines theme & child stylesheets into one minified file, 
 * "combo-style.css", served from the child theme folder. This feature 
 * may be disabled in theme settings, perhaps for debugging.
 */



/**
 * Enable/disable email notification on fatal errors; should usually return true
 */
add_filter('wp_fatal_error_handler_enabled', function($bool) {return true;});


/**
 * Set QNRWP debugging constant, should usually return false
 */
add_filter('qnrwp_debug_constant', function($debug) {return false;});

