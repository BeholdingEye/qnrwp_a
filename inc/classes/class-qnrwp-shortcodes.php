<?php

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes setup class (a singleton)
 */
final class QNRWP_Shortcodes {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Shortcode definition hooks
   */
  private function hooks() {
    add_shortcode('featured-image',   array($this, 'featured_image'));
    add_shortcode('include',          array($this, 'include_sc'));
    add_shortcode('contact-form',     array($this, 'contact_form'));
    add_shortcode('menu',             array($this, 'menu'));
    add_shortcode('carousel',         array($this, 'carousel'));
    add_shortcode('samples',          array($this, 'samples'));
  }
  
  
  /**
   * Featured image shortcode definition
   * 
   * [featured-image size=large align=center link=no] TODO process options
   * 
   * Content argument is for content enclosed in open/closed shortcodes
   */
  public function featured_image($atts, $content = null) {
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
  
  
  /**
   * Include file / post shortcode definition
   * 
   * [include file='fileURL'] or [include post='post ID' layout='title,excerpt' class='class name']
   * 
   * Assumes file parameter is relative to child theme directory, or theme if no child
   * 
   * Layout parameter accepts 'title', 'date', 'excerpt', and 'content' to appear in listed order
   * 
   * Class parameter would define a class to be added to the wrapper DIV of the included post, may be used as ID
   */
  public function include_sc($atts, $content = null) {
    $a = shortcode_atts(array(
      'file' => '',
      'post' => '',
      'layout' => '',
      'class' => '',
    ), $atts);
    if ($a['file'] !== '') { // File
      ob_start();
      include trailingslashit(get_stylesheet_directory()) . $a['file'];
      return ob_get_clean();
    } else if ($a['post'] !== '') { // Post
      if (!(is_home() || is_search() || is_archive() || is_date()) && $a['post'] == get_the_ID()) return;
      $post = get_post($a['post'], ARRAY_A);
      if (!$post) return;
      if ($a['class']) $rHtml = '<div class="qnrwp-included-post '.sanitize_html_class($a['class']).'">'.PHP_EOL;
      else $rHtml = '<div class="qnrwp-included-post">'.PHP_EOL;
      if ($a['layout'] == '') { // If no layout, return content
        $rHtml .= '<div class="qnrwp-included-post-content">'.apply_filters('the_content', $post['post_content']).'</div>'.PHP_EOL;
      } else {
        $layoutL = explode(',', trim($a['layout']));
        foreach ($layoutL as $layout) {
          if (trim($layout) == 'title') { // Provide our own filtering for title, date and excerpt
            $rHtml .= '<p class="qnrwp-included-post-title">'.esc_html(apply_filters('qnrwp_included_post_title', $post['post_title'])).'</p>'.PHP_EOL;
          } else if (trim($layout) == 'date') {
            $rHtml .= '<p class="qnrwp-included-post-date">'.esc_html(apply_filters('qnrwp_included_post_date', $post['post_date'])).'</p>'.PHP_EOL;
          } else if (trim($layout) == 'excerpt') { // Excerpt will be empty if not written for the post, but filter may be used
            $rHtml .= '<div class="qnrwp-included-post-excerpt">'.apply_filters('qnrwp_included_post_excerpt', $post['post_excerpt']).'</div>'.PHP_EOL;
          } else if (trim($layout) == 'content') {
            $rHtml .= '<div class="qnrwp-included-post-content">'.apply_filters('the_content', $post['post_content']).'</div>'.PHP_EOL;
          }
        }
      }
      $rHtml .= '</div>';
      return $rHtml;
    }
  }
  
  
  /**
   * Contact form shortcode definition
   * 
   * [contact-form]
   * 
   * Used for mail contact, and subscription without message/subject/name
   * 
   * Email will be sent from wordpress@domain.com
   * 
   * Reply-To header will be set to the user's email
   * 
   * The 'warnings' parameter controls display of max chars and IP under 
   *    message if message is used
   * 
   * Chars not valid in HTML attributes, <>'"&, will throw an Exception
   */
  public function contact_form($atts, $content = null) {
    // Construct default placeholder-subject
    // Attribute escaping done later TODO improve
    $subjectLine = (mb_strlen(get_bloginfo('name')) > 50) 
                          ? __('Enquiry from a website visitor', 'qnrwp') 
                          : sprintf(__('Enquiry from a %s website visitor', 'qnrwp'), esc_attr(sanitize_text_field(get_bloginfo('name'))));
    $a = shortcode_atts(array(
      'subject' => 'yes', // Subject field
      'message' => 'yes', // Message field
      'warnings' => 'yes', // Warnings under message
      'autofocus' => 'yes', // Autofocus in email or name input
      'name' => 'no', // Name field
      'title' => 'no',
      'intro' => 'no',
      'tooltips' => 'no',
      'title-text' => __('Contact Form', 'qnrwp'),
      'intro-text' => __('Send us a message and we will respond as soon as possible.', 'qnrwp'),
      'label-email' => __('Your email', 'qnrwp'),
      'label-name' => __('Your name', 'qnrwp'),
      'label-subject' => __('Subject', 'qnrwp'),
      'label-message' => __('Message', 'qnrwp'),
      'label-submit' => __('Send', 'qnrwp'),
      'placeholder-email' => __('you@domain.com', 'qnrwp'),
      'placeholder-name' => __('First Last', 'qnrwp'),
      'placeholder-subject' => $subjectLine,
      'placeholder-message' => '',
      'sent-reply' => __('Your message has been sent. You should receive a reply within 2 working days.', 'qnrwp'),
      'fail-reply' => __('Sorry, your message could not be sent.', 'qnrwp'),
      'form-class' => 'contact-form', // Should be unique to each form on page, hex of it will be used to id / block repeats
    ), $atts); // As a minimum, email field required, for subscribing
    // Don't accept values that would need escaping in HTML attributes, so no <>'"&
    foreach ($a as $key => $value) {
      if (esc_attr($value) != $value) 
          wp_die('ERROR: '. sprintf(__('Contact form parameter "%s" contains characters invalid in HTML attributes.', 'qnrwp'), esc_attr($key)));
      if (mb_strlen($value) > 100) 
          wp_die('ERROR: '. sprintf(__('Contact form parameter "%s" is over 100 characters long.', 'qnrwp'), esc_attr($key)));
    }
    if ($a['form-class'] == '' || mb_strlen($a['form-class']) > 40)
        wp_die('ERROR: '. sprintf(__('Contact form parameter "%s" must not be empty or over 40 characters long.', 'qnrwp'), 'form-class'));
    // Render the form
    if (!class_exists('QNRWP_Contact_Form')) 
        require_once QNRWP_DIR . 'inc/classes/class-qnrwp-contact-form.php';
    ob_start();
    QNRWP_Contact_Form::contact_form_render($a);
    return ob_get_clean();
  }
  
  
  /**
   * Menu shortcode definition
   * 
   * [menu name='menuName']
   */
  public function menu($atts, $content = null) {
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
  
  
  /**
   * Carousel shortcode definition
   * 
   * [carousel name="QNRWP-Widget-Carousel-1" size="large"]
   */
  public function carousel($atts, $content = null) {
    $a = shortcode_atts(array(
      'name' => '',
      'size' => 'large',
    ), $atts);
    $pages = get_pages(array('post_status' => 'private'));
    $rHtml = '';
    foreach ($pages as $page) {
      if ($page->post_title === $a['name']) {
        if (!class_exists('QNRWP_Widget_Custom')) {
          require_once QNRWP_DIR . 'inc/classes/class-qnrwp-widget-custom.php';
        }
        $rHtml = QNRWP_Widget_Custom::get_carousel_html($page->ID, $a['size']);
        break;
      }
    }
    return $rHtml;
  }
  
  
  /**
   * Samples shortcode definition
   * 
   * [samples categories="cat1, cat2, cat3"]
   */
  public function samples($atts, $content = null) {
    $a = shortcode_atts(array(
      'name' => 'Samples', // Title of the samples panel
      'size' => 'medium_large', // Image size to use
      'number' => 6, // For best display, let this be a multiple of 6 (divisible by 3 and 2)
      'categories' => 'sample-work', // Comma separated string of categories to show
    ), $atts);
    //$sCatsL = preg_split('/,\s+/', $a['categories']);
    if (!class_exists('QNRWP_Samples')) {
      require_once QNRWP_DIR . 'inc/classes/class-qnrwp-samples.php';
    }
    //QNRWP_Samples::get_samples_html($sampleName, $sampleCategories, $sampleSize, $samplesNumber, $pageNumber)
    $rHtml = QNRWP_Samples::get_samples_html(esc_attr($a['name']), esc_attr($a['categories']), esc_attr($a['size']), esc_attr($a['number']), 1);
    return $rHtml; // Could be empty
  }
  
  
} // End QNRWP_Shortcodes class


