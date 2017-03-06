<?php
/**
 * QNRWP-A functions.php
 */

// ===================== GENERIC FUNCTIONS =====================

// ----------------------- Post Thumbnail URL getter

function qnrwp_get_post_thumbnail_url($thumbHtml) {
    // Get URL from IMG src attribute for CSS background styling
    // TODO get size
    $mm = preg_match('@src="(https?://[^\"]+)"@i', $thumbHtml, $matches);
    if ($mm) {
      return $matches[1];
    }
    else return '';
}


// ----------------------- Layout type getter

function qnrwp_get_layout() {
  // Get layout type according to active sidebars
  $layout = 'single';
  if (is_active_sidebar('qnrwp-sidebar-1') && is_active_sidebar('qnrwp-sidebar-2')) $layout = 'three-cols';
  else if (is_active_sidebar('qnrwp-sidebar-1')) $layout = 'left-sidebar';
  else if (is_active_sidebar('qnrwp-sidebar-2')) $layout = 'right-sidebar';
  return $layout;
}


// ----------------------- Custom Widget subheader HTML getter from defining page ID

function qnrwp_get_subheader_html($widgetDefPageID) {
  // $widgetDefPageID - ID of the page defining the subheader
  
  // Get subheader attributes from the defining page
  $subheaderAttributes = '';
  // No validation, we trust the settings text to be untampered
  $rawS = get_post_field('post_content', $widgetDefPageID);
  if (stripos($rawS, '{subheader-options') !== false) {
    $rawS = preg_replace('@\s*//.*?(?:\n+|$)@i', ' ', $rawS); // Remove comments
    $rawS = preg_replace('@\s*\{subheader-options\s*@i', '', $rawS); // Remove "{subheader=options"
    $rawS = preg_replace('@\s*\}@i', '', $rawS); // Remove ending "}"
    $rawS = preg_replace('@\s+@i', ' ', $rawS); // Convert whitespace to a space
    $subheaderAttributes = ' '.$rawS; // Add a space to place attributes in tag
  }
  
  $rHtml = '';
  $shOptionsL = array(); // Array of page name : image URL
  $shOptionsL['*'] = ''; // Avoid an error later if key/value not set
  $shOptionsL['News'] = ''; // Likewise, prefer no-URL if News not present
  
  // Get Featured Image from definition page, as default for pages not defined by child pages
  if (has_post_thumbnail($widgetDefPageID)) {
    $thumbHtml = get_the_post_thumbnail($widgetDefPageID, 'qnrwp-largest'); // Largest theme size
    $shOptionsL['*'] = qnrwp_get_post_thumbnail_url($thumbHtml);
  }
  
  // Get child pages and their Featured Images
  $widgetChildren = get_page_children($widgetDefPageID, get_pages());
  if (count($widgetChildren) > 0) {
    foreach ($widgetChildren as $widgetChild) {
      // Store name and img URL as key:value
      if (has_post_thumbnail($widgetChild)) {
        $thumbHtml = get_the_post_thumbnail($widgetChild, 'qnrwp-largest');
        $shOptionsL[$widgetChild->post_title] = qnrwp_get_post_thumbnail_url($thumbHtml);
      }
    }
  }

  if ($GLOBALS['isNews']) { // Create News header, for all News pages
    $headerTitleText = 'News';
    $headerURL = $shOptionsL['News'];
  }
  else if ($GLOBALS['postsAmount'] == 'single') { // Create Page header
    if ($GLOBALS['pageTitle'] == 'Home') {
      // TODO use page content instead
      $headerTitleText = '<b><big>'.get_bloginfo('name').'</big></b><br>'.PHP_EOL;
      $headerTitleText .= get_bloginfo('description');
    }
    else {
      $headerTitleText = $GLOBALS['pageTitle'];
    }
    try { // Match URLs to pages, named, or as assigned to *
      $headerURL = $shOptionsL[$GLOBALS['pageTitle']];
    }
    catch (Exception $e) {
      $headerURL = $shOptionsL['*'];
    }
  }
  $rHtml = '<div'.$subheaderAttributes.' style="background-image:url(\''.$headerURL.'\');">'.PHP_EOL;
  $rHtml .= '<div><p>'.$headerTitleText.'</p></div></div>'.PHP_EOL;
  return $rHtml;
}


