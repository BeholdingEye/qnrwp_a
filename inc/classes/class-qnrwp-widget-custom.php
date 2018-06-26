<?php

defined( 'ABSPATH' ) || exit;

/**
 * Custom Widget definition
 * 
 * The point of the custom widget is to create HTML for display on more 
 * than one page, otherwise shortcodes are a better option
 */
class QNRWP_Widget_Custom extends WP_Widget {
  
  /**
   * Instantiate the parent object
   */
	public function __construct() {
		$widget_ops = array( 
			'classname'   => 'qnrwp_widget_custom',
			'description' => esc_html__('Enables the selection of a custom widget to display. Custom widgets are defined in pages titled "QNRWP-Widget-WidgetType-XXXX", where "WidgetType" is either "Carousel" or "SubHeader", and the "XXXX" part is the unique identifier of the particular widget instance.', 'qnrwp'),
		);
		parent::__construct('qnrwp_widget_custom', 'QNRWP Custom Widget', $widget_ops);
	}
  
  
  /**
   * Displays widget HTML output if current page in list of pages to display on (decided generically now)
   */
	public function widget($args, $instance) {
    $rHtml = '';
    // Carousel
    if (stripos(get_post($instance['qnrwp_widget'])->post_title, 'Carousel') !== false) {
      $rHtml = self::get_carousel_html($instance['qnrwp_widget'], 'large'); // Other sizes supported by shortcode
    }
    // Sub Header
    else if (stripos(get_post($instance['qnrwp_widget'])->post_title, 'SubHeader') !== false) {
      $rHtml = self::get_subheader_html($instance['qnrwp_widget']);
    }
    echo $args['before_widget'] . $rHtml . $args['after_widget']; // Done
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
    //echo self::get_pages_form_for_widget($pagesVal, $pagesL, $pagesOutputID, $pagesOutputName); // TEST TODO
    echo self::get_widget_defs_menu($widget, $fieldWidgetID, $fieldWidgetName);
	}
  
  // ----------------------- OUR OWN METHODS:
  
  /**
   * Returns HTML select/options menu listing widgets defined as "QNRWP-Widget-" prefixed pages
   * 
   * No output field required, the Widget updating works with "selected" <option> in <select>
   */
  public static function get_widget_defs_menu($existingVal, $outputID, $outputName) {
    $pages = get_pages(array('post_status' => 'private'));
    $rP = '';
    $selected = '';
    foreach ($pages as $page) {
      // Omit pages that are children of other pages (widgets are defined as main pages)
      // wp_get_post_parent_id is supposed to return false when no parent, but returns 0
      if (! wp_get_post_parent_id($page->ID) && stripos($page->post_title, 'QNRWP-Widget-') !== false) {
        // Save the page ID instead of name, for faster retrieval later
        if ($page->ID == $existingVal) $selected = ' selected="selected"';
        $rP .= '<option '.$selected.' value="'.esc_attr($page->ID).'">'.esc_html($page->post_title).'</option>'.PHP_EOL;
        // Reset $selected for next item
        $selected = '';
      }
    }
    if ($rP) {
      $rP = '<p>'.esc_html__('Select the widget to display', 'qnrwp').':</p><select name="'.$outputName.'" id="'.$outputID.'">'.PHP_EOL . $rP . '</select><br>'.PHP_EOL;
    }
    else $rP = '<p>'.esc_html__('No pages defining QNRWP widgets exist yet.', 'qnrwp').'</p>'.PHP_EOL;
    return $rP;
  }
  
  
  /**
   * Returns HTML for Custom Widget subheader from defining page ID
   * 
   * @param   int   $widgetDefPageID    ID of page defining the subheader
   */
  public static function get_subheader_html($widgetDefPageID) {
    // Get subheader attributes from the defining page
    $subheaderAttributes = '';
    // No validation, we trust the settings text to be untampered
    $rawS = get_post_field('post_content', $widgetDefPageID);
    if (stripos($rawS, '{subheader-options') !== false) { // TODO make it JSON compliant and use functions ??
      $rawS = preg_replace('@\s*//.*?(?:\n+|$)@i', ' ', $rawS); // Remove comments
      $rawS = preg_replace('@\s*\{subheader-options\s*@i', '', $rawS); // Remove "{subheader=options"
      $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
      $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
      $subheaderAttributes = ' '.$rawS; // Add a space to place attributes in tag
    }
    
    $rHtml = '';
    $wcContent = ''; // Widget child page content for display in subheader on matching named page
    $shOptionsL = array(); // Array of page name => image attachment ID
    $shOptionsL['*'] = ''; // Avoid an error later if key/value not set
    $shOptionsL['News'] = ''; // Likewise, prefer no-URL if News not present
    
    // Get Featured Image from definition page, as default for pages not defined by child pages
    if (has_post_thumbnail($widgetDefPageID)) {
      $shOptionsL['*'] = get_post_thumbnail_id($widgetDefPageID);
    }

    // Get child pages and their Featured Images / content
    $widgetChildren = get_page_children($widgetDefPageID, get_pages(array('post_status' => 'private')));
    if (count($widgetChildren) > 0) {
      foreach ($widgetChildren as $widgetChild) {
        // Get any content from child page for its matching named page
        if ($widgetChild->post_title == $GLOBALS['QNRWP_GLOBALS']['pageTitle']) {
          $wcContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
        }
        // Store name and img URL as key => value
        if (has_post_thumbnail($widgetChild)) {
          $shOptionsL[$widgetChild->post_title] = get_post_thumbnail_id($widgetChild);
        }
      }
    }
    
    $headerTitleText = $wcContent ? $wcContent : $GLOBALS['QNRWP_GLOBALS']['pageTitle']; // May be overriden below
    
    if ($GLOBALS['QNRWP_GLOBALS']['isNews']) { // Create News header, for all News pages, if no content defined
      $headerTitleText = $wcContent ? $wcContent : 'News';
      $attID = $shOptionsL['News'];
    } else if ($GLOBALS['QNRWP_GLOBALS']['postsAmount'] == 'single') { // Create Page header
      if (isset($shOptionsL[$GLOBALS['QNRWP_GLOBALS']['pageTitle']]) && !empty($shOptionsL[$GLOBALS['QNRWP_GLOBALS']['pageTitle']])) {
        $attID = $shOptionsL[$GLOBALS['QNRWP_GLOBALS']['pageTitle']];
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
        $mItem .= 'div#subheader {background-image:url("'.$imgPath.'");}'.PHP_EOL;
        $mItem .= '}'.PHP_EOL;
        $mHtml = $mItem . $mHtml; // Concatenate in reverse
      }
      $largestImage = $imgPath; // Record last item as largest, the default in style block
    }
    
    // Prepare style block for responsive bg image size
    $sHtml = '<style>'.PHP_EOL;
    $sHtml .= 'div#subheader {background-image:url("'.$largestImage.'");}'.PHP_EOL;
    $sHtml .= $mHtml;
    $sHtml .= '</style>'.PHP_EOL;
    
    $rHtml = $sHtml . '<div id="subheader"'.$subheaderAttributes.'>'.PHP_EOL;
    if ($wcContent) $rHtml .= $headerTitleText; // We already have all the code if $wcContent
    else $rHtml .= '<div><p class="center-vertical">'.$headerTitleText.'</p></div>'; // TODO simplify?
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
    
    $widgetChildren = get_page_children($widgetDefPageID, get_pages(array('post_status' => 'private')));
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
