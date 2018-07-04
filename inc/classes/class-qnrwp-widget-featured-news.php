<?php

defined( 'ABSPATH' ) || exit;


/**
 * Featured News widget definition
 */
class QNRWP_Widget_Featured_News extends WP_Widget {
  
  /**
   * Instantiate the parent object
   */
	public function __construct() {
		// Instantiate the parent object
		$widget_ops = array( 
			'classname'   => 'qnrwp_widget_featured_news',
			'description' => esc_html__('Excerpts of 4 latest News posts, with Featured Images, to appear as cards in a row.', 'qnrwp'),
		);
		parent::__construct('qnrwp_widget_featured_news', 'QNRWP Featured News', $widget_ops);
	}
  
  
  /**
   * Constructs Featured News HTML and renders it
   */
	public function widget($args, $instance) {
    // ----------------------- Custom Query
    $the_query = new WP_Query(array('post_type' => 'post', 'nopaging' => true));
    if ($the_query->have_posts()) {
      $featuredCount = 0;
      $rHtml = '<!-- Featured News -->'.PHP_EOL;
      $rHtml .= '<div class="qnrwp-featured-news-block">'.PHP_EOL; // Opening Featured News block
      $rHtml .= '<div>'.PHP_EOL; // Opening of first of two item-of-two DIVs
      // ----------------------- The Loop
      while ($the_query->have_posts()) {
        $the_query->the_post();
        //if (in_category(array('news', 'uncategorized')) && has_post_thumbnail()) { // Disabled category filtering
        if (has_post_thumbnail()) {
          //$thumbHtml = get_the_post_thumbnail(get_the_ID(), 'medium');
          //$thumbUrl = qnrwp_get_post_thumbnail_url($thumbHtml);
          $thumbUrl = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'medium');
          if (is_ssl()) $thumbUrl = set_url_scheme($thumbUrl, 'https'); // Convert to HTTPS if used
          $postLink = get_the_permalink(get_the_ID());
          $rHtml .= '<a class="qnrwp-featured-news-item" href="'.$postLink.'">'.PHP_EOL; // Opening item
          $rHtml .= '<div class="qnrwp-featured-news-item-header" style="background-image:url(\''.$thumbUrl.'\')">&nbsp;</div>'.PHP_EOL;
          $rHtml .= '<div class="qnrwp-featured-news-item-text">'.PHP_EOL;
          $rHtml .= '<h1>'.get_the_title().'</h1>'.PHP_EOL;
          $GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'] = 115; // TODO ??
          $rHtml .= '<div class="qnrwp-featured-news-item-excerpt">'.PHP_EOL.get_the_excerpt().PHP_EOL.'</div>'.PHP_EOL;
          $GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'] = 110;
          $rHtml .= '</div>'.PHP_EOL.'</a>'.PHP_EOL; // Closing item
          $featuredCount += 1;
          if ($featuredCount == 2) $rHtml .= '</div><div><!-- No whitespace -->'.PHP_EOL; // Close first item-of-two, open next
        }
        if ($featuredCount == 4) break;
      }
      $rHtml .= '</div>'.PHP_EOL.'</div><!-- End of Featured News -->'.PHP_EOL; // Closing second item-of-two and Featured News block
      if ($featuredCount == 4) echo $rHtml; // Echo nothing if 4 Featured News items not obtained
      
      wp_reset_postdata(); // Restore original Post Data
    } // Else no posts found, do nothing
	}
  
  
  /**
   * Output widget admin options form
   */
	public function form($instance) {
    echo '<p>'.esc_html__('Excerpts of 4 latest News posts, with Featured Images, to appear as cards in a row.', 'qnrwp').'</p>';
	}
  
} // End QNRWP_Widget_Featured_News class

