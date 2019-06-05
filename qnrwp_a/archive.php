<?php
/**
 * archive.php
 */

defined( 'ABSPATH' ) || exit;

$GLOBALS['QNRWP_GLOBALS']['pageTemplate'] = 'archive.php';

$queriedObject = get_queried_object(); // May be null, for date at least

get_header(); // Includes start of content; sets our global

get_sidebar('subheader'); ?>

<!-- Middle Row -->
<section id="middle-row" class="middle-row">
  <?php 

  get_sidebar('left'); ?>
  
  <!-- Content Box -->
  <main id="content-box" class="<?php echo QNRWP_UI_Parts::get_content_box_classes(); // Will contain 'content-box' plus sidebar-dependent layout classes ?>">

    <?php 
    if ($queriedObject && ($queriedObject->post_type == 'qnrwp_sample_card' || $queriedObject->name == 'qnrwp_sample_card' || $queriedObject->query_var == 'qnrwp_sample_card')):
      // TODO subheader will show "News" page title... fix is low priority
      $sampleCardsArchive = QNRWP_Samples::get_samples_html('Samples', 'medium_large', 6, 1);
      if (!$sampleCardsArchive) echo '<div class="search-results">'.esc_html__('No sample cards to show.', 'qnrwp').'</div>';
      else echo $sampleCardsArchive;
    
    elseif (!have_posts()): ?>
    
      <div class="search-results"><?php esc_html_e('No news posts found for this query.', 'qnrwp'); ?></div>
      
    <?php
    
    elseif (have_posts()):
    
      if (is_date()):
        // Arrange the date in "month day, year" format
        add_filter('wp_title_parts', function($args) {
          if (count($args) == 3) $args = array($args[1], ltrim($args[2], '0') . ',', $args[0]);
          elseif (count($args) == 2) $args = array($args[1], $args[0]);
          return $args;
        });
      ?>
        <div class="search-results"><?php echo esc_html__('News posts for:', 'qnrwp').' '.wp_title('', $display=false); ?></div>
      <?php elseif (is_tag()): ?>
        <div class="search-results"><?php echo esc_html__('News posts tagged with:', 'qnrwp').' <em>'.single_tag_title('', false).'</em>'; ?></div>
      <?php elseif (is_category()): ?>
        <div class="search-results"><?php echo esc_html__('News posts in category:', 'qnrwp').' <em>'.single_cat_title('', false).'</em>'; ?></div>
      <?php elseif (is_tax()): ?>
        <div class="search-results"><?php echo esc_html__('News posts tagged with:', 'qnrwp').' <em>'.single_term_title('', false).'</em>'; ?></div>
      <?php elseif (is_author()): // Author won't have null $queriedObject ?>
        <div class="search-results"><?php echo esc_html__('News posts by:', 'qnrwp').' <em>'.$queriedObject->data->display_name.'</em>'; ?></div>
      <?php endif;
    
      while (have_posts()): 
  
        the_post();
      
        if (get_option('qnrwp_news_page_items') == 1) get_template_part('template-parts/content', 'post');
        
        else get_template_part('template-parts/content', 'news_list');
        
      endwhile;
      
      get_template_part('template-parts/content', 'news_list_links');
      
    endif; ?>  

  </main><!-- End of Content Box -->

  <?php 

  get_sidebar(); // Generic dummy for template-overriding plugins such as WooCommerce
  
  get_sidebar('right'); ?>
  
</section><!-- End of Middle Row -->
<?php 

get_sidebar('subcontent');

get_footer(); // Includes end of content
