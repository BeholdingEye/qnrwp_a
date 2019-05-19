<?php
/**
 * content-news_list.php
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- Post Item Excerpt -->
<?php $postLink = get_the_permalink(get_the_ID()); ?>
<a href="<?php echo $postLink; ?>" id="qnrwp-excerpt-<?php echo get_the_ID(); ?>" class="qnrwp-excerpt-item"><?php 
if (has_post_thumbnail()) {
  echo get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'qnrwp-post-featured-img')); // No whitespace
}
?><div class="qnrwp-excerpt-item-block">
    <p class="qnrwp-excerpt-date"><?php 
    // We use "get_" version because "the_date()" is buggy, does not fetch the date on all posts in a list of posts published on the same date
    echo get_the_date();
    ?></p>
    <h1><?php the_title(); ?></h1>
    <div class="qnrwp-excerpt-text">
      <?php $htmlExcerpt = apply_filters('the_excerpt', get_the_excerpt());
        if (!$htmlExcerpt) $htmlExcerpt = '<p>'.esc_html__('Click here to see this post.', 'qnrwp').'</p>';
        echo $htmlExcerpt;
?></div></div></a><!-- No whitespace -->
