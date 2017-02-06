<?php

// ===================== GENERIC FUNCTIONS =====================

// ----------------------- Post Thumbnail URL getter

function get_post_thumbnail_url($thumbHtml) {
    // Get URL from IMG src attribute for CSS background styling
    $mm = preg_match('@src="(https?://[^\"]+)"@i', $thumbHtml, $matches);
    if ($mm) {
      return $matches[1];
    }
    else return '';
}

// ===================== WP FUNCTIONS =====================

// ----------------------- Enqueue Scripts & Styles

function qnrwp_enqueue_styles() {
  if (is_child_theme()) {
    // Load parent stylesheet first if this is a child theme
    wp_enqueue_style('parent-stylesheet', trailingslashit(get_template_directory_uri()) . 'style.css', null, null);
  }
  // Load active theme stylesheet in both cases
  wp_enqueue_style('qnr-interface-stylesheet', trailingslashit(get_template_directory_uri()) . 'res/css/qnr-interface.css', null, null);
  wp_enqueue_style('contact-form-stylesheet', trailingslashit(get_template_directory_uri()) . 'res/css/contact_form.css', null, null);
  //wp_enqueue_style('qnr-hmenu-stylesheet', trailingslashit(get_template_directory_uri()) . 'res/css/qnr-hmenu.css', null, null);
  wp_enqueue_style('theme-stylesheet', get_stylesheet_uri(), null, null);
}

//function qnrwp_enqueue_style() {
  //wp_enqueue_style('core', 'style.css', false); 
//}

function qnrwp_enqueue_scripts() {
  wp_enqueue_script('qnr-interface-js', trailingslashit(get_template_directory_uri()) . 'res/js/qnr-interface.js', null, null);
  wp_enqueue_script('contact-js', trailingslashit(get_template_directory_uri()) . 'res/js/contact.js', null, null);
  //wp_enqueue_script('qnr-hmenu-js', trailingslashit(get_template_directory_uri()) . 'res/js/qnr-hmenu.js', null, null);
  wp_enqueue_script('qnrwp_a-main-js', trailingslashit(get_template_directory_uri()) . 'qnrwp_a-main.js', null, null);
}
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_styles');
add_action('wp_enqueue_scripts', 'qnrwp_enqueue_scripts');


// ----------------------- FILTERS

// ----------------------- Search Form filter
function qnrwp_search_form_filter($form) {
  //if ( 'html5' == $format ) {
			//$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				//<label>
					//<span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>
					//<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" />
				//</label>
				//<input type="submit" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
			//</form>';
		//}
  
  $form = preg_replace('@\s+<span class="screen-reader-text">[^<]+</span>@i', '', $form);
  $form = preg_replace('@\s+<input type="submit" class="search-submit" value="[^\"]+" />@i', 
                            '<input type="submit" class="search-submit" value="g" />', $form); // No whitespace
  $form = preg_replace('@Search &hellip;@i', 'Search news&hellip;', $form);
  $form = preg_replace('@\s+</?label>@i', '', $form);
  return $form;
}
add_filter('get_search_form', 'qnrwp_search_form_filter');

// ----------------------- Main Query filter

// Customize parameters of main Loop query
function qnrwp_main_query_filter($query) {
    if ($query->is_main_query() && !is_page() && !is_admin()) { // Not in Admin screens
        $query->set('category_name', 'news,uncategorized');
    }
}
add_action('pre_get_posts', 'qnrwp_main_query_filter' );

// ----------------------- General Widget before/after filter

