<?php
/**
 * content-post.php
 */

$postClass = 'class="' . join(' ', get_post_class()).' '.get_post_field('post_name').'"'; // Slug... ?>

<div id="post-<?php echo get_the_ID(); ?>" <?php echo $postClass; ?>>

  <div class="post-date"><?php the_date(); ?></div>
  <h1 class="page-title"><?php the_title(); ?></h1>
  
  <?php 
    // Place Featured Image at start of content
    $postFeaturedImageTag = '';
    if (isset(get_option('qnrwp_settings_array')['news-featured-image']) && get_option('qnrwp_settings_array')['news-featured-image'] == 1) {
      $theContent = trim(get_the_content());
      if (strpos($theContent, '[featured-image') !== 0 && strpos($theContent, '[carousel') !== 0) {
        the_post_thumbnail(get_the_ID(), 'large');
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
