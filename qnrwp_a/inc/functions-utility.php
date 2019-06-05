<?php
/**
 * Generic utility functions that do not depend on the QNRWP theme
 * 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Debugging printout function
 */
function qnrwp_debug_printout($valueToPrint, $append=false) { // Append must be false...
  // Print any variable to the debug-printout.txt file
  // File permissions/ownership must be correct, same as WP generally
  $debugPrintFile = trailingslashit(get_template_directory()) . 'debug-printout.txt';
  $dpfSize = filesize($debugPrintFile);
  $tv = print_r($valueToPrint, $return=true) . PHP_EOL;
  // Limit printout to max 5000 chars
  if (strlen($tv) > 50000) $tv = __('DEBUG PRINTOUT ERROR: Length limit exceeded.', 'qnrwp');
  // Limit printout text file size to 9K, useful for appending only
  if ($append && $dpfSize !== false && $dpfSize < 100000) {
    file_put_contents($debugPrintFile, $tv, FILE_APPEND);
  } else {
    unlink(trailingslashit(get_template_directory()) . 'debug-printout.txt'); // Needed
    file_put_contents($debugPrintFile, $tv);
  }
}


/**
 * Returns news categories (news, uncategorized) ids as a string, managing our global DISABLED
 */
//function qnrwp_get_news_categories_ids() {
  //if (isset($GLOBALS['QNRWP_GLOBALS']['newsCategories'])) return $GLOBALS['QNRWP_GLOBALS']['newsCategories'];
  //else {
    ////$cats = get_categories(array('slug' => array('news', 'uncategorized'))); // Doesn't get 'uncategorized'
    //$cats = get_categories();
    //$catsIDs = '1'; // Uncategorized cat ID is always 1
    //foreach ($cats as $cat) {
      //if ($cat->slug == 'news') {
        //$catsIDs .= ','.$cat->term_id;
        //break;
      //}
    //}
    //$GLOBALS['QNRWP_GLOBALS']['newsCategories'] = $catsIDs;
    //return $catsIDs;
  //}
//}


/**
 * Interprets and returns user boolean input, usually string
 */
function qnrwp_interpret_bool_input($bool) {
  if (strtolower($bool) === 'yes' || strtolower($bool) === 'y' 
                                  || strtolower($bool) === 'true' 
                                  || $bool === '1' 
                                  || $bool === 1 
                                  || $bool === true) return true;
  else return false;
}


/**
 * Returns true if client device is mobile, from browser UA
 * 
 * This is the PHP version of the JS original
 */
function qnrwp_deviceIsMobile() {
  // 
  $isMobile = preg_match('/iPhone|iPad|iPod|Android|Blackberry|Nokia|Opera mini|Windows mobile|Windows phone|iemobile/i', $_SERVER['HTTP_USER_AGENT']);
  return $isMobile;
}


/**
 * Returns minified CSS text
 */
function qnrwp_minify_css($text) {
  // Remove comments
  $text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $text);
  // Remove tabs, excessive spaces and newlines
  $text = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $text);
  $text = str_replace('{ ', '{', $text);
  $text = str_replace(' {', '{', $text);
  $text = str_replace(' }', '}', $text);
  $text = str_replace('} ', '}', $text);
  $text = str_replace('; ', ';', $text);
  $text = str_replace(': ', ':', $text);
  return $text;
}


/**
 * Returns array of filepaths in a directory tree
 * 
 * The directory should not have a trailing slash
 */
function qnrwp_get_directory_tree_filepaths_array($directory) {
  $filePathsL = array();
  $strFilePaths = qnrwp_get_directory_tree_filepaths_string($directory);
  if ($strFilePaths) {
    $filePathsL = explode("\n", $strFilePaths);
    if (empty($filePathsL[count($filePathsL)-1])) array_pop($filePathsL);
  }
  return $filePathsL;
}

/**
 * Returns file paths in a directory tree as string of lines, called from array-returning function
 * 
 * The directory should not have a trailing slash
 */
function qnrwp_get_directory_tree_filepaths_string($directory) {
  $filePaths = '';
  $handle = @opendir($directory); // Suppress any errors with @
  if ($handle) {
    while (true) {
      $file = readdir($handle);
      if ($file === false) break;
      if (in_array($file, array('.', '..'))) continue;
      if (is_dir($directory . '/' . $file)) {
        $filePaths .= qnrwp_get_directory_tree_filepaths_string($directory . '/' . $file);
      } else $filePaths .= $directory . '/' . $file . "\n";
    }
    closedir($handle);
  }
  return $filePaths;
}

