<?php
/**
 * QNRWP-A index.php
 */

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
    <?php wp_head(); ?>
  </head>
  <body onload="afterLoad()" <?php body_class('qnr-winscroller'); ?> data-qnr-offset="-4">    
  <?php 
    try {
      // Show in source that we're reporting errors
      echo '<!-- Error reporting: ' . error_reporting().' -->'.PHP_EOL;
      
      // ----------------------- Main Content Construction
      
      if (is_active_sidebar('qnrwp-row-2')) {
        // Layout: 'single', 'left-sidebar', 'right-sidebar', 'three-cols'
        $layout = 'single';
        $rHtml = '';
        $postsAmount = 'multi';
        $isNews = false;
        $pageTitle = 'News';
        $htmlContent = '';
        
        // ----------------------- The Loop (page/post content and meta)
        if (have_posts()) {
          while (have_posts()) {
            the_post(); // Must be here, for correct sequence of posts and their thumbs
                        
            // ----------------------- Singular types: single post, page, attachment
            if (is_singular()) {
              $postsAmount = 'single';
              $pageTitle = get_the_title();
              // Set layout
              if (get_post_type() == 'page') $layout = 'single';
              // If not Page, single Posts are laid out same as multi-item pages
              else $layout = qnrwp_get_layout(); // In functions.php
              $postClass = 'class="' . join(' ', get_post_class()).' '.get_post_field('post_name').'"'; // Slug...
              // Start constructing return HTML
              $rHtml = '<div id="post-'.get_the_ID().'" '.$postClass.'>'.PHP_EOL; // Open singular
              // Get the date, if type is post
              if (get_post_type() == 'post') $rHtml .= '<div class="post-date">'.get_the_date().'</div>'.PHP_EOL;
              if (get_post_type() != 'page') $rHtml .= '<h1 class="page-title">'.$pageTitle.'</h1>'.PHP_EOL; // Not on Pages
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
                $prevPostLink = get_previous_post_link( $format = '%link', 
                                                        $link = '&laquo;&nbsp;%title', 
                                                        $in_same_term = false, 
                                                        $excluded_terms = $exCats, 
                                                        $taxonomy = 'category');
                                                        
                $nextPostLink = get_next_post_link(     $format = '%link', 
                                                        $link = '%title&nbsp;&raquo;', 
                                                        $in_same_term = false, 
                                                        $excluded_terms = $exCats, 
                                                        $taxonomy = 'category');
                // Previous and Next post links
                $rHtml .= '<div class="post-nav-links">'.PHP_EOL;
                $rHtml .= '<span class="post-older"><p>Previous</p><p>'.$prevPostLink.'</p></span>'; // No whitespace
                $rHtml .= '<span class="post-newer"><p>Next</p><p>'.$nextPostLink.'</p></span>'.PHP_EOL;
                $rHtml .= '</div>'.PHP_EOL;
              }
              $rHtml .= '</div>'.PHP_EOL; // Close singular
            }
            
            // ----------------------- Multi-item page, blog/archive/category (not testimonials)
            else if (!in_category('testimonial')) { // Test no longer necessary due to filter, but kept for info
              $isNews = true;
              // Set layout
              $layout = qnrwp_get_layout(); // In functions.php
              // Construct return HTML
              $rHtml .= '<!-- Post Item Excerpt -->'.PHP_EOL; // Concatenate multiple items
              $postLink = get_the_permalink(get_the_ID());
              $rHtml .= '<a href="'.$postLink.'" id="excerpt-'.get_the_ID().'" class="excerpt-item">'.PHP_EOL; // Open item
              if (has_post_thumbnail()) {
                $rHtml .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'qnrwp-post-featured-img')); // No whitespace
              }
              $rHtml .= '<div class="excerpt-item-block">'.PHP_EOL;
              $rHtml .= '<p class="excerpt-date">'.get_the_date().'</p>'.PHP_EOL;
              $excerptTitle = get_the_title();
              // Truncate title if it is too long, over 60 chars NOT USED
              //if (strlen($excerptTitle) > 60) {
                //$excerptTitle = trim(substr($excerptTitle, 0, 60));
                //$excerptTitle = preg_replace('/(\S+)\s+\S*$/', '$1...', $excerptTitle);
              //}
              $rHtml .= '<h1>'.$excerptTitle.'</h1>'.PHP_EOL;
              $rHtml .= '<div class="excerpt-text">'.PHP_EOL;
              $htmlExcerpt = apply_filters('the_excerpt', get_the_excerpt());
              // Roll our own excerpt getter NOT USED
              //$htmlExcerpt = qnrwp_get_news_first_para_excerpt();
              if (!$htmlExcerpt) $htmlExcerpt = '<p>Click here to see this post.</p>';
              $rHtml .= $htmlExcerpt;
              $rHtml .= '</div></div></a>'.PHP_EOL; // Close item
            }
          }
          if ($postsAmount == 'multi') {
            // Previous and Next posts pages links
            $nextPostsLink = get_next_posts_link('&laquo;&nbsp;Older');
            $prevPostsLink = get_previous_posts_link('Newer&nbsp;&raquo;');
            //if ($nextPostsLink) { // Test so that we're not showing isolated &laquo;
              //$nextPostsLink = '&laquo;&nbsp;'.$nextPostsLink;
            //}
            //if ($prevPostsLink) {
              //$prevPostsLink = $prevPostsLink.'&nbsp;&raquo;';
            //}
            if ($prevPostsLink || $nextPostsLink) { // Could be only one page of posts
              $rHtml .= '<div class="excerpts-pages-links">'.PHP_EOL;
              $rHtml .= '<span class="excerpts-older">'.$nextPostsLink.'</span>'; // No whitespace
              $rHtml .= '<span class="excerpts-newer">'.$prevPostsLink.'</span>'.PHP_EOL;
              $rHtml .= '</div>'.PHP_EOL;
            }
          }
        }
        if (is_search() && $rHtml == '') { // Nothing found
          $isNews = true;
          // Set layout, as for multi
          $layout = qnrwp_get_layout(); // In functions.php
          $rHtml = '<div class="search-results">No news posts found for this search.</div>'.PHP_EOL;
        }
        else if (is_search() && $rHtml != '') { // Something found
          $isNews = true;
          // Layout has already been set
          $rHtml = '<div class="search-results">News posts matching your search query:</div>'.PHP_EOL.$rHtml;
        }
      }
      
      // ----------------------- PAGE LAYOUT
      
      // ----------------------- Header Row
      
      if (is_active_sidebar('qnrwp-row-1')) {
        echo '<!-- Header Row -->'.PHP_EOL;
        echo '<div id="header-row" class="header-row widget-area">' .PHP_EOL;
        dynamic_sidebar('qnrwp-row-1'); // Navigation menu & intro header usually
        echo '</div><!-- End of Header -->'.PHP_EOL;
      }
      // ----------------------- Content Row
      
      echo '<!-- Content Row -->'.PHP_EOL.'<div id="content-row">'.PHP_EOL; // Open content row
      
      // ----------------------- Sub Header Row (may be narrower than header as it is in Content Row)
      
      if (is_active_sidebar('qnrwp-subrow-1')) {
        echo '<!-- Sub Header Row -->'.PHP_EOL;
        echo '<div id="sub-header-row" class="sub-header-row widget-area">' .PHP_EOL;
        
        // Globals for Sub Header widget
        $GLOBALS['isNews'] = $isNews;
        $GLOBALS['postsAmount'] = $postsAmount;
        $GLOBALS['pageTitle'] = $pageTitle;
        
        dynamic_sidebar('qnrwp-subrow-1'); // Sub Header
        echo '</div><!-- End of Sub Header -->'.PHP_EOL;
      }
      
      // ----------------------- Middle Row
      
      echo '<!-- Middle Row -->'.PHP_EOL.'<div id="middle-row">'.PHP_EOL; // Open row of content & sidebars
      
      // ----------------------- Left Sidebar
      
      if (($layout == 'three-cols' || $layout == 'left-sidebar') && is_active_sidebar('qnrwp-sidebar-1')) {
        echo '<!-- Left Sidebar -->'.PHP_EOL;
        echo '<div id="sidebar-left" class="sidebar sidebar-left widget-area">' .PHP_EOL;
        dynamic_sidebar('qnrwp-sidebar-1'); // Usually WP blogging widgets
        echo '</div><!-- End of Left Sidebar -->'.PHP_EOL;
      }
      
      // ----------------------- Main Content
      
      if (is_active_sidebar('qnrwp-row-2')) {
        echo '<!-- Content Box -->'.PHP_EOL;
        // Adjust Content classes, accounting for sidebars
        $contentBoxClass = 'content-box widget-area';
        if ($layout == 'three-cols') $contentBoxClass .= ' three-col-content';
        else if ($layout == 'left-sidebar') $contentBoxClass .= ' two-col-content-right';
        else if ($layout == 'right-sidebar') $contentBoxClass .= ' two-col-content-left';
        else if ($layout == 'single') $contentBoxClass .= ' single-col-content';
        echo '<div id="content-box" class="'.$contentBoxClass.'">'.PHP_EOL;
        
        //echo $rHtml; // Content
        $GLOBALS['contentHtml'] = $rHtml; // Place in global for widget to echo
        wp_reset_postdata(); // Restore original Post Data
        
        dynamic_sidebar('qnrwp-row-2');
        
        echo '</div><!-- End of Content Box -->'.PHP_EOL;
      }
      
      // ----------------------- Right Sidebar
      
      if (($layout == 'three-cols' || $layout == 'right-sidebar') && is_active_sidebar('qnrwp-sidebar-2')) {
        echo '<!-- Right Sidebar -->'.PHP_EOL;
        echo '<div id="sidebar-right" class="sidebar sidebar-right widget-area">'.PHP_EOL;
        dynamic_sidebar('qnrwp-sidebar-2'); // Usually WP blogging widgets
        echo '</div><!-- End of Right Sidebar -->'.PHP_EOL;
      }
      
      echo '</div><!-- End of Middle Row -->'.PHP_EOL; // Close row of content & sidebars
      echo '</div><!-- End of Content Row -->'.PHP_EOL; // Close content row
      
      // ----------------------- Footer Row
      
      if (is_active_sidebar('qnrwp-row-3')) {
        echo '<!-- Footer Row -->'.PHP_EOL;
        echo '<div id="footer-row" class="footer-row widget-area">'.PHP_EOL;
        dynamic_sidebar('qnrwp-row-3'); // Custom Footer menus
        echo '</div><!-- End of Footer -->'.PHP_EOL;
      }
      
    }
    catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n"; // ErrorException($message, 0, $severity, $file, $line)
      echo ' Line: ', $e->getLine(), "\n";
    }
  ?>
  <?php print_late_styles(); wp_footer(); ?>
  </body>
</html>