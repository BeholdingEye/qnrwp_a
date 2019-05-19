<?php
/**
 * content-post.php
 */

defined( 'ABSPATH' ) || exit;

$postClass = join(' ', get_post_class()).' '.get_post_field('post_name'); // Slug... ?>

<div id="post-<?php echo get_the_ID(); ?>" class="<?php echo apply_filters('qnrwp_content_post_post_class', $postClass); ?>">

  <div class="post-date"><?php echo get_the_date(); // We use "get_" version because "the_date()" is buggy (see note in "content-news_list.php") ?></div>
  <h1 class="page-title"><?php the_title(); ?></h1>
  
  <?php 
    // Place Featured Image at start of content
    $postFeaturedImageTag = '';
    if (QNRWP::get_setting('news-featured-image') == 1) {
      $theContent = trim(get_the_content());
      if (strpos($theContent, '[featured-image') !== 0 && strpos($theContent, '[carousel') !== 0) {
        QNRWP_UI_Parts::render_post_thumbnail(get_the_ID());
      }
    } ?>
  
  <?php the_content();
  
  // Previous and Next post links TODO refactor??
  $prevPostLink = get_previous_post_link( $format = '%link', 
                                          $link = '&laquo;&nbsp;%title', 
                                          $in_same_term = false
                                          //$excluded_terms = $exCats, 
                                          //$taxonomy = 'category'
                                          );
                                          
  $nextPostLink = get_next_post_link(     $format = '%link', 
                                          $link = '%title&nbsp;&raquo;', 
                                          $in_same_term = false
                                          //$excluded_terms = $exCats, 
                                          //$taxonomy = 'category'
                                          ); ?>
  <div class="post-nav-links">
    <span class="post-older"><p><?php esc_html_e('Previous', 'qnrwp'); ?></p><p><?php echo $prevPostLink; ?></p></span><span class="post-newer"><p><?php esc_html_e('Next', 'qnrwp'); ?></p><p><?php echo $nextPostLink; ?></p></span><!-- No whitespace -->
  </div>

</div>