// ----------------------- Custom Widget carousel HTML getter from defining page ID

function qnrwp_get_carousel_html($widgetDefPageID, $imageSize = 'large') {
  // $widgetDefPageID - ID of the page defining the carousel
  // $imageSize - thumbnail, medium, large, full, qnrwp-largest (keep them under 2000px)
  
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
  // Get child pages as content DIVs
  $widgetChildren = get_page_children($widgetDefPageID, get_pages());
  $iC = 0;
  if (count($widgetChildren) > 0) {
    foreach ($widgetChildren as $widgetChild) {
      // Construct item DIV for carousel
      $thumbBG = '';
      if (has_post_thumbnail($widgetChild)) {
        // Get BG image from Featured Image, hide all but the first two (JS will show them)
        // Hoping that will speed up the load: only first image loaded at first, then when displayed, the rest
        $thumbHtml = get_the_post_thumbnail($widgetChild, $imageSize);
        if ($iC < 1) $thumbBG = ' style="background-image:url(\''.qnrwp_get_post_thumbnail_url($thumbHtml).'\')"';
        else $thumbBG = ' style="display:none;background-image:url(\''.qnrwp_get_post_thumbnail_url($thumbHtml).'\')"';
      }
      $htmlContent = apply_filters('the_content', get_post_field('post_content', $widgetChild->ID));
      // Wrap the content with centered inner DIV for easier styling
      $htmlContent = '<div class="slide-inner center">'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      $rHtml .= '<div'.$thumbBG.'>'.PHP_EOL.$htmlContent.'</div>'.PHP_EOL;
      $iC += 1;
    }
  }
  // The carousel widget may already have a class assigned in the defining options
  if (stripos($carouselDataAttributes, 'class="') !== false) {
    $carouselDataAttributes = str_replace('class="', 'class="qnr-carousel ', $carouselDataAttributes);
    $rHtml = '<div'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  }
  else $rHtml = '<div class="qnr-carousel"'.$carouselDataAttributes.'>'.PHP_EOL.$rHtml.'</div>'.PHP_EOL;
  return $rHtml;
}


// ----------------------- Custom Widget picker menu

function qnrwp_get_widget_defs_menu($existingVal, $outputID, $outputName) {
  // Returns HTML select/options menu listing widgets defined as "QNRWP-Widget-" prefixed pages
  // No output field required, the Widget updating works with "selected" <option> in <select>
  $pages = get_pages();
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
    $rP = '<p>Select the widget to display:</p><select name="'.$outputName.'" id="'.$outputID.'">'.PHP_EOL . $rP . '</select><br>'.PHP_EOL;
  }
  else $rP = '<p>No pages defining QNRWP widgets exist yet.</p>'.PHP_EOL;
  return $rP;
}


// ----------------------- Custom Widget pages-to-display-on selector