// Take care of things other filters cannot...
function qnrwp_dynamic_sidebar_params($params) {
  //if ($params[0]['widget_name'] == 'Recent Posts') {
    //$params[0]['before_widget'] = PHP_EOL.'<!-- Widget -->'.PHP_EOL.'<div class="widget widget-recent-posts">'.PHP_EOL;
    //$params[0]['after_widget'] = '</div>'.PHP_EOL;
  //}
  //else if ($params[0]['widget_name'] == 'Search') {
    //$params[0]['before_widget'] = PHP_EOL.'<!-- Widget -->'.PHP_EOL.'<div class="widget widget-search">'.PHP_EOL;
    //$params[0]['after_widget'] = '</div>'.PHP_EOL;
  //}
  //else if ($params[0]['widget_name'] == 'Calendar') {
    //$params[0]['before_widget'] = PHP_EOL.'<!-- Widget -->'.PHP_EOL.'<div class="widget widget-calendar">'.PHP_EOL;
    //$params[0]['after_widget'] = '</div>'.PHP_EOL;
  //}
  
  // Actually, don't filter positive, we do it negative (generic positives are in sidebar definition)
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


// ----------------------- Custom Menu widget args filter

function qnrwp_nav_menu_args($args) {
  // Make main nav menu a Quicknr Nav Menu
  if($args['menu'] == wp_get_nav_menu_object('QNRWP Main Nav Menu')) {
    $args['depth'] = -1; // Make it flat, no submenus
    // Cannot concatenate to default container class...
    $args['container_class'] = 'widget qnr-navmenu';
  }
  // Class footer menu
  else if($args['menu'] == wp_get_nav_menu_object('QNRWP Footer Menu')) {
    $args['container_class'] = 'widget qnr-footer-menu';
  }
	return $args;
}
add_filter('wp_nav_menu_args', 'qnrwp_nav_menu_args');


// ----------------------- Excerpt length filter

function qnrwp_custom_excerpt_length($length) {
	return 25;
}
add_filter('excerpt_length', 'qnrwp_custom_excerpt_length', 999);


// ----------------------- WIDGETS

// ----------------------- My Widget widget definition

class QNRWP_My_Widget extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_my_widget',
			'description' => 'My Widget is awesome.',
		);
		parent::__construct('qnrwp_my_widget', 'QNRWP My Widget', $widget_ops);
	}
  
	public function widget($args, $instance) {
		// Widget content output
    echo 'This is my widget output';
    
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		if (!empty($instance['mysetting'])) {
			echo '<div class="widget-mysetting">' . $instance['mysetting'] . '</div>';
		}
		echo esc_html__('Hello, World!', 'text_domain');
		echo $args['after_widget'];
	}
  
	public function form($instance) {
		// Output widget admin options form
    ?>
    <?php
    // Title field vars
		$title = !empty($instance['title']) ? $instance['title'] : esc_html('New title');
    $fieldTitleID = esc_attr($this->get_field_id('title'));
    $fieldTitleName = esc_attr($this->get_field_name('title'));
    // My Setting field vars
		$mysetting = !empty($instance['mysetting']) ? $instance['mysetting'] : esc_html('New mysetting');
    $fieldMySettingID = esc_attr($this->get_field_id('mysetting'));
    $fieldMySettingName = esc_attr($this->get_field_name('mysetting'));
    // HTML form
		?>
		<p>
		<label for="<?php echo $fieldTitleID ?>"><?php esc_attr('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $fieldTitleID ?>" name="<?php echo $fieldTitleName ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
		<label for="<?php echo $fieldMySettingID ?>"><?php esc_attr('Mysetting:'); ?></label> 
		<input class="widefat" id="<?php echo $fieldMySettingID ?>" name="<?php echo $fieldMySettingName ?>" type="text" value="<?php echo esc_attr($mysetting); ?>">
		</p>
		<?php 
	}
  
	public function update($new_instance, $old_instance) {
		// Process and save widget options
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['mysetting'] = (!empty($new_instance['mysetting'])) ? strip_tags($new_instance['mysetting']) : '';
		return $instance;
	}
}


// ----------------------- Carousel_Image widget definition

class QNRWP_Carousel_Image extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_carousel_image',
			'description' => 'Quicknr Interface image carousel, using "slide-1.jpg", "slide-2.jpg" etc. in "res/img/" theme folder, to appear on the static Home page.',
		);
		parent::__construct('qnrwp_carousel_image', 'QNRWP Carousel-Image', $widget_ops);
	}
  
	public function widget($args, $instance) {
		// TODO
	}
  
	public function form($instance) {
		// Output widget admin options form
    ?>
		<p>Quicknr Interface image carousel, using "slide-1.jpg", "slide-2.jpg" etc. in "res/img/" theme folder, to appear on the static Home page.</p>
		<?php 
	}
}


// ----------------------- Sub Header widget definition

