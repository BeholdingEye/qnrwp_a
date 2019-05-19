<?php
/**
 * 404.php
 */

defined( 'ABSPATH' ) || exit;

$GLOBALS['QNRWP_GLOBALS']['pageTemplate'] = '404.php';

get_header(); // Includes start of content; sets our global

get_sidebar('subheader'); ?>

<!-- Middle Row -->
<section id="middle-row" class="middle-row">
  <?php 

  get_sidebar('left'); ?>
  
  <!-- Content Box -->
  <main id="content-box" class="<?php echo QNRWP_UI_Parts::get_content_box_classes(); // Will contain 'content-box' plus sidebar-dependent layout classes ?>">
    
    <div class="qnrwp-page-not-found-notice center" style="font-size:1.6em;line-height:1.4em;padding:2em 2em 10em;">
      <?php esc_html_e('Sorry, the page you are looking for could not be found.', 'qnrwp'); ?>
    </div>
    
  </main><!-- End of Content Box -->

  <?php

  get_sidebar(); // Generic dummy for template-overriding plugins such as WooCommerce

  get_sidebar('right'); ?>
  
</section><!-- End of Middle Row -->

<?php 

get_sidebar('subcontent');

get_footer(); // Includes end of content
