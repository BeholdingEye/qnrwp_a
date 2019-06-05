<?php

defined( 'ABSPATH' ) || exit;

/**
 * Custom Widget definition for Subheaders
 */
class QNRWP_Widget_Subheader extends WP_Widget {
  
  /**
   * Instantiate the parent object
   */
	public function __construct() {
		$widget_ops = array( 
			'classname'   => 'qnrwp_widget_subheader',
			'description' => esc_html__('Enables the selection of a custom Subheader widget to display.', 'qnrwp'),
		);
		parent::__construct('qnrwp_widget_subheader', 'QNRWP Subheader', $widget_ops);
	}
  
  
  /**
   * Displays widget HTML output if current page in list of pages to display on (decided generically now)
   */
	public function widget($args, $instance) {
    $rHtml = '';
    if (get_post($instance['qnrwp_widget'])->post_type == 'qnrwp_subheader') {
      $rHtml = self::get_subheader_html($instance['qnrwp_widget']);
    }
    if ($rHtml) echo $args['before_widget'] . $rHtml . $args['after_widget']; // Done
    else echo '';
	}
  
  
  /**
   * Output widget admin options form, the custom widget selection menu
   */
	public function form($instance) {
    // Widget (defined by a page) to display field
		$widget = (!empty($instance['qnrwp_widget'])) ? $instance['qnrwp_widget'] : '';
    $fieldWidgetID = esc_attr($this->get_field_id('qnrwp_widget'));
    $fieldWidgetName = esc_attr($this->get_field_name('qnrwp_widget'));
    // HTML form
    echo self::get_widget_defs_menu($widget, $fieldWidgetID, $fieldWidgetName);
	}
  
  
  /**
   * Returns HTML select/options menu listing widgets
   * 
   * No output field required, the Widget updating works with "selected" <option> in <select>
   */
  public static function get_widget_defs_menu($existingVal, $outputID, $outputName) {
    $rP = '<option value="0">'.__('-- Select the widget --', 'qnrwp').'</option>'.PHP_EOL;
    $selected = '';
    $subheaders = get_posts(array('post_parent' => 0, 'post_type' => 'qnrwp_subheader', 'post_status' => 'publish,private'));
    foreach ($subheaders as $subheader) {
      if ($subheader->ID == $existingVal) $selected = 'selected="selected" ';
      $rP .= '<option '.$selected.'value="'.esc_attr($subheader->ID).'">'.esc_html($subheader->post_title).'</option>'.PHP_EOL;
      $selected = '';
    }
    if ($rP) {
      $rP = '<p>'.esc_html__('Select the widget to display', 'qnrwp').':</p><select name="'.$outputName.'" id="'.$outputID.'">'.PHP_EOL 
                      . $rP 
                      . '</select><br>'.PHP_EOL;
    } else $rP = '<p>'.esc_html__('No pages defining QNRWP Subheaders exist yet.', 'qnrwp').'</p>'.PHP_EOL;
    return $rP;
  }
  
  
  /**
   * Returns HTML for Subheader from defining page ID
   * 
   * @param   int   $widgetDefPageID    ID of page defining the subheader
   */
  public static function get_subheader_html($widgetDefPageID) {
    if (!$widgetDefPageID) return ''; // Exit if 0, the first menu item in widget form
    // Get subheader attributes from the defining page
    $subheaderAttributes = '';
    // No validation, we trust the settings text to be untampered
    $rawS = get_post_field('post_content', $widgetDefPageID);
    if (stripos($rawS, '{sub-header-options') !== false) { // TODO make it JSON compliant and use functions ??
      $rawS = preg_replace('@\s*//.*?(?:\n+|$)@i', ' ', $rawS); // Remove comments
      $rawS = preg_replace('@\s*\{sub-header-options\s*@i', '', $rawS); // Remove "{sub-header-options"
      $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
      $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
      $subheaderAttributes = ' '.$rawS; // Add a space to place attributes in tag
    }
    
    $rHtml = '';
    $wcContent = ''; // Widget child page content for display in subheader on matching named page
    $shOptionsL = array(); // Array of page name => image attachment ID
    $shOptionsL['*'] = ''; // Avoid an error later if key/value not set
    $shOptionsL['News'] = ''; // Likewise, prefer no-URL if News not present NO we use default image
    
    // Get Featured Image from definition page, as default for pages not defined by child pages
    if (has_post_thumbnail($widgetDefPageID)) {
      $shOptionsL['*'] = get_post_thumbnail_id($widgetDefPageID);
    }

    // Get child pages and their Featured Images / content
    //$widgetChildren = get_page_children($widgetDefPageID, get_pages(array('post_status' => 'private')));
    $widgetChildren = get_children(array('post_parent' => $widgetDefPageID, 'post_type' => 'qnrwp_subheader', 'post_status' => 'publish,private'));
    if (count($widgetChildren) > 0) {
      foreach ($widgetChildren as $widgetChild) {
        // Get any content from child page for its matching named page
        if (is_singular() && $widgetChild->post_title == get_queried_object()->post_title) {
          $wcContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
        }
        // Store name and img URL as key => value
        if (has_post_thumbnail($widgetChild)) {
          $shOptionsL[$widgetChild->post_title] = get_post_thumbnail_id($widgetChild);
        }
      }
    }
    
    if (!is_home() && is_front_page() && !$wcContent) { // Home TODO
      $wcContent = '<div id="sub-header-content" class="sub-header-content">'
                    .'<p class="qnr-font-resize" data-qnr-font-min="75" data-qnr-win-max-width="1024">'
                    .get_bloginfo('description').'</p></div>';
    }
    if (is_singular()) $headerTitleText = $wcContent ? $wcContent : get_queried_object()->post_title; // May be overriden below
    else $headerTitleText = wp_get_document_title();
    if ($GLOBALS['QNRWP_GLOBALS']['pageTemplate'] == '404.php') {
      $headerTitleText = '404 - ' . __('Page not found', 'qnrwp');
      $attID = $shOptionsL['*'];
    } else if (!is_singular() || get_queried_object()->post_type == 'post') { // Create News header, for all News pages, if no content defined
      $headerTitleText = $wcContent ? $wcContent : 'News';
      if ($shOptionsL['News']) $attID = $shOptionsL['News'];
      else $attID = $shOptionsL['*'];
    } else if (is_singular()) { // Page
      if (isset($shOptionsL[get_queried_object()->post_title]) && !empty($shOptionsL[get_queried_object()->post_title])) {
        $attID = $shOptionsL[get_queried_object()->post_title];
      } else {
        $attID = $shOptionsL['*'];
      }
    }
    
    // Get different sizes of image
    $attMeta = wp_get_attachment_metadata($attID);
    $mHtml = ''; // CSS media rules
    $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
    $uploaded_image_location = $upload_dir['baseurl'] . '/' . $attMeta['file']; // Note the 'baseurl', not 'basedir'
    if (is_ssl()) $uploaded_image_location = set_url_scheme($uploaded_image_location, 'https'); // Convert to HTTPS if used
    $image_subdir = substr($attMeta['file'], 0, strrpos($attMeta['file'], '/'));
    $largestImage = $uploaded_image_location; // Just in case, we fall back on full size file
    foreach ($attMeta['sizes'] as $size => $sizeArray) {
      // Create file path for intermediate size image
      $imgPath = $upload_dir['baseurl'] . '/' . $image_subdir . '/' . $sizeArray['file'];
      if (is_ssl()) $imgPath = set_url_scheme($imgPath, 'https');
      // Limit to min 600px width or height to avoid stretching low res on mobiles
      if ($sizeArray['width'] > 600 && $sizeArray['height'] > 600) {
        // Prepend presumably increasing sizes to media html
        $mItem = '@media (max-width: '.$sizeArray['width'].'px) {'.PHP_EOL;
        $mItem .= 'div#sub-header {background-image:url("'.$imgPath.'");}'.PHP_EOL;
        $mItem .= '}'.PHP_EOL;
        $mHtml = $mItem . $mHtml; // Concatenate in reverse
      }
      $largestImage = $imgPath; // Record last item as largest, the default in style block
    }
    
    // Prepare style block for responsive bg image size
    $sHtml = '<style>'.PHP_EOL;
    $sHtml .= 'div#sub-header {background-image:url("'.$largestImage.'");}'.PHP_EOL;
    $sHtml .= $mHtml;
    $sHtml .= '</style>'.PHP_EOL;
    
    $rHtml = $sHtml . '<div id="sub-header"'.$subheaderAttributes.'>'.PHP_EOL; // Further wrapped in "#sub-header-row" of sidebar template
    if ($wcContent) $rHtml .= $headerTitleText; // We already have all the code if $wcContent
    else $rHtml .= '<div id="sub-header-content" class="sub-header-content"><p class="sub-header-content-auto">'.$headerTitleText.'</p></div>'; // Similar to Home
    return $rHtml . '</div>' . PHP_EOL;
  }


} // End QNRWP_Widget_Subheader class