class QNRWP_Sub_Header extends WP_Widget {
  
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_sub_header',
			'description' => 'Sub header with a scrolling image and page title.',
		);
		parent::__construct('qnrwp_sub_header', 'QNRWP Sub Header', $widget_ops);
	}
  
	public function widget($args, $instance) {
    if ($GLOBALS['isNews']) { // Create News header, for all News pages
      $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/type-blocks_amador-loureiro.jpg';
      $headerTitle = 'News';
      echo '<div class="qnr-scroller" style="background-image:url(\''.$headerURL.'\');">'.PHP_EOL;
      echo '<div><p>'.$headerTitle.'</p></div></div>'.PHP_EOL;
    }
    else if ($GLOBALS['postsAmount'] == 'single') { // Create Page header
      if ($GLOBALS['pageTitle'] == 'Home') {
        $headerTitleText = '<b><big>'.get_bloginfo('name').'</big></b><br>'.PHP_EOL;
        $headerTitleText .= get_bloginfo('description');
      }
      else {
        $headerTitleText = $GLOBALS['pageTitle'];
      }
      if ($GLOBALS['pageTitle'] == 'Support') {
        $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/climbing-gear_cameron-kirby.jpg';
      }
      //else if ($GLOBALS['pageTitle'] == 'News') {
        //$headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/type-blocks_amador-loureiro.jpg';
      //}
      else if ($GLOBALS['pageTitle'] == 'About') {
        $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/green-shoots_markus-spiske.jpg';
      }
      else if ($GLOBALS['pageTitle'] == 'Contact') {
        $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/road-snow_kimon-maritz.jpg';
      }
      else if ($GLOBALS['pageTitle'] == 'Legal') {
        $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/books_eli-francis.jpg';
      }
      else if ($GLOBALS['pageTitle'] == 'Home') {
        $headerURL = trailingslashit(get_template_directory_uri()) . 'res/img/design-tools_sergey-zolkin.jpg';
      }
      echo '<div class="qnr-scroller" style="background-image:url(\''.$headerURL.'\');">'.PHP_EOL;
      echo '<div><p>'.$headerTitleText.'</p></div></div>'.PHP_EOL;
    }
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
            $thumbUrl = get_post_thumbnail_url($thumbHtml);
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
  // ----------------------- Sub Header Row (within narrower content & sidebars row)
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
  register_widget('QNRWP_My_Widget');
  register_widget('QNRWP_Carousel_Image');
  register_widget('QNRWP_Sub_Header');
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
  
  // ----------------------- Post Formats support

  /*
    - ASIDE - Typically styled without a title. Similar to a Facebook 
    note update.

    - GALLERY - A gallery of images. Post will likely contain a gallery 
    shortcode and will have image attachments.

    - LINK - A link to another site. Themes may wish to use the first 
    <a href=””> tag in the post content as the external link for that 
    post. An alternative approach could be if the post consists only of 
    a URL, then that will be the URL and the title (post_title) will be 
    the name attached to the anchor for it.

    - IMAGE - A single image. The first <img /> tag in the post could be 
    considered the image. Alternatively, if the post consists only of a 
    URL, that will be the image URL and the title of the post (post_title) 
    will be the title attribute for the image.

    - QUOTE - A quotation. Probably will contain a blockquote holding 
    the quote content. Alternatively, the quote may be just the content, 
    with the source/author being the title.

    - STATUS - A short status update, similar to a Twitter status update.

    - VIDEO - A single video or video playlist. The first <video /> tag 
    or object/embed in the post content could be considered the video. 
    Alternatively, if the post consists only of a URL, that will be the 
    video URL. May also contain the video as an attachment to the post, 
    if video support is enabled on the blog (like via a plugin).

    - AUDIO - An audio file or playlist. Could be used for Podcasting.

    - CHAT - A chat transcript, like so:

      John: foo
      Mary: bar
      John: foo 2

    Note: When writing or editing a Post, Standard is used to designate 
    that no Post Format is specified. Also if a format is specified that 
    is invalid then standard (no format) will be used.

  */
  
  // Post Formats are not enabled, better handled with categories
  //add_theme_support('post-formats', array(    'aside',
                                              //'gallery',
                                              //'link',
                                              //'image',
                                              //'quote',
                                              //'status',
                                              //'video',
                                              //'audio',
                                              //'chat',
                                              //));
  
}
add_action('after_setup_theme', 'qnrwp_setup');

// Add post-formats to post_type 'page' (not used)
//function qnrwp_add_post_formats_to_page(){
    //add_post_type_support('page', 'post-formats');
    //register_taxonomy_for_object_type('post_format', 'page');
//}
//add_action('init', 'qnrwp_add_post_formats_to_page', 11);


// ----------------------- Shortcodes definition

// [featuredimage size=large align=center link=no]
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
    return include($a['file']);
  }
}
add_shortcode('include', 'qnrwp_include_shortcode');


//// ----------------------- Menu registration (not needed)
//function qnrwp_register_menus() {
  //register_nav_menus();
//}
//add_action( 'init', 'qnrwp_register_menus' );


?>
