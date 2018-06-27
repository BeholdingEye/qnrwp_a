<?php
/*
 * Header
 */
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

// Title is handled by the title-tag feature
wp_head(); // Required
?>
</head>
<body <?php body_class('qnr-winscroller'); ?> data-qnr-offset="-4" style="visibility:hidden;opacity:0;">
<?php

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
