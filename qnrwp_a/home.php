<?php
/**
 * home.php
 */

defined( 'ABSPATH' ) || exit;

$GLOBALS['QNRWP_GLOBALS']['pageTemplate'] = 'home.php';

get_header(); // Includes start of content; sets our global

get_sidebar('subheader'); ?>

<!-- Middle Row -->
<section id="middle-row" class="middle-row">
  <?php 

  get_sidebar('left'); ?>
  
  <!-- Content Box -->
  <main id="content-box" class="<?php echo QNRWP_UI_Parts::get_content_box_classes(); // Will contain 'content-box' plus sidebar-dependent layout classes ?>">

  <?php 
  
  if (have_posts()): 
  
    while (have_posts()): 
    
      the_post();
      
      if (get_option('qnrwp_news_page_items') == 1) get_template_part('template-parts/content', 'post');
      
      else get_template_part('template-parts/content', 'news_list');
    
    endwhile;
    
    get_template_part('template-parts/content', 'news_list_links');
  
  else:
  
    get_template_part('template-parts/content', 'none');
    
  endif; ?>  

  </main><!-- End of Content Box -->

  <?php 

  get_sidebar(); // Generic dummy for template-overriding plugins such as WooCommerce
  
  get_sidebar('right'); ?>
  
</section><!-- End of Middle Row -->
<?php 

get_sidebar('subcontent');

get_footer(); // Includes end of content
