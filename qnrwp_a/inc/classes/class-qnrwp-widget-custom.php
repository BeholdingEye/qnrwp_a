<?php

defined( 'ABSPATH' ) || exit;

/**
 * Custom Widget definition NOT USED, replaced with specific Subheader and Carousel widgets
 * 
 * The point of the custom widget is to create HTML for display on more 
 * than one page, otherwise shortcodes are a better option (for carousels)
 */
class QNRWP_Widget_Custom extends WP_Widget {
  
  /**
   * Instantiate the parent object
   */
	public function __construct() {
		$widget_ops = array( 
			'classname'   => 'qnrwp_widget_custom',
			'description' => esc_html__('Enables the selection of a custom widget to display, Subheader or Carousel.', 'qnrwp'),
		);
		parent::__construct('qnrwp_widget_custom', 'QNRWP Custom Widget', $widget_ops);
	}
  
  
  /**
   * Displays widget HTML output if current page in list of pages to display on (decided generically now)
   */
	public function widget($args, $instance) {
    $rHtml = '';
    // Carousel
    if (get_post($instance['qnrwp_widget'])->post_type == 'qnrwp_carousel') {
      $rHtml = self::get_carousel_html($instance['qnrwp_widget'], 'qnrwp-largest'); // Other sizes supported by shortcode
    }
    // Sub Header
    else if (get_post($instance['qnrwp_widget'])->post_type == 'qnrwp_subheader') {
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
    $carousels = get_posts(array('post_parent' => 0, 'post_type' => 'qnrwp_carousel', 'post_status' => 'publish,private'));
    foreach ($carousels as $carousel) {
      if ($carousel->ID == $existingVal) $selected = 'selected="selected" ';
      $rP .= '<option '.$selected.'value="'.esc_attr($carousel->ID).'">'.esc_html($carousel->post_title).'</option>'.PHP_EOL;
      $selected = '';
    }
    if ($rP) {
      $rP = '<p>'.esc_html__('Select the widget to display', 'qnrwp').':</p><select name="'.$outputName.'" id="'.$outputID.'">'.PHP_EOL 
                      . $rP 
                      . '</select><br>'.PHP_EOL;
    } else $rP = '<p>'.esc_html__('No pages defining QNRWP widgets exist yet.', 'qnrwp').'</p>'.PHP_EOL;
    return $rP;
  }
  
  
  /**
   * Returns HTML for Custom Widget subheader from defining page ID
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
        if ($widgetChild->post_title == get_queried_object()->post_title) {
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
    $headerTitleText = $wcContent ? $wcContent : get_queried_object()->post_title; // May be overriden below
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
    else $rHtml .= '<div id="sub-header-content" class="sub-header-content"><p class="">'.$headerTitleText.'</p></div>'; // Similar to Home
    return $rHtml . '</div>' . PHP_EOL;
  }
  
  
  /**
   * Returns HTML for Custom Widget carousel from defining page ID
   * 
   * @param   int     $widgetDefPageID  ID of page defining the carousel
   * @param   string  $imageSize        thumbnail, medium, medium_large, large, 
   *                                    full, qnrwp-larger, qnrwp-largest, qnrwp-extra
   */
  public static function get_carousel_html($widgetDefPageID, $imageSize = 'qnrwp-largest') {
    if (!$widgetDefPageID) return ''; // Exit if 0, the first menu item in widget form
    // Get carousel attributes from the defining page
    $carouselDataAttributes = '';
    // No validation, we trust the settings text to be untampered
    $rawS = get_post_field('post_content', $widgetDefPageID);
    if (stripos($rawS, '{carousel-options') !== false) {
      $rawS = preg_replace('@\s*//.*?\n+@i', ' ', $rawS); // Remove comments
      $rawS = preg_replace('@\s*\{carousel-options\s*@i', '', $rawS); // Remove "{carousel=options"
      $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
      $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
      $carouselDataAttributes = ' '.$rawS; // Add a space to place attributes in tag
    }
    // Construct carousel HTML
    $rHtml = '';
    $msHtml = ''; // Media rules style block
    $bsHtml = ''; // Style HTML for base sizes of slides
    
    // Test if size large enough to bother making it responsive (edited for all sizes) TODO
    $makeResponsive = false;
    //$sizesL = array('qnrwp-larger', 'qnrwp-largest', 'qnrwp-extra', 'full');
    $sizesL = apply_filters('qnrwp_carousel_defined_image_sizes', array('thumbnail', 'medium', 'medium_large', 'large', 'qnrwp-larger', 'qnrwp-largest', 'qnrwp-extra', 'full'));
    if (in_array($imageSize, $sizesL)) $makeResponsive = true;
      
    $attsSizesURLs = []; // List of attachment ID keys, values of size => URL array
    $slideIDs = []; // List of attachment IDs => timestamp DIV IDs for the slides
    
    //$widgetChildren = get_page_children($widgetDefPageID, get_pages(array('post_status' => 'private')));
    $widgetChildren = get_children(array('post_parent' => $widgetDefPageID, 'post_type' => 'qnrwp_carousel', 'post_status' => 'publish,private', 'order' => 'ASC'));
    if (count($widgetChildren) < 1) return ''; // Exit if no slides defined
    
    // Get child pages as content DIVs
    $iC = 0;
    foreach ($widgetChildren as $widgetChild) {
      if (!has_post_thumbnail($widgetChild)) return ''; // Exit if a post thumb not set on a slide
      
      // Get data for responsive styling
      $attID = get_post_thumbnail_id($widgetChild);
      $attsSizesURLs[$attID] = self::get_attachment_sizes_urls($attID); // ID => size => URL
      $slideIDs[$attID] = 'cSlide' . str_replace('.', '', strval(microtime(true)));
      
      // Construct item DIV for carousel
      $thumbBG = '';
      // Hide all but the first two (JS will show them)
      // Hoping that will speed up the load: only first image loaded at first, then when displayed, the rest
      
      if ($iC < 1) $thumbBG = ' style="background-image:url(\''.$attsSizesURLs[$attID][$imageSize].'\')"';
      else $thumbBG = ' style="display:none;background-image:url(\''.$attsSizesURLs[$attID][$imageSize].'\')"';
      
      $bsHtml .= 'div#'.$slideIDs[$attID].' {background-image:url("'.$attsSizesURLs[$attID][$imageSize].'") !important;}'.PHP_EOL;
      
      $htmlContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
      // Wrap the content with centered inner DIV for easier styling
      $htmlContent = '<div class="'.apply_filters('qnrwp_carousel_slide_inner_classes', 'slide-inner center').'">'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      $rHtml .= '<div id="'.$slideIDs[$attID].'"'.$thumbBG.'>'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      $iC += 1;
    }
    // Get sizes as pixels from last attachment, assumed representative of all
    $sizesALL = [];
    $attMeta = wp_get_attachment_metadata($attID);
    foreach ($attMeta['sizes'] as $size => $sizeArray) {
      $sizesALL[$size] = $sizeArray['width'];
    }
    // Get pixel width of imageSize
    $imageWidth = $sizesALL[$imageSize];
    
    if ($makeResponsive) {
      // Create media blocks for large to imageSize (edited for all sizes) TODO
      foreach ($sizesALL as $size => $width) {
        //if ($width < $imageWidth && $width > 1000) {
        if ($width < $imageWidth && $width > 0) {
          // Create the media wrap
          $mItem = '@media (max-width: '.$width.'px) {'.PHP_EOL;
          // Iterate images for each size
          $msItems = '';
          foreach ($attsSizesURLs as $attID => $sizeArray) {
            $msItems .= 'div#'.$slideIDs[$attID].' {background-image:url("'.$sizeArray[$size].'") !important;}'.PHP_EOL;
          }
          $mItem .= $msItems;
          $mItem .= '}'.PHP_EOL;
          $msHtml = $mItem . $msHtml; // Prepend larger before smaller
        }
      }
    }
    // Prepend base sizes, complete style block
    $msHtml = '<style>'.PHP_EOL . $bsHtml . $msHtml . '</style>'.PHP_EOL;
      
    // The carousel widget may already have a class assigned in the defining options
    if (stripos($carouselDataAttributes, 'class="') !== false) {
      $carouselDataAttributes = str_replace('class="', 'class="qnr-carousel ', $carouselDataAttributes);
      $rHtml = '<div'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
    }
    else $rHtml = '<div class="qnr-carousel"'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
    return $msHtml . $rHtml; // Prepend style block
  }


  /**
   * Returns array of size => URL, for different sizes of image inc. full
   */
  public static function get_attachment_sizes_urls($attachmentID) {
    $attSizesURLs = [];
    $attMeta = wp_get_attachment_metadata($attachmentID);
    $upload_dir = wp_upload_dir(); // Full path upload dir array for present YYYY/MM, may not be that of image
    $uploaded_image_location = $upload_dir['baseurl'] . '/' . $attMeta['file']; // Note the 'baseurl', not 'basedir'
    if (is_ssl()) $uploaded_image_location = set_url_scheme($uploaded_image_location, 'https'); // Convert to HTTPS if used
    $image_subdir = substr($attMeta['file'], 0, strrpos($attMeta['file'], '/'));
    foreach ($attMeta['sizes'] as $size => $sizeArray) {
      // Create URL for intermediate size image
      $imgURL = $upload_dir['baseurl'] . '/' . $image_subdir . '/' . $sizeArray['file'];
      if (is_ssl()) $imgURL = set_url_scheme($imgURL, 'https');
      $attSizesURLs[$size] = $imgURL;
    }
    $attSizesURLs['full'] = $uploaded_image_location; // Add original upload as 'full' size
    return $attSizesURLs;
  }


} // End QNRWP_Widget_Custom class