function qnrwp_get_pages_form_for_widget($pagesVal, $pagesL, $outputID, $outputName) {
  // Returns HTML to use in a widget form so pages can be selected for widget to appear on
  // $pagesVal - string value that is stored in db
  // $pagesL - array exploded from pagesVal, listing checked pages options
  // $outputID - output field ID matching the ID for saving the value that is updated by JS on checkbox clicks
  // $outputName - output field Name
  $pages = get_pages(); 
  $rP = '';
  $pageCheckbox = '';
  $pageParentID = false;
  foreach ($pages as $page) {
    // Omit pages set up as a widget's contents with "QNRWP-Widget-" prefix, and their children (one level only)
    $pageParentID = wp_get_post_parent_id($page->ID);
    // wp_get_post_parent_id is supposed to return false when no parent, but returns 0
    $pageParentTitle = $pageParentID ? get_post($pageParentID)->post_title : '';
    if (stripos($page->post_title, 'QNRWP-Widget-') === false && stripos($pageParentTitle, 'QNRWP-Widget-') === false) {
      // Test if this page is in $pagesL array parameter, select if so
      $checked = in_array($page->ID, $pagesL) ? ' checked="checked"' : ''; // Don't convert page ID to string...
      $pageCheckbox = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
                          .' value="'.$page->ID.'">'.esc_html($page->post_title).'</label><br>'.PHP_EOL;
      $rP .= $pageCheckbox;
    }
  }
  if ($rP) {
    // All News Posts checkbox (reverse order of concatenating)
    $checked = in_array('-1', $pagesL) ? ' checked="checked"' : '';
    $rP = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-1" name="qnrwp-all-news" id="qnrwp-all-news">All News Posts</label><br>'.PHP_EOL . $rP;
    // All Pages Except the Following checkbox
    $checked = in_array('-2', $pagesL) ? ' checked="checked"' : '';
    $rP = '<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-2" name="qnrwp-all-except" id="qnrwp-all-except">All except the following:</label><br>'.PHP_EOL . $rP;
    // Input field collecting the checked values as a setting to save, ID passed in param, as well as previous value
    // Class attribute is for JS identification as the ID and name are set by WP code
    $rP .= 'Output: <input name="'.$outputName.'" id="'.$outputID.'" class="qnrwp-setting-output-field" value="'.$pagesVal.'">'.PHP_EOL;
    // No <form> wrap, that's handled by the WP widget code, but DIV parent wrap for JS
    $rP = '<div style="box-sizing:border-box;padding:1em;height:200px;overflow:auto;border:solid thin #EEE">'.PHP_EOL . $rP;
    $rP = '<p>Select the page(s) to display this Widget. Click "All except the following:" to exclude the selected.</p>'.PHP_EOL . $rP;
    $rP .= '</div>'.PHP_EOL;
  }
  else $rP = '<p>No pages that could display the widget exist yet.</p>';
  return $rP;
}

// ===================== WP FUNCTIONS =====================

// ----------------------- Enqueue Scripts & Styles

function qnrwp_enqueue_styles() {
  wp_enqueue_style('qnr-interface-stylesheet', get_template_directory_uri() . '/res/css/qnr-interface.css', null, null);
  wp_enqueue_style('qnr-hmenu-stylesheet', get_template_directory_uri() . '/res/css/qnr-hmenu.css', null, null);
  // Load parent stylesheet before child's
  // Retreive parent theme dir: get_template_directory_uri()
  // Retreive child theme dir: get_stylesheet_directory_uri()
  wp_enqueue_style('theme-stylesheet', get_template_directory_uri() . '/style.css', null, null);
  if (is_child_theme()) {
    wp_enqueue_style('child-stylesheet', get_stylesheet_uri(), null, null); // Child theme style.css
  }
}

function qnrwp_enqueue_scripts() {
  wp_enqueue_script('qnr-interface-js', get_template_directory_uri() . '/res/js/qnr-interface.js', null, null);
  wp_enqueue_script('qnr-hmenu-js', get_template_directory_uri() . '/res/js/qnr-hmenu.js', null, null);
  wp_enqueue_script('qnrwp_a-main-js', get_template_directory_uri() . '/qnrwp_a-main.js', null, null);
}
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_styles');
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_scripts');


// ----------------------- FILTERS

// ----------------------- Media Screen JS with note about image size
function qnrwp_selectively_enqueue_admin_script( $hook ) {
  $currentScreen = get_current_screen();
  if($currentScreen->id == 'options-media') {
    wp_enqueue_script('qnrwp_a-admin-js', get_template_directory_uri() . '/qnrwp_a-admin.js', null, null);
  }
}
add_action('admin_enqueue_scripts', 'qnrwp_selectively_enqueue_admin_script');

