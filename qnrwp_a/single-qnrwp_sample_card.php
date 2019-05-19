<?php
/**
 * single-qnrwp_sample_card.php
 */

defined( 'ABSPATH' ) || exit;

$GLOBALS['QNRWP_GLOBALS']['pageTemplate'] = 'single-qnrwp_sample_card.php';

get_header(); // Includes start of content; sets our global

get_sidebar('subheader'); ?>

<!-- Middle Row -->
<section id="middle-row" class="middle-row">
  <?php 

  get_sidebar('left'); ?>

  <!-- Content Box -->
  <main id="content-box" class="<?php echo QNRWP_UI_Parts::get_content_box_classes(); // Will contain 'content-box' plus sidebar-dependent layout classes ?>">

    <!-- Samples Row -->
    <div class="qnrwp-samples-row">
      <h2 class="qnrwp-samples-list-title"><?php esc_html_e('Sample Card', 'qnrwp'); // Place before the block ?></h2>
      
      <!-- Samples List -->
      <div class="qnrwp-samples-list-block">
        <?php if (have_posts()): while (have_posts()): 
        
          the_post(); 
          
          get_template_part('template-parts/content', 'qnrwp_sample_card');
          
        endwhile; endif; ?>
      </div><!-- End of Samples List -->
    </div><!-- End of Samples Row -->

  </main><!-- End of Content Box -->

  <?php 

  get_sidebar(); // Generic dummy for template-overriding plugins such as WooCommerce

  get_sidebar('right'); ?>

</section><!-- End of Middle Row -->
<?php 

get_sidebar('subcontent');

get_footer(); // Includes end of content

