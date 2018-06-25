<?php
/**
 * QNRWP-A index.php
 */

// ===================== The main template file =====================

// -----------------------  Error Handler

//error_reporting(E_ALL); // Report all PHP errors
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
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
<?php

echo QNRWP_Meta_Tags::meta_opengraph_twitter_tags();
// Debugging printout block for testing
//qnrwp_debug_printout(array('have posts: ' => have_posts(),
                            //'is singular: ' => is_singular(),
                            //'is home: ' => is_home(),
                            //'is front page: ' => is_front_page(),
                            //'is search: ' => is_search(),
                            //'is archive: ' => is_archive(),
                            //'is date: ' => is_date(),
                            //'content: ' => $post,
                            //'post type: ' => get_post_type(),
                            //'single post title: ' => single_post_title('', $display=false),
                            //'wp title: ' => wp_title('', $display=false),
                            //'title: ' => get_the_title()),$append=false);

QNRWP_UI_Parts::favicon_appleicon();

// Title is handled by the title-tag feature
wp_head(); // Required

?>
</head>
<body <?php body_class('qnr-winscroller'); ?> data-qnr-offset="-4" style="visibility:hidden;opacity:0;">
<?php 
  try {
    // Show in source that we're reporting errors
    echo '<!-- Error reporting: ' . error_reporting().' -->'.PHP_EOL;
    
    // ----------------------- Main Content Construction
    
    // Layout: 'single', 'left-sidebar', 'right-sidebar', 'three-cols'
    $layout = 'single';
    $rHtml = '';
    $postsAmount = 'multi';
    $isNews = false;
    $pageTitle = 'News';
    $htmlContent = '';
    
    if ($_GET['status'] == '404') { // Not found, redirected from 404.php
      $postsAmount = 'single';
      $pageTitle = '404 - '.esc_html__('Not found', 'qnrwp');
      $rHtml = '<div class="qnrwp-page-not-found-notice center" style="font-size:2em;line-height:1.4em;padding:4em 2em;">'
                .esc_html__('Sorry, the page you are looking for could not be found.', 'qnrwp')
                .'</div>';
    } else if (have_posts()) {
    
      // ----------------------- The Loop (page/post content and meta)
      while (have_posts()) {
        the_post(); // Must be here, for correct sequence of posts and their thumbs
        $layout = QNRWP::get_layout();
        // ----------------------- Singular types: single post, page, attachment
        if (is_singular()) {
          $postsAmount = 'single';
          $pageTitle = get_the_title();
          $postClass = 'class="' . join(' ', get_post_class()).' '.get_post_field('post_name').'"'; // Slug...
          // Start constructing return HTML
          $rHtml = '<div id="post-'.get_the_ID().'" '.$postClass.'>'.PHP_EOL; // Open singular
          // Get the date, if type is post
          if (get_post_type() == 'post') $rHtml .= '<div class="post-date">'.get_the_date().'</div>'.PHP_EOL;
          if (get_post_type() != 'page') $rHtml .= '<h1 class="page-title">'.$pageTitle.'</h1>'.PHP_EOL; // Not on Pages
          // Place Featured Image at start of content if News post
          $postFeaturedImageTag = '';
          if (get_post_type() == 'post') {
            if (isset(get_option('qnrwp_settings_array')['news-featured-image']) && get_option('qnrwp_settings_array')['news-featured-image'] == 1) {
              $theContent = trim(get_the_content());
              if (strpos($theContent, '[featured-image') !== 0 && strpos($theContent, '[carousel') !== 0) {
                $postFeaturedImageTag = get_the_post_thumbnail(get_the_ID(), 'large') . PHP_EOL;
              }
            }
          }
          
          $htmlContent = $postFeaturedImageTag . apply_filters('the_content', get_the_content());
          
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
            $rHtml .= '<span class="post-older"><p>'.esc_html__('Previous', 'qnrwp').'</p><p>'.$prevPostLink.'</p></span>'; // No whitespace
            $rHtml .= '<span class="post-newer"><p>'.esc_html__('Next', 'qnrwp').'</p><p>'.$nextPostLink.'</p></span>'.PHP_EOL;
            $rHtml .= '</div>'.PHP_EOL;
          }
          $rHtml .= '</div>'.PHP_EOL; // Close singular
        }
        
        // ----------------------- Multi-item page, blog/archive/category (not testimonials)
        else if (!in_category('testimonial')) { // Test no longer necessary due to filter, but kept for info
          $isNews = true;
          // Set layout
          // Construct return HTML
          $rHtml .= '<!-- Post Item Excerpt -->'.PHP_EOL; // Concatenate multiple items
          $postLink = get_the_permalink(get_the_ID());
          $rHtml .= '<a href="'.$postLink.'" id="qnrwp-excerpt-'.get_the_ID().'" class="qnrwp-excerpt-item">'.PHP_EOL; // Open item
          if (has_post_thumbnail()) {
            $rHtml .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'qnrwp-post-featured-img')); // No whitespace
          }
          $rHtml .= '<div class="qnrwp-excerpt-item-block">'.PHP_EOL;
          $rHtml .= '<p class="qnrwp-excerpt-date">'.get_the_date().'</p>'.PHP_EOL;
          $excerptTitle = get_the_title();
          // Truncate title if it is too long, over 60 chars NOT USED
          //if (strlen($excerptTitle) > 60) {
            //$excerptTitle = trim(substr($excerptTitle, 0, 60));
            //$excerptTitle = preg_replace('/(\S+)\s+\S*$/', '$1...', $excerptTitle);
          //}
          $rHtml .= '<h1>'.$excerptTitle.'</h1>'.PHP_EOL;
          $rHtml .= '<div class="qnrwp-excerpt-text">'.PHP_EOL;
          $htmlExcerpt = apply_filters('the_excerpt', get_the_excerpt());
          if (!$htmlExcerpt) $htmlExcerpt = '<p>'.esc_html__('Click here to see this post.', 'qnrwp').'</p>';
          $rHtml .= $htmlExcerpt;
          $rHtml .= '</div></div></a>'.PHP_EOL; // Close item
        }
      } // End of while
      
      if ($postsAmount == 'multi') {
        // Previous and Next posts pages links
        $nextPostsLink = get_next_posts_link('&laquo;&nbsp;'.esc_html__('Older', 'qnrwp'));
        $prevPostsLink = get_previous_posts_link(esc_html__('Newer', 'qnrwp').'&nbsp;&raquo;');
        //if ($nextPostsLink) { // Test so that we're not showing isolated &laquo;
          //$nextPostsLink = '&laquo;&nbsp;'.$nextPostsLink;
        //}
        //if ($prevPostsLink) {
          //$prevPostsLink = $prevPostsLink.'&nbsp;&raquo;';
        //}
        if ($prevPostsLink || $nextPostsLink) { // Could be only one page of posts
          $rHtml .= '<div class="qnrwp-excerpts-pages-links">'.PHP_EOL;
          $rHtml .= '<span class="qnrwp-excerpts-older">'.$nextPostsLink.'</span>'; // No whitespace
          $rHtml .= '<span class="qnrwp-excerpts-newer">'.$prevPostsLink.'</span>'.PHP_EOL;
          $rHtml .= '</div>'.PHP_EOL;
        }
      }
    } // End of have_posts()
    
    if (is_search() && $rHtml == '') { // Nothing found
      $isNews = true;
      // Set layout, as for multi
      $layout = QNRWP::get_layout();
      $rHtml = '<div class="search-results">'.esc_html__('No news posts found for this search.', 'qnrwp').'</div>'.PHP_EOL;
    }
    else if (is_search() && $rHtml != '') { // Something found
      $isNews = true;
      // Layout has already been set
      $rHtml = '<div class="search-results">'.esc_html__('News posts matching your search query', 'qnrwp').':</div>'.PHP_EOL.$rHtml;
    }
    else if (is_archive() && is_date()) {
      $isNews = true;
      // Layout has already been set
      $rHtml = '<div class="search-results">'.esc_html__('News posts for', 'qnrwp').': '.wp_title('', $display=false).'</div>'.PHP_EOL.$rHtml;
    }
    
    // ----------------------- PAGE LAYOUT
    
    // ----------------------- Header Row
    
    echo '<!-- Header Row -->'.PHP_EOL;
    $headerFixed = isset($GLOBALS['QNRWP_GLOBALS']['settingsArray']['header-fixed'])?$GLOBALS['QNRWP_GLOBALS']['settingsArray']['header-fixed']:1;
    echo '<div id="header-row" class="header-row widget-area'
            .($headerFixed
            ?' qnrwp-has-fixed-header'
            :'')
            .'">' .PHP_EOL;
    QNRWP_UI_Parts::cookie_notice();
    if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-header')) {
      dynamic_sidebar('qnrwp-row-header');
    }
    echo '<div id="header-nav-row" class="'.apply_filters('qnrwp_header_nav_row_class', 'header-nav-row flex-block flex-vertical-center-content').'">';
    QNRWP_UI_Parts::site_logo();
    QNRWP_UI_Parts::main_navigation_menu();
    echo '</div>';
    echo '</div><!-- End of Header -->'.PHP_EOL;
    
    // ----------------------- Content Row
    
    echo '<!-- Content Row -->'.PHP_EOL.'<div id="content-row" class="content-row'
              .(get_option('qnrwp_use_fixed_header', $default=0)?' qnrwp-has-fixed-header"':'"').'>'.PHP_EOL; // Open content row
    
    // ----------------------- Sub Header Row (may be narrower than header as it is in Content Row)
    
    if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-subrow-header')) {
      echo '<!-- Sub Header Row -->'.PHP_EOL;
      echo '<div id="sub-header-row" class="sub-header-row widget-area">' .PHP_EOL;
      
      // Globals for Sub Header widget
      $GLOBALS['QNRWP_GLOBALS']['isNews'] = $isNews;
      $GLOBALS['QNRWP_GLOBALS']['postsAmount'] = $postsAmount;
      $GLOBALS['QNRWP_GLOBALS']['pageTitle'] = $pageTitle;
      
      dynamic_sidebar('qnrwp-subrow-header'); // Sub Header
      echo '</div><!-- End of Sub Header Row -->'.PHP_EOL;
    }
    
    // ----------------------- Middle Row: content box & sidebars
    
    echo '<!-- Middle Row -->'.PHP_EOL.'<div id="middle-row" class="middle-row">'.PHP_EOL; // Open middle row
    
    // ----------------------- Left Sidebar
    
    if (($layout == 'three-cols' || $layout == 'left-sidebar') && QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-sidebar-left')) { // TODO redundant active sidebar test??
      echo '<!-- Left Sidebar -->'.PHP_EOL;
      echo '<div id="sidebar-left" class="sidebar sidebar-left widget-area">' .PHP_EOL;
      dynamic_sidebar('qnrwp-sidebar-left'); // Usually WP blogging widgets
      echo '</div><!-- End of Left Sidebar -->'.PHP_EOL;
    }
    
    // ----------------------- Main Content
    
    echo '<!-- Content Box -->'.PHP_EOL;
    // Adjust Content classes, accounting for sidebars
    $contentBoxClass = 'content-box';
    if ($layout == 'three-cols') $contentBoxClass .= ' three-col-content';
    else if ($layout == 'left-sidebar') $contentBoxClass .= ' two-col-content-right';
    else if ($layout == 'right-sidebar') $contentBoxClass .= ' two-col-content-left';
    else if ($layout == 'single') $contentBoxClass .= ' single-col-content';
    echo '<div id="content-box" class="'.$contentBoxClass.'">'.PHP_EOL;
    
    echo $rHtml; // Content
    //$GLOBALS['QNRWP_GLOBALS']['contentHtml'] = $rHtml; // Place in global for widget to echo NOT USED
    
    echo '</div><!-- End of Content Box -->'.PHP_EOL;
    
    // ----------------------- Right Sidebar
    
    if (($layout == 'three-cols' || $layout == 'right-sidebar') && QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-sidebar-right')) {
      echo '<!-- Right Sidebar -->'.PHP_EOL;
      echo '<div id="sidebar-right" class="sidebar sidebar-right widget-area">'.PHP_EOL;
      dynamic_sidebar('qnrwp-sidebar-right'); // Usually WP blogging widgets
      echo '</div><!-- End of Right Sidebar -->'.PHP_EOL;
    }
    
    echo '</div><!-- End of Middle Row -->'.PHP_EOL; // Close row of content & sidebars
    
    // ----------------------- Sub Content Row (under content and sidebars, may be narrower than footer as it is in Content Row)
    
    if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-subrow-content')) {
      echo '<!-- Sub Content Row -->'.PHP_EOL;
      echo '<div id="sub-content-row" class="sub-content-row widget-area">'.PHP_EOL;
      dynamic_sidebar('qnrwp-subrow-content');
      echo '</div><!-- End of Sub Content Row -->'.PHP_EOL;
    }
    
    echo '</div><!-- End of Content Row -->'.PHP_EOL; // Close content row
    
    // ----------------------- Footer Row
    
    $footerUpperActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-upper');
    $footerMiddleActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-middle');
    $footerLowerActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-lower');
    if ($footerUpperActive || $footerMiddleActive || $footerLowerActive) {
      echo '<!-- Footer Row -->'.PHP_EOL;
      echo '<div id="footer-row" class="footer-row">'.PHP_EOL;
      if ($footerUpperActive) {
        echo '<div id="footer-row-upper" class="footer-row-upper widget-area">'.PHP_EOL;
        dynamic_sidebar('qnrwp-row-footer-upper');
        echo '</div>'.PHP_EOL;
      }
      if ($footerMiddleActive) {
        echo '<div id="footer-row-middle" class="footer-row-middle widget-area">'.PHP_EOL;
        dynamic_sidebar('qnrwp-row-footer-middle');
        echo '</div>'.PHP_EOL;
      }
      if ($footerLowerActive) {
        echo '<div id="footer-row-lower" class="footer-row-lower widget-area">'.PHP_EOL;
        dynamic_sidebar('qnrwp-row-footer-lower');
        echo '</div>'.PHP_EOL;
      }
      echo '</div><!-- End of Footer -->'.PHP_EOL;
    }
    
    wp_reset_postdata(); // Restore original Post Data
    
  }
  catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n"; // ErrorException($message, 0, $severity, $file, $line)
    echo ' Line: ', $e->getLine(), "\n";
  }
?>
<?php print_late_styles(); wp_footer(); ?>
</body>
</html>