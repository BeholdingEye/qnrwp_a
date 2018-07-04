<?php
/**
 * content-page.php
 */

$postClass = 'class="' . join(' ', get_post_class()).' '.get_post_field('post_name').'"'; // Slug... ?>

<div id="post-<?php echo get_the_ID(); ?>" <?php echo $postClass; ?>>

  <?php the_content(); ?>

</div>
