<?php
/**
 * content-page.php
 */

defined( 'ABSPATH' ) || exit;

$postClass = join(' ', get_post_class()).' '.get_post_field('post_name'); // Slug... ?>

<div id="post-<?php echo get_the_ID(); ?>" class="<?php echo apply_filters('qnrwp_content_page_post_class', $postClass); ?>">

  <?php the_content(); ?>

</div>