// ----------------------- Add registered custom image size to Dashboard
function qnrwp_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'qnrwp-largest' => 'QNRWP-Largest',
    ));
}
add_filter('image_size_names_choose', 'qnrwp_custom_image_sizes');

// ----------------------- Search Form filter
function qnrwp_search_form_filter($form) {  
  $form = preg_replace('@\s+<span class="screen-reader-text">[^<]+</span>@i', '', $form);
  $form = preg_replace('@\s+<input type="submit" class="search-submit" value="[^\"]+" />@i', 
                            '<input type="submit" class="search-submit" value="g" />', $form); // No whitespace
  $form = preg_replace('@Search &hellip;@i', 'Search news&hellip;', $form);
  $form = preg_replace('@\s+</?label>@i', '', $form);
  return $form;
}
add_filter('get_search_form', 'qnrwp_search_form_filter');

// ----------------------- Excerpt 'read more' filter
function qnrwp_excerpt_more_filter($moreStr) {
  return '...';
}
add_filter('excerpt_more', 'qnrwp_excerpt_more_filter');

// ----------------------- Main Query filter

// Customize parameters of main Loop query
function qnrwp_main_query_filter($query) {
    if ($query->is_main_query() && !is_page() && !is_admin()) { // Not in Admin screens...
        $query->set('category_name', 'news,uncategorized');
    }
}
add_action('pre_get_posts', 'qnrwp_main_query_filter' );

// ----------------------- General Widget before/after filter

// Take care of things other filters cannot...
function qnrwp_dynamic_sidebar_params($params) {
  // Don't filter positive, we do it negative (generic positives are in sidebar definition)
  // Text widget will have an inconsistent 'textwidget' class only, cannot be filtered
  if ($params[0]['widget_name'] == 'Custom Menu' || $params[0]['widget_name'] == 'Text') {
    $params[0]['before_widget'] = PHP_EOL.'<!-- Widget -->'.PHP_EOL;
    $params[0]['after_widget'] = '';
  }
  return $params;
}
add_filter('dynamic_sidebar_params', 'qnrwp_dynamic_sidebar_params');

// ----------------------- Title filter

// Customize title with blog name, and take care of it on Home page
function qnrwp_title_filter($title) {
  if (empty($title) && (is_home() || is_front_page())) {
    $title = 'Home';
  }
  $title =  get_bloginfo('name') . ' | ' . $title;
  return $title;
}
add_filter('wp_title', 'qnrwp_title_filter');


// ----------------------- Widget Title filter

// Remove titles on widgets that shouldn't have them displayed
function qnrwp_widget_title_filter($wtitle) {
  if (  stripos($wtitle, 'Copyright') !== false || 
        stripos($wtitle, 'Menu') !== false || 
        stripos($wtitle, 'Logo') !== false || 
        stripos($wtitle, 'Search') !== false || 
        stripos($wtitle, 'Social Links') !== false   ) {
    return '';
  }
  return $wtitle;
}
add_filter('widget_title', 'qnrwp_widget_title_filter');


// ----------------------- Recent Posts widget args filter

function qnrwp_recent_posts_widget_args($args) {
  $args['category_name'] = 'news,uncategorized';
  return $args;
}
add_filter('widget_posts_args', 'qnrwp_recent_posts_widget_args'); 


// ----------------------- Custom Menu widget args filters

function qnrwp_nav_menu_args($args) {
  // Make main nav menu a QI Navmenu
  if($args['menu'] == wp_get_nav_menu_object('QNRWP Main Nav Menu')) {
    //$args['depth'] = -1; // Make it flat, no submenus
    // Cannot concatenate to default container class...
    $args['container_class'] = 'widget qnr-navmenu';
  }
  // Class footer menu
  else if($args['menu'] == wp_get_nav_menu_object('QNRWP Footer Menu')) {
    $args['container_class'] = 'widget qnr-footer-menu';
  }
  //// Class test menu - Doesn't work from shortcode...
  //else if($args['menu'] == wp_get_nav_menu_object('Test_Menu')) {
    //$args['container_class'] = 'qnr-hmenu';
  //}
	return $args;
}
add_filter('wp_nav_menu_args', 'qnrwp_nav_menu_args');

