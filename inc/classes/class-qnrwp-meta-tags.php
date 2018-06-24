<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP meta tags class
 */
class QNRWP_Meta_Tags {
  
  /**
   * Creates meta, OpenGraph and Twitter card tags, per post or generic
   */
  public static function meta_opengraph_twitter_tags() {
    $rHtml = '';
    if (get_option('qnrwp_use_meta_tags')) {
      // ----------------------- Description
      // Get description from first paragraph if news post
      if (is_singular() && get_post_type() == 'post') {
        $postFirstPara = QNRWP_News::get_news_first_para_excerpt();
        if ($postFirstPara) $rHtml .= '<meta name="description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
        else $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
      } else { // Other pages get description from settings option or site tagline
        if (get_option('qnrwp_meta_description')) 
          $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
        else
          $rHtml .= '<meta name="description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
      }
      // ----------------------- Keywords
      if (get_option('qnrwp_meta_keywords')) 
        $rHtml .= '<meta name="keywords" content="'.esc_attr(trim(get_option('qnrwp_meta_keywords'))).'">'.PHP_EOL;
      // ----------------------- Author
      if (get_option('qnrwp_meta_author')) 
        $rHtml .= '<meta name="author" content="'.esc_attr(trim(get_option('qnrwp_meta_author'))).'">'.PHP_EOL;
    }
      
    //<!-- Open Graph -->
    //<meta name="og:title" content="">
    //<meta name="og:description" content="">
    //<meta name="og:type" content="website">
    //<meta name="og:site_name" content="">
    //<meta name="og:url" content="">
    //<meta name="og:image" content="">
    if (get_option('qnrwp_use_opengraph_tags')) {
      $rHtml .= '<!-- Open Graph -->'.PHP_EOL;
      $genericOG = false; // Avoid some code duplication later
      // News post; ensure title matches post only if excerpt obtained
      if (is_singular() && get_post_type() == 'post') {
        if (!isset($postFirstPara) || empty($postFirstPara)) $postFirstPara = QNRWP_News::get_news_first_para_excerpt();
        if ($postFirstPara) {
          $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(single_post_title('', $display=false))).'">'.PHP_EOL;
          $rHtml .= '<meta name="og:description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
        }
        else { // Both title and description generic together
          $genericOG = true;
        }
      } else { // Other pages get title and description from settings option, meta tag, or site tagline
        $genericOG = true;
      }
      if ($genericOG) {
        // ----------------------- Title
        if (get_option('qnrwp_opengraph_title')) 
          $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(get_option('qnrwp_opengraph_title'))).'">'.PHP_EOL;
        else
          $rHtml .= '<meta name="og:title" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
        // ----------------------- Description
        if (get_option('qnrwp_opengraph_description')) 
          $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_option('qnrwp_opengraph_description'))).'">'.PHP_EOL;
        else if (get_option('qnrwp_meta_description')) 
          $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
        else 
          $rHtml .= '<meta name="og:description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
      }
      // Site type, and name and URL, always from Site Title and URL settings (custom for news posts)
      $rHtml .= '<meta name="og:type" content="website">'.PHP_EOL;
      $rHtml .= '<meta name="og:site_name" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
      // ----------------------- Page and image URLs, generic or post Custom Field or Featured Image
      if (is_singular() && get_post_type() == 'post') {
        $rHtml .= '<meta name="og:url" content="'.esc_attr(get_permalink()).'">'.PHP_EOL;
        if (get_post_custom_values('OpenGraph-Twitter-Card-Image'))
          $rHtml .= '<meta name="og:image" content="'.esc_attr(esc_url(trim(get_post_custom_values('OpenGraph-Twitter-Card-Image')[0]))).'">'.PHP_EOL;
        else if (has_post_thumbnail())
          $rHtml .= '<meta name="og:image" content="'.esc_attr(get_the_post_thumbnail_url(null, $size='large')).'">'.PHP_EOL;
        else if (get_option('qnrwp_opengraph_imageurl')) 
          $rHtml .= '<meta name="og:image" content="'.esc_attr(trim(get_option('qnrwp_opengraph_imageurl'))).'">'.PHP_EOL;
      } else {
        $rHtml .= '<meta name="og:url" content="'.esc_attr(trim(get_bloginfo('url'))).'">'.PHP_EOL;
        if (get_option('qnrwp_opengraph_imageurl')) 
          $rHtml .= '<meta name="og:image" content="'.esc_attr(trim(get_option('qnrwp_opengraph_imageurl'))).'">'.PHP_EOL;
      }
    }
    
    //<!-- Twitter Card -->
    //<meta name="twitter:card" content="summary">
    //<!-- alernative content: summary_large_image -->
    //<meta name="twitter:site" content="">
    //<meta name="twitter:title" content="">
    //<meta name="twitter:description" content="">
    //<meta name="twitter:image" content="">
    if (get_option('qnrwp_use_twitter_tags')) {
      $rHtml .= '<!-- Twitter Card -->'.PHP_EOL;
      if (get_option('qnrwp_use_twitter_largeimage')) $rHtml .= '<meta name="twitter:card" content="summary_large_image">'.PHP_EOL;
      else $rHtml .= '<meta name="twitter:card" content="summary">'.PHP_EOL;
      if (get_option('qnrwp_twitter_site')) 
        $rHtml .= '<meta name="twitter:site" content="'.esc_attr(trim(get_option('qnrwp_twitter_site'))).'">'.PHP_EOL;
    
      $genericTW = false; // Avoid some code duplication later
      // News post; ensure title matches post only if excerpt obtained
      if (is_singular() && get_post_type() == 'post') {
        if (!isset($postFirstPara) || empty($postFirstPara)) $postFirstPara = QNRWP_News::get_news_first_para_excerpt();
        if ($postFirstPara) {
          $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(single_post_title('', $display=false))).'">'.PHP_EOL;
          $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim($postFirstPara)).'">'.PHP_EOL;
        }
        else { // Both title and description generic together
          $genericTW = true;
        }
      } else { // Other pages get title and description from settings option, meta tag, or site tagline
        $genericTW = true;
      }
      if ($genericTW) {
        // ----------------------- Title
        if (get_option('qnrwp_twitter_title')) 
          $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(get_option('qnrwp_twitter_title'))).'">'.PHP_EOL;
        else
          $rHtml .= '<meta name="twitter:title" content="'.esc_attr(trim(get_bloginfo('name'))).'">'.PHP_EOL;
        // ----------------------- Description
        if (get_option('qnrwp_twitter_description')) 
          $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_option('qnrwp_twitter_description'))).'">'.PHP_EOL;
        else if (get_option('qnrwp_meta_description')) 
          $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_option('qnrwp_meta_description'))).'">'.PHP_EOL;
        else 
          $rHtml .= '<meta name="twitter:description" content="'.esc_attr(trim(get_bloginfo('description'))).'">'.PHP_EOL;
      }
      // ----------------------- Image URLs, generic or post Custom Field or Featured Image
      if (is_singular() && get_post_type() == 'post') {
        if (get_post_custom_values('OpenGraph-Twitter-Card-Image'))
          $rHtml .= '<meta name="twitter:image" content="'.esc_attr(esc_url(trim(get_post_custom_values('OpenGraph-Twitter-Card-Image')[0]))).'">'.PHP_EOL;
        else if (has_post_thumbnail())
          $rHtml .= '<meta name="twitter:image" content="'.esc_attr(get_the_post_thumbnail_url(null, $size='large')).'">'.PHP_EOL;
        else if (get_option('qnrwp_twitter_imageurl')) 
          $rHtml .= '<meta name="twitter:image" content="'.esc_attr(trim(get_option('qnrwp_twitter_imageurl'))).'">'.PHP_EOL;
      } else if (get_option('qnrwp_twitter_imageurl')) {
        $rHtml .= '<meta name="twitter:image" content="'.esc_attr(trim(get_option('qnrwp_twitter_imageurl'))).'">'.PHP_EOL;
      }
    }
    return $rHtml;
  }

} // End QNRWP_Meta_Tags class
