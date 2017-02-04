<?php

// ===================== The main template file =====================

// -----------------------  Error Handler

error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Change default PHP config

function exception_error_handler($severity, $message, $file, $line) {
  if (!(error_reporting() & $severity)) {
    // This error code is not included in error_reporting
    return;
  }
  throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('exception_error_handler');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title(''); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
  </head>
  <body onload="afterLoad()">    
  <?php 
    try {
      // Show in source that we're reporting errors
      echo '<!-- Error reporting: ' . error_reporting().' -->'.PHP_EOL;
      
      // ----------------------- Nav Menu (not used)
      //wp_nav_menu(array(  'theme_location' => 'test-menu',
                          //'container_class' => 'test-menu-container',
                          //));
      
      // ----------------------- Main Content Construction
      
      if (is_active_sidebar('qnrwp-row-2')) {
        // Layout: 'single', 'left-sidebar', 'right-sidebar', 'three-cols'
        $layout = 'single';
        $rHtml = '';
        $postsAmount = 'multi';
        $isNews = false;
        
        // ----------------------- The Loop (page/post content and meta)
        if (have_posts()) {
          while (have_posts()) {
            the_post(); // Must be here, for correct sequence of posts and their thumbs
            
            // ----------------------- Singular types: single post, page, attachment
            if (is_singular()) {
              $postsAmount = 'single';
              // Set layout
              if (get_post_type() == 'page') $layout = 'single';
              // If not Page, single Posts are laid out same as multi-item pages
              else if (is_active_sidebar('qnrwp-sidebar-1') && is_active_sidebar('qnrwp-sidebar-2')) $layout = 'three-cols';
              else if (is_active_sidebar('qnrwp-sidebar-1')) $layout = 'left-sidebar';
              else if (is_active_sidebar('qnrwp-sidebar-2')) $layout = 'right-sidebar';
              $postClass = 'class="' . join(' ', get_post_class()) . '"';
              // Start constructing return HTML
              $rHtml = '<div id="post-'.get_the_ID().'" '.$postClass.'>'.PHP_EOL; // Open singular
              // Get the date, if type is post
              if (get_post_type() == 'post') $rHtml .= '<div class="post-date">'.get_the_date().'</div>'.PHP_EOL;
              if (!is_front_page()) $rHtml .= '<h1 class="page-title">'.get_the_title().'</h1>'.PHP_EOL; // Not on Home
              $htmlContent = apply_filters('the_content', get_the_content());
              $rHtml .= $htmlContent;
              if (get_post_type() == 'post') {
                $isNews = true;
                // ----------------------- Construct Previous and Next links
                // First, obtain non- News/Uncategorized categories, to exclude them
                $exCats = array();
                $categories = get_categories(array('orderby' => 'name', 'parent' => 0)); // Don't consider sub-categories
                foreach ($categories as $category) {
                  if ($category->slug != 'news' && $category->slug != 'uncategorized') {
                    array_push($exCats, $category->cat_ID);
                  }
                }
                $prevPostLink = get_previous_post_link( $format = '&laquo;&nbsp;%link', 
                                                        $link = '%title', 
                                                        $in_same_term = false, 
                                                        $excluded_terms = $exCats, 
                                                        $taxonomy = 'category');
                                                        
                $nextPostLink = get_next_post_link(     $format = '%link&nbsp;&raquo;', 
                                                        $link = '%title', 
                                                        $in_same_term = false, 
                                                        $excluded_terms = $exCats, 
                                                        $taxonomy = 'category');
                // Previous and Next post links
                $rHtml .= '<div class="post-nav-links">'.PHP_EOL;
                $rHtml .= '<span class="post-older">'.$prevPostLink.'</span>'; // No whitespace
                $rHtml .= '<span class="post-newer">'.$nextPostLink.'</span>'.PHP_EOL;
                $rHtml .= '</div>'.PHP_EOL;
              }
              $rHtml .= '</div>'.PHP_EOL; // Close singular
            }
            
            // ----------------------- Multi-item page, blog/archive/category (not testimonials)
            else if (!in_category('testimonial')) { // Test no longer necessary due to filter, but kept for info
              $isNews = true;
              // Set layout
              if (is_active_sidebar('qnrwp-sidebar-1') && is_active_sidebar('qnrwp-sidebar-2')) $layout = 'three-cols';
              else if (is_active_sidebar('qnrwp-sidebar-1')) $layout = 'left-sidebar';
              else if (is_active_sidebar('qnrwp-sidebar-2')) $layout = 'right-sidebar';
              // Construct return HTML
              $rHtml .= '<!-- Post Item Excerpt -->'.PHP_EOL; // Concatenate multiple items
              $postLink = get_the_permalink(get_the_ID());
              $rHtml .= '<a href="'.$postLink.'" id="excerpt-'.get_the_ID().'" class="excerpt-item">'.PHP_EOL; // Open item
              if (has_post_thumbnail()) {
                $rHtml .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'qnrwp-post-featured-img')); // No whitespace
              }
              $rHtml .= '<div class="excerpt-item-block">'.PHP_EOL;
              $rHtml .= '<p class="excerpt-date">'.get_the_date().'</p>'.PHP_EOL;
              $rHtml .= '<h1>'.get_the_title().'</h1>'.PHP_EOL;
              $rHtml .= '<div class="excerpt-text">'.PHP_EOL;
              $htmlExcerpt = apply_filters('the_excerpt', get_the_excerpt());
              if (!$htmlExcerpt) $htmlExcerpt = '<p>Click here to see this post.</p>';
              $rHtml .= $htmlExcerpt;
              // Custom meta values for the post
              // WP has tricky meta functions with multi-dimensional arrays,
              //   we use a relatively simple one
              $key_values = get_post_custom_values('Price'); // Simple array, may be empty
              if ($key_values) {
                foreach ($key_values as $key => $value) {
                  $rHtml .= '<p>Price: '.$value.' </p>'.PHP_EOL; 
                }
              }
              $rHtml .= '</div></div></a>'.PHP_EOL; // Close item
            }
          }
          if ($postsAmount == 'multi') {
            // Previous and Next posts pages links
            $rHtml .= '<div class="excerpts-pages-links">'.PHP_EOL;
            $nextPostsLink = get_next_posts_link('Older');
            $prevPostsLink = get_previous_posts_link('Newer');
            if ($nextPostsLink) { // Test so that we're not showing isolated &laquo;
              $nextPostsLink = '&laquo;&nbsp;'.$nextPostsLink;
            }
            if ($prevPostsLink) {
              $prevPostsLink = $prevPostsLink.'&nbsp;&raquo;';
            }
            $rHtml .= '<span class="excerpts-older">'.$nextPostsLink.'</span>'; // No whitespace
            $rHtml .= '<span class="excerpts-newer">'.$prevPostsLink.'</span>'.PHP_EOL;
            $rHtml .= '</div>'.PHP_EOL;
          }
        }
        if (is_search() && $rHtml == '') { // Nothing found
          $isNews = true;
          // Set layout, as for multi
          if (is_active_sidebar('qnrwp-sidebar-1') && is_active_sidebar('qnrwp-sidebar-2')) $layout = 'three-cols';
          else if (is_active_sidebar('qnrwp-sidebar-1')) $layout = 'left-sidebar';
          else if (is_active_sidebar('qnrwp-sidebar-2')) $layout = 'right-sidebar';
          $rHtml = '<div class="search-no-results">No news posts found for this search.</div>'.PHP_EOL;
        }
      }
      
      // ----------------------- PAGE LAYOUT
      
      // ----------------------- Header Row
      
      if (is_active_sidebar('qnrwp-row-1')) {
        echo '<!-- Header Row -->'.PHP_EOL;
        echo '<div id="header-row" class="header-row widget-area" role="complementary">' .PHP_EOL;
        dynamic_sidebar('qnrwp-row-1'); // Navigation menu & intro header
        echo '</div><!-- End of Header -->'.PHP_EOL;
      }
      
      echo '<!-- Middle Row -->'.PHP_EOL.'<div id="middle-row">'.PHP_EOL; // Open row of content & sidebars
      if ($isNews) { // Create News header
        $newsHeaderURL = trailingslashit(get_template_directory_uri()) . 'res/img/news-reader_toa-heftiba.jpg';
        echo '<div class="qnr-scroller" style="background-image:url(\''.$newsHeaderURL.'\');">'.PHP_EOL;
        echo '<div><p>News</p></div></div>'.PHP_EOL;
      }
      
      // ----------------------- Left Sidebar
      
      if (($layout == 'three-cols' || $layout == 'left-sidebar') && is_active_sidebar('qnrwp-sidebar-1')) {
        echo '<!-- Left Sidebar -->'.PHP_EOL;
        echo '<div id="sidebar-left" class="sidebar sidebar-left widget-area" role="complementary">' .PHP_EOL;
        dynamic_sidebar('qnrwp-sidebar-1'); // Usually WP blogging widgets
        echo '</div><!-- End of Left Sidebar -->'.PHP_EOL;
      }
      
      // ----------------------- Main Content
      
      if (is_active_sidebar('qnrwp-row-2')) {
        echo '<!-- Content -->'.PHP_EOL;
        // Adjust Content classes, accounting for sidebars
        $contentBoxClass = 'content-box widget-area';
        if ($layout == 'three-cols') $contentBoxClass .= ' three-col-content';
        else if ($layout == 'left-sidebar') $contentBoxClass .= ' two-col-content-right';
        else if ($layout == 'right-sidebar') $contentBoxClass .= ' two-col-content-left';
        else if ($layout == 'single') $contentBoxClass .= ' single-col-content';
        echo '<div id="content-box" class="'.$contentBoxClass.'" role="complementary">'.PHP_EOL;
        
        //echo $rHtml; // Content
        $GLOBALS['contentHtml'] = $rHtml; // Place in global for widget to echo
        wp_reset_postdata(); // Restore original Post Data
        
        dynamic_sidebar('qnrwp-row-2');
        
        echo '</div><!-- End of Content -->'.PHP_EOL;
      }
      
      // ----------------------- Right Sidebar
      
      if (($layout == 'three-cols' || $layout == 'right-sidebar') && is_active_sidebar('qnrwp-sidebar-2')) {
        echo '<!-- Right Sidebar -->'.PHP_EOL;
        echo '<div id="sidebar-right" class="sidebar sidebar-right widget-area" role="complementary">'.PHP_EOL;
        dynamic_sidebar('qnrwp-sidebar-2'); // Usually WP blogging widgets
        echo '</div><!-- End of Right Sidebar -->'.PHP_EOL;
      }
      
      echo '</div><!-- End of Middle Row -->'.PHP_EOL; // Close row of content & sidebars
      
      // ----------------------- Footer Row
      
      if (is_active_sidebar('qnrwp-row-3')) {
        echo '<!-- Footer Row -->'.PHP_EOL;
        echo '<div id="footer-row" class="footer-row widget-area" role="complementary">'.PHP_EOL;
        dynamic_sidebar('qnrwp-row-3'); // Custom Footer menus
        echo '</div><!-- End of Footer -->'.PHP_EOL;
      }
      
    }
    catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n"; // ErrorException($message, 0, $severity, $file, $line)
      echo ' Line: ', $e->getLine(), "\n";
    }
  ?>
  <?php wp_footer(); ?>
  </body>
</html>