function qnrwp_menu_classes($classes, $item, $args, $depth) {
  // Make submenu of main nav menu a qnr-hmenu
  // Note that args and its menu are objects in this hook...
  if ($args->menu == wp_get_nav_menu_object('QNRWP Main Nav Menu') && 
            $depth == 0 && in_array('menu-item-has-children', $classes)) {
    $classes[] = 'qnr-hmenu';
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'qnrwp_menu_classes', 10, 4);


// ----------------------- Excerpt length filter

function qnrwp_custom_excerpt_length($length) {
	return 20;
}
add_filter('excerpt_length', 'qnrwp_custom_excerpt_length', 999);


// ----------------------- WIDGETS

// ----------------------- Custom Widget definition

class QNRWP_Custom_Widget extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_custom_widget',
			'description' => 'Custom Widget to display.',
		);
		parent::__construct('qnrwp_custom_widget', 'QNRWP Custom Widget', $widget_ops);
	}
  
	public function widget($args, $instance) {
    // Get the pages list from string value
    $pagesL = explode(',', $instance['mypages']);
    // Decide whether to show the widget on this page
    $allExcept = in_array('-2', $pagesL);
    $allNews = in_array('-1', $pagesL);
    $thisPage = get_the_ID();
    $showWidget = false;
    $postType = get_post_type();
    // TODO: News page has a different post ID from its page ID
    if ($postType == 'post' && ! is_home() && $allNews && ! $allExcept) $showWidget = true;
    else if ($postType == 'post' && ! is_home() && ! $allNews && $allExcept) $showWidget = true;
    else if (($postType == 'page' || is_home()) && in_array($thisPage, $pagesL) && ! $allExcept) $showWidget = true;
    else if (($postType == 'page' || is_home()) && ! in_array($thisPage, $pagesL) && $allExcept) $showWidget = true;
    // Display
    if ($showWidget) {
      $rHtml = '';
      // Carousel
      if (stripos(get_post($instance['mywidget'])->post_title, 'Carousel') !== false) {
        $rHtml = qnrwp_get_carousel_html($instance['mywidget'], 'large'); // Other sizes supported by shortcode
      }
      // Sub Header
      else if (stripos(get_post($instance['mywidget'])->post_title, 'SubHeader') !== false) {
        $rHtml = qnrwp_get_subheader_html($instance['mywidget']);
      }
      echo $rHtml; // Done
    }
	}
  
	public function form($instance) {
		// ----------------------- Output widget admin options form
    // Add JS for widget form
    wp_enqueue_script('qnrwp_a-adminwidgets-js', get_template_directory_uri() . '/qnrwp_a-adminwidgets.js', null, null);
    // Page(s) to display the widget field
		$pagesVal = !empty($instance['mypages']) ? $instance['mypages'] : '';
    $pagesL = explode(',', $pagesVal);
    $pagesOutputID = esc_attr($this->get_field_id('mypages'));
    $pagesOutputName = esc_attr($this->get_field_name('mypages'));
    // Widget (defined by a page) to display field
		$widget = (!empty($instance['mywidget'])) ? $instance['mywidget'] : '';
    $fieldWidgetID = esc_attr($this->get_field_id('mywidget'));
    $fieldWidgetName = esc_attr($this->get_field_name('mywidget'));
    // HTML form
    echo qnrwp_get_pages_form_for_widget($pagesVal, $pagesL, $pagesOutputID, $pagesOutputName);
    echo qnrwp_get_widget_defs_menu($widget, $fieldWidgetID, $fieldWidgetName);
	}
  
	public function update($new_instance, $old_instance) {
		// Process and save widget options
		$instance = array();
		$instance['mypages'] = (!empty($new_instance['mypages'])) ? strip_tags($new_instance['mypages']) : '';
		$instance['mywidget'] = (!empty($new_instance['mywidget'])) ? strip_tags($new_instance['mywidget']) : '';
		return $instance;
	}
}


