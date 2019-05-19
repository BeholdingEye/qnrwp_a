<?php

defined( 'ABSPATH' ) || exit;

/**
 * News setup class (a singleton)
 */
final class QNRWP_News {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks related to news
   */
  private function hooks() {
    // Customize the search form on news pages
    add_filter('get_search_form', array($this, 'search_form'));
    
    // Excerpt
    add_filter('excerpt_more', array($this, 'excerpt_more_filter'));
    add_filter('excerpt_length', array($this, 'custom_excerpt_length'), 999);
    add_filter('get_the_excerpt', array($this, 'get_pretty_excerpt'));
    
    // News categories
    //add_action('pre_get_posts', array($this, 'main_query_filter'));
    //add_filter('widget_posts_args', array($this, 'recent_posts_widget_args'));
    
    // News post filter
    add_filter('the_content', array($this, 'filter_news_post'), 999); // Run late for complete HTML after shortcodes
    
    // Archives widget filters
    //add_filter('getarchives_where', array($this, 'customarchives_where'));
    //add_filter('getarchives_join', array($this, 'customarchives_join'));
    
    // Calendar widget filters
    //add_filter('query', array($this, 'calendar_news_prev_next'));
    //add_filter('day_link', array($this, 'day_link_filter'), 10, 4);
  }
  
  
  /**
   * Customizes the search form on news pages
   */
  public function search_form($form) {  
    $form = preg_replace('@\s+<span class="screen-reader-text">[^<]+</span>@i', '', $form);
    $form = preg_replace('@\s+<input type="submit" class="search-submit" value="[^\"]+" />@i', 
                              '<input type="hidden" value="post" name="post_type" id="post_type" />' // Add post type filter for News only
                              .'<input type="submit" class="search-submit" value="g" />', $form); // No whitespace
    $form = preg_replace('@Search &hellip;@i', esc_attr__('Search news', 'qnrwp').'&hellip;', $form);
    $form = preg_replace('@\s+</?label>@i', '', $form);
    return $form;
  }
  
  
  /**
   * Excerpt 'read more' filter
   */
  public function excerpt_more_filter($moreStr) {
    return '...';
  }
  
  
  /**
   * Filters parameters of main Loop query to get news posts only DISABLED
   */
  //public function main_query_filter($query) {
    //if ($query->is_main_query() && !is_page() && !is_admin()) { // Not in Admin screens...
      //$query->set('category_name', 'news,uncategorized');
    //}
  //}
  
  
  /**
   * Filters Recent Posts widget args to get news posts only DISABLED
   */
  //public function recent_posts_widget_args($args) {
    //$args['category_name'] = 'news,uncategorized';
    //return $args;
  //}
  
  
  /**
   * First of two functions filtering archives call to return news posts only DISABLED
   */
  //public function customarchives_where($sql) {
    //global $wpdb;
    //$include = qnrwp_get_news_categories_ids();
    //return $sql . " AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN ($include)";
  //}
  
  
  /**
   * Second of two functions filtering archives call to return news posts only DISABLED
   */
  //public function customarchives_join($sql) {
    //global $wpdb;
    //return $sql . " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
  //}
  
  
  /**
   * Filters next prev and day links in caledar widget for news posts only, by filtering the SQL query, as no higher level method available DISABLED
   */
  //public function calendar_news_prev_next($sql) {
    //if (isset($GLOBALS['QNRWP_GLOBALS']['widgetBeingShown']) && $GLOBALS['QNRWP_GLOBALS']['widgetBeingShown'] == 'calendar') { // Set in our widgets class to save CPU cycles
      //global $wpdb;
      //$prevQ = "SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
        //FROM $wpdb->posts
        //WHERE post_date < '\d+-\d+-01'
        //AND post_type = 'post' AND post_status = 'publish'
          //ORDER BY post_date DESC
          //LIMIT 1";
      //$nextQ = "SELECT MONTH(post_date) AS month, YEAR(post_date) AS year
        //FROM $wpdb->posts
        //WHERE post_date > '\d+-\d+-\d+ 23:59:59'
        //AND post_type = 'post' AND post_status = 'publish'
          //ORDER BY post_date ASC
          //LIMIT 1";
      //$dayQ = "SELECT DISTINCT DAYOFMONTH(post_date)
        //FROM $wpdb->posts WHERE post_date >= '\d+-\d+-01 00:00:00'
        //AND post_type = 'post' AND post_status = 'publish'
        //AND post_date <= '\d+-\d+-\d+ 23:59:59'";
      //$prevQ = preg_replace('/\s\s+/', ' ', $prevQ);
      //$nextQ = preg_replace('/\s\s+/', ' ', $nextQ);
      //$dayQ = preg_replace('/\s\s+/', ' ', $dayQ);
      //$sql = preg_replace('/\s\s+/', ' ', $sql);
      //$prevQ = preg_replace('/\(/', '\\(', $prevQ);
      //$prevQ = preg_replace('/\)/', '\\)', $prevQ);
      //$nextQ = preg_replace('/\(/', '\\(', $nextQ);
      //$nextQ = preg_replace('/\)/', '\\)', $nextQ);
      //$dayQ = preg_replace('/\(/', '\\(', $dayQ);
      //$dayQ = preg_replace('/\)/', '\\)', $dayQ);
      //if (preg_match('/'.$prevQ.'/', $sql) || preg_match('/'.$nextQ.'/', $sql) || preg_match('/'.$dayQ.'/', $sql)) {
        //$join = "INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
        //$catsIDs = qnrwp_get_news_categories_ids();
        //$where = "AND $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN ($catsIDs)";
        //$sql = preg_replace('/(WHERE post_date)/', $join . ' $1', $sql);
        //$sql = preg_replace('/(AND post_type = \'post\' AND post_status = \'publish\')/', '$1 ' . $where, $sql);
      //}
    //}
    //return $sql;
  //}
  
  
  /**
   * Excerpt length filter
   */
  public function custom_excerpt_length($length) {
    $rN = 35; // On average there are 5 characters per word
    if (isset($GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'])) // Global required by custom callers as well...
      $rN = intval(($GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength']/5) + 5);
    return $rN;
  }
  
  
  /**
   * Returns first paragraph from HTML content, as classed by content filter, called statically
   */
  public static function get_news_first_para_excerpt() {
    global $post;
    // Remove HTML/PHP tags
    $content = strip_tags($post->post_content);
    // Remove shortcodes
    // First convert double brackets to safe strings
    $content = preg_replace('/\[\[/', '<<<qnrwp_gnfpe_s<<<', $content);
    $content = preg_replace('/\]\]/', '>>>qnrwp_gnfpe_e>>>', $content);
    $content = preg_replace('/\s*\[[^\]]*\]\s*/', ' ', $content);
    $content = preg_replace('/<<<qnrwp_gnfpe_s<<</', '[', $content);
    $content = preg_replace('/>>>qnrwp_gnfpe_e>>>/', ']', $content);
    $cL = preg_split('/\s*\n\s*\n+/', $content);
    if (isset($cL) && !empty($cL)) {
      $rT = trim($cL[0]);
      // Keep it sane, reduce to <= 255 chars
      if (mb_strlen($rT) > 255) {
        $GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'] = 255;
        $rT = self::get_pretty_excerpt($rT);
        // Reset to usual 110 for excerpts
        $GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'] = 110;
      }
      return $rT;
    }
    else return '';
  }

  
  /**
   * Returns pretty excerpt
   * 
   * Used in both excerpt hook in this class and custom calls
   */
  public function get_pretty_excerpt($excerpt) {
    // Reduce length to max 110 chars
    $excerptDecode = wp_kses_decode_entities($excerpt); // Decode numerical entities, only for counting
    $eLen = 110;
    // Consider the global limit (for the sake of generic use in meta description)
    if (isset($GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'])) $eLen = $GLOBALS['QNRWP_GLOBALS']['MaxExcerptLength'];
    if (mb_substr($excerptDecode, 0, $eLen) == $excerptDecode) 
          return $excerpt;
    if (mb_substr($excerptDecode, 0, $eLen) !== mb_substr($excerpt, 0, $eLen)) {
      $eLen += mb_strlen($excerpt) - mb_strlen($excerptDecode);
    }
    $excerpt = trim(mb_substr($excerpt, 0, $eLen)); // Limit to 110 chars
    // Delete last, possibly truncated word
    $excerpt = preg_replace('/(\S+)\s+\S*$/', '$1', $excerpt);
    // Remove single closing word after sentence
    $excerpt = preg_replace('/([.!?]+)\s+\S*$/', '$1', $excerpt);
    // Remove closing punctuation (not including ; as it may end a char entity)
    $excerpt = preg_replace('/[,:.!?-]+$/', '', $excerpt);
    return $excerpt . '...';
  }
  
  
  /**
   * Sets CSS class on first paragraph of news post and makes images clickable
   */
  public function filter_news_post($content) {
    // Test if inside the main loop, a news post
    // Test for 'is_single()' removed, so that self::get_news_first_para_excerpt() will work (NO, reinstated)
    //if (is_single() && in_the_loop() && is_main_query() && get_post_type() == 'post' 
                    //&& !is_admin() && in_category(array('news', 'uncategorized'))) { // Disabled category filtering
    if (is_single() && in_the_loop() && is_main_query() && get_post_type() == 'post' && !is_admin()) {
      
      // ----------------------- CSS first paragraph markup for excerpts
      // Class first paragraph as "news-post-first-para", perhaps after featured image possibly wrapped in A and P tags
      // Won't work if a carousel precedes the first paragraph, but that will be caught by CSS
      //qnrwp_debug_printout($content, $append=false);
      $gP = '/^(\s*((<p>\s*<a[^>]+>\s*<img[^>]+>\s*<\/a>\s*<\/p>)|(<img[^>]+>))?\s*<p)>/';
      $content = preg_replace($gP, '$1 class="news-post-first-para">', $content);
      
      // ----------------------- Clickable images
      // Add an "expand image" overlay (for external links; for image links, see last regex)
      $gP = '/(<a [^>]+)(>\s*<img[^>]+>\s*<\/a>)/';
      $content = preg_replace($gP, '$1 class="news-post-expandable-image"$2', $content);
      // Transfer alignleft class from img to a
      $gP = '/(<a [^>]*class=")([^>]+>\s*<img[^>]+)\s*alignleft([^>]+>\s*<\/a>)/';
      $content = preg_replace($gP, '$1alignleft $2$3', $content);
      // Transfer alignright class from img to a
      $gP = '/(<a [^>]*class=")([^>]+>\s*<img[^>]+)\s*alignright([^>]+>\s*<\/a>)/';
      $content = preg_replace($gP, '$1alignright $2$3', $content);
      // Transfer aligncenter class from img to a
      $gP = '/(<a [^>]*class=")([^>]+>\s*<img[^>]+)\s*aligncenter([^>]+>\s*<\/a>)/';
      $content = preg_replace($gP, '$1aligncenter $2$3', $content);
      // Copy width from img to a
      $gP = '/(<a [^>]+)(>\s*<img[^>]+width=")([^\"]+)("[^>]*>\s*<\/a>)/';
      $content = preg_replace($gP, '$1 style="width:$3px;"$2$3$4', $content);
      // Add image link class (for different expansion icon) if linking to an image (note the order href/class)
      $gP = '/(<a [^>]*href="[^\"]+(?:\.png|\.svg|\.jpg|\.jpeg|\.gif)"[^>]+class="[^\"]+)(")/i';
      $content = preg_replace($gP, '$1 news-post-image-link$2', $content);
    }
    return $content;
  }

} // End QNRWP_News class
