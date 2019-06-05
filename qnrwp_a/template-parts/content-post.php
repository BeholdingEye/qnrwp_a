<?php
/**
 * content-post.php
 */

defined( 'ABSPATH' ) || exit;

$postClass = join(' ', get_post_class()).' '.get_post_field('post_name'); // Slug...

if (!is_singular()) $postClass .= ' post-on-news-page';

?>
<article id="post-<?php echo get_the_ID(); ?>" class="<?php echo apply_filters('qnrwp_content_post_post_class', $postClass); ?>">

  <div class="post-date"><?php echo get_the_date(); // We use "get_" version because "the_date()" is buggy (see note in "content-news_list.php") ?></div>
  <?php
  if (is_singular()):
    the_title('<h1 class="page-title ' . apply_filters('qnrwp_content_post_title_class', 'qnrwp-heading-dark'). '">', '</h1>');
  else:
    the_title('<h2 class="page-title ' . apply_filters('qnrwp_content_post_title_class', 'qnrwp-heading-dark'). '">', '</h2>');
  endif;
  
  // Place Featured Image at start of content
  if (QNRWP::get_setting('news-featured-image') == 1 && has_post_thumbnail(get_the_ID())) {
    $theContent = trim(get_the_content());
    if (strpos($theContent, '[featured-image') !== 0 && strpos($theContent, '[carousel') !== 0) {
      QNRWP_UI_Parts::render_post_thumbnail(get_the_ID());
    }
  }
  
  the_content();
  
  if (is_singular()):
  
  // Previous and Next post links
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
  <?php endif; ?>
</article>