// ----------------------- Content widget definition

class QNRWP_Content extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_content',
			'description' => 'Page or post content from main edit screen.',
		);
		parent::__construct('qnrwp_content', 'QNRWP Page or Post Content', $widget_ops);
	}
  
	public function widget($args, $instance) {
		// Echo global with pre-constructed page or post content HTML
    echo $GLOBALS['contentHtml'];
	}
}


// ----------------------- Featured News widget definition

class QNRWP_Featured_News extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_featured_news',
			'description' => 'Excerpts of 4 latest Posts, with category News or Uncategorized, with Featured Images, to appear on the static Home page.',
		);
		parent::__construct('qnrwp_featured_news', 'QNRWP Featured News', $widget_ops);
	}
  
	public function widget($args, $instance) {
    if (is_front_page()) {
      // ----------------------- Custom Query
      $the_query = new WP_Query(array('post_type' => 'post', 'nopaging' => true));
      if ($the_query->have_posts()) {
        $featuredCount = 0;
        $rHtml = '<!-- Featured News -->'.PHP_EOL;
        $rHtml .= '<div class="featured-news-block">'.PHP_EOL; // Opening Featured News block
        $rHtml .= '<div>'.PHP_EOL; // Opening of first of two item-of-two DIVs
        // ----------------------- The Loop
        while ($the_query->have_posts()) {
          $the_query->the_post();
          if (in_category(array('news', 'uncategorized')) && has_post_thumbnail()) {
            $thumbHtml = get_the_post_thumbnail(get_the_ID(), 'medium');
            $thumbUrl = qnrwp_get_post_thumbnail_url($thumbHtml);
            $postLink = get_the_permalink(get_the_ID());
            $rHtml .= '<a class="featured-news-item" href="'.$postLink.'">'.PHP_EOL; // Opening item
            $rHtml .= '<div class="featured-news-item-header" style="background-image:url(\''.$thumbUrl.'\')">&nbsp;</div>'.PHP_EOL;
            $rHtml .= '<div class="featured-news-item-text">'.PHP_EOL;
            $rHtml .= '<h1>'.get_the_title().'</h1>'.PHP_EOL;
            $rHtml .= '<div class="featured-news-item-excerpt">'.PHP_EOL.get_the_excerpt().PHP_EOL.'</div>'.PHP_EOL;
            $rHtml .= '</div>'.PHP_EOL.'</a>'.PHP_EOL; // Closing item
            $featuredCount += 1;
            if ($featuredCount == 2) $rHtml .= '</div><div><!-- No whitespace! -->'.PHP_EOL; // Close first item-of-two, open next
          }
          if ($featuredCount == 4) break;
        }
        $rHtml .= '</div>'.PHP_EOL.'</div><!-- End of Featured News -->'.PHP_EOL; // Closing second item-of-two and Featured News block
        if ($featuredCount == 4) echo $rHtml; // Echo nothing if 4 Featured News items not obtained
        
        // Restore original Post Data
        wp_reset_postdata();
      }
      else {
        // No posts found
      }
    } // If not on front page, do nothing
	}
  
	public function form($instance) {
		// Output widget admin options form
    ?>
		<p>Excerpts of 4 latest Posts, with category News or Uncategorized, with Featured Images, to appear on the static Home page.</p>
		<?php 
	}
  
}


// ----------------------- Sidebar & Widget registration

function qnrwp_widgets_init() {
  // ----------------------- Header Row
  register_sidebar(array(
    'name'          => 'Header Row',
    'id'            => 'qnrwp-row-1',
    'description'   => 'Widgets in this area will be shown on all posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Sub Header Row (within possibly narrower content & sidebars row)
  register_sidebar(array(
    'name'          => 'Sub Header Row',
    'id'            => 'qnrwp-subrow-1',
    'description'   => 'Widgets in this area will be shown on all or some posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Left Sidebar
  register_sidebar(array(
    'name'          => 'Left Sidebar',
    'id'            => 'qnrwp-sidebar-1',
    'description'   => 'Widgets in this area will be shown on all posts but not on pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Main Content Box
  register_sidebar(array(
    'name'          => 'Main Content Row',
    'id'            => 'qnrwp-row-2',
    'description'   => 'Widgets in this area will be shown on all or some posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Right Sidebar
  register_sidebar(array(
    'name'          => 'Right Sidebar',
    'id'            => 'qnrwp-sidebar-2',
    'description'   => 'Widgets in this area will be shown on all  posts but not on pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  // ----------------------- Footer Row
  register_sidebar(array(
    'name'          => 'Footer Row',
    'id'            => 'qnrwp-row-3',
    'description'   => 'Widgets in this area will be shown on all posts and pages.',
    'before_widget' => "<!-- Widget -->\n" . '<div id="%1$s" class="widget %2$s">'.PHP_EOL,
    'after_widget'  => "\n</div>\n",
    //'before_widget' => "<!-- Widget -->\n",
    //'after_widget'  => '',
    'before_title'  => "<h2 class=\"widget-title\">",
    'after_title'   => "</h2>\n",
  ));
  register_widget('QNRWP_Custom_Widget');
  register_widget('QNRWP_Content');
  register_widget('QNRWP_Featured_News');
}
add_action('widgets_init', 'qnrwp_widgets_init');


function qnrwp_setup() {
  // ----------------------- Post Thumbnails support
  add_theme_support('post-thumbnails');
  
  // ----------------------- HTML5 support
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));
  
  // ----------------------- Add new image size, the largest
  add_image_size('qnrwp-largest', 2000, 1500, false);

}
add_action('after_setup_theme', 'qnrwp_setup');


// ----------------------- Shortcodes definitions

// Content argument is for content enclosed in open/closed shortcodes

// [featuredimage size=large align=center link=no] TODO process options
function qnrwp_featuredimage_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'size' => 'large',
    'align' => 'center',
    'link' => 'no',
  ), $atts);
  if (has_post_thumbnail()) {
    $id = get_the_ID(); // Post id, obtained from WP global
    return get_the_post_thumbnail($post=$id,$size=$a['size']);
  }
}
add_shortcode('featuredimage', 'qnrwp_featuredimage_shortcode');


// [include file='fileURL']
function qnrwp_include_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'file' => '',
  ), $atts);
  if ($a['file'] !== '') {
    // Assume file parameter is relative to child theme directory, or theme if no child
    return include(trailingslashit(get_stylesheet_directory()) . $a['file']);
  }
}
add_shortcode('include', 'qnrwp_include_shortcode');

// [menu name='menuName']
function qnrwp_menu_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'name' => '',
    'id' => '',
    'depth' => 0,
  ), $atts);
  $mymenu = wp_nav_menu(array(
    'menu'              => $a['name'],
    'echo'              => false,
    'container_class'   => 'test-menu qnr-hmenu',
    'container_id'      => $a['id'],
    'depth'             => $a['depth'],
    //'walker' => new QNRWP_Walker_Nav_Menu()
  ));
  return $mymenu;
}
add_shortcode('menu', 'qnrwp_menu_shortcode');

// [carousel name="QNRWP-Widget-Carousel-1" size="large"]
function qnrwp_carousel_shortcode($atts, $content = null) {
  $a = shortcode_atts(array(
    'name' => '',
    'size' => 'large',
  ), $atts);
  $pages = get_pages();
  $rHtml = '';
  foreach ($pages as $page) {
    if ($page->post_title === $a['name']) {
      $rHtml = qnrwp_get_carousel_html($page->ID, $a['size']);
      break;
    }
  }
  return $rHtml;
}
add_shortcode('carousel', 'qnrwp_carousel_shortcode');

?>