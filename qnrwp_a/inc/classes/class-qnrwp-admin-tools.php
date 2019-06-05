<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP Admin Tools class, not instantiated
 */
class QNRWP_Admin_Tools {
  
  /**
   * Imports our reusable blocks
   */
  public static function import_blocks() {
    if (isset($_POST['qnrwp_tools_blocks']) && $_POST['qnrwp_tools_blocks']) {
      $postsData = array();
      foreach ($_POST['qnrwp_tools_blocks'] as $bfph) {
        $fp = hex2bin($bfph);
        $js = @file_get_contents($fp);
        if ($js) {
          $jsL = json_decode($js, true);
          if ($jsL) $postsData[] = array('post-title' => $jsL['title'], 'post-content' => $jsL['content']);
        }
      }
      if ($postsData) {
        QNRWP_Admin_Tools::create_posts('wp_block', 'publish', $postsData);
      } else {
        // Print notice
        add_action('admin_notices', function () {
          $class = 'notice notice-error is-dismissible';
          $message = __('No blocks could be imported', 'qnrwp');
          printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
        });
      }
    }
  }
  
  
  /**
   * Sets all settings to required values
   */
  public static function set_settings() {
    if (isset($_POST['qnrwp_tools_settings']) && $_POST['qnrwp_tools_settings']) {
      
      // Admin bar - off
      update_user_option(get_current_user_id(), 'show_admin_bar_front', 'false');
      
      // Comments and pings off
      update_option('default_pingback_flag', '');
      update_option('default_ping_status', 'closed');
      update_option('default_comment_status', 'closed');
      
      // Media medium size 360x360
      update_option('thumbnail_size_w', 150);
      update_option('thumbnail_size_h', 150);
      update_option('thumbnail_crop', 1);
      update_option('medium_size_w', 360);
      update_option('medium_size_h', 360);
      update_option('large_size_w', 1024);
      update_option('large_size_h', 1024);
      update_option('uploads_use_yearmonth_folders', 1);
      
      // Print notice
      add_action('admin_notices', function () {
        $class = 'notice notice-success is-dismissible';
        $message = __('Settings have been set', 'qnrwp');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
      });
    }
  }
  
  
  /**
   * Sets up Home / News pages
   */
  public static function setup_pages() {
    if (isset($_POST['qnrwp_tools_setuppages']) && $_POST['qnrwp_tools_setuppages']) {
      $homeTitle = esc_html__('Home', 'qnrwp');
      $newsTitle = esc_html__('News', 'qnrwp');
      $gotHome = 0;
      $gotNews = 0;
      $pages = get_pages(array('post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private')));
      foreach ($pages as $page) {
        if ($page->post_title == $homeTitle) $gotHome = $page->ID;
        if ($page->post_title == $newsTitle) $gotNews = $page->ID;
      }
      if ($gotHome && $gotNews) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $gotHome);
        update_option('page_for_posts', $gotNews);
        // Print notice
        add_action('admin_notices', function () {
          $class = 'notice notice-success is-dismissible';
          $message = __('Home and News pages have been set up as front page and posts page', 'qnrwp');
          printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
        });
      }
    }
  }
  
  
  /**
   * Creates pages
   */
  public static function create_pages() {
    $postsData = array();
    foreach ($_POST['qnrwp_tools_create'] as $pageTitle) {
      if ($pageTitle) {
        $postsData[] = array('post-title' => $pageTitle, 'post-content' => '');
      }
    }
    if ($postsData) QNRWP_Admin_Tools::create_posts('page', 'publish', $postsData);
  }
  
  
  /**
   * Creates posts, called by page creator and blocks importer
   * 
   * @param   $postsData  array     Array of ('post-title' and 'post-content') arrays
   */
  public static function create_posts($postType, $postStatus, $postsData) {
  
    // ----------------------- Create posts
    
    $posts = get_posts(array('post_type' => $postType, 'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private')));
    $createdPostsCount = 0;
    $skippedPostsCount = 0;
    $errorsCount = 0;
    $errorPosts = array();
    $createdPosts = array();
    $skippedPosts = array();
    foreach ($postsData as $pdItem) {
      $postTitle = $pdItem['post-title'];
      if ($postTitle) {
        $alreadyExists = false;
        foreach ($posts as $post) {
          if ($post->post_title == $postTitle) {
            $alreadyExists = true;
            $skippedPostsCount += 1;
            $skippedPosts[] = $postTitle;
          }
        }
        if (!$alreadyExists) {
          $savedPostID = wp_insert_post(array(
                                'post_title' => $postTitle,
                                'post_content' => $pdItem['post-content'],
                                'post_type' => $postType,
                                'post_status' => $postStatus,
                                ), 
                                true);
          if (is_wp_error($savedPostID)) {
            $errorsCount += 1;
            $errorPosts[] = $postTitle;
          } else {
            $createdPostsCount += 1;
            $createdPosts[] = $postTitle;
          }
        }
      }
    }
    
    // ----------------------- Display notices
    
    // We use closures to be able to use our variables
    if ($errorsCount) {
      add_action('admin_notices', function () use ($errorPosts, $errorsCount, $postType) {
        $class = 'notice notice-error is-dismissible';
        if ($postType == 'page') {
          $mSing = '%1$d page could not be created due to errors: %2$s';
          $mPlur = '%1$d pages could not be created due to errors: %2$s';
        } elseif ($postType == 'wp_block') {
          $mSing = '%1$d block could not be created due to errors: %2$s';
          $mPlur = '%1$d blocks could not be created due to errors: %2$s';
        } else {
          $mSing = '%1$d post could not be created due to errors: %2$s';
          $mPlur = '%1$d posts could not be created due to errors: %2$s';
        }
        // translators: first variable is number, second is list of post titles
        $message = sprintf(_n($mSing,
                              $mPlur,
                              $errorsCount,
                              'qnrwp'), 
                              number_format_i18n($errorsCount),
                              implode(', ', $errorPosts));
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
      });
    }
    if ($skippedPostsCount) {
      add_action('admin_notices', function () use ($skippedPosts, $skippedPostsCount, $postType) {
        $class = 'notice notice-warning is-dismissible';
        if ($postType == 'page') {
          $mSing = '%1$d page was not created because it already exists: %2$s';
          $mPlur = '%1$d pages were not created because they already exist: %2$s';
        } elseif ($postType == 'wp_block') {
          $mSing = '%1$d block was not created because it already exists: %2$s';
          $mPlur = '%1$d blocks were not created because they already exist: %2$s';
        } else {
          $mSing = '%1$d post was not created because it already exists: %2$s';
          $mPlur = '%1$d posts were not created because they already exist: %2$s';
        }
        // translators: first variable is number, second is list of post titles
        $message = sprintf(_n($mSing, 
                              $mPlur, 
                              $skippedPostsCount,
                              'qnrwp'), 
                              number_format_i18n($skippedPostsCount),
                              implode(', ', $skippedPosts));
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
      });
    }
    if ($createdPostsCount) {
      add_action('admin_notices', function () use ($createdPosts, $createdPostsCount, $postType) {
        $class = 'notice notice-success is-dismissible';
        if ($postType == 'page') {
          $mSing = '%1$d page was successfully created: %2$s';
          $mPlur = '%1$d pages were successfully created: %2$s';
        } elseif ($postType == 'wp_block') {
          $mSing = '%1$d block was successfully created: %2$s';
          $mPlur = '%1$d blocks were successfully created: %2$s';
        } else {
          $mSing = '%1$d post was successfully created: %2$s';
          $mPlur = '%1$d posts were successfully created: %2$s';
        }
        // translators: first variable is number, second is list of post titles
        $message = sprintf(_n($mSing, 
                              $mPlur, 
                              $createdPostsCount,
                              'qnrwp'), 
                              number_format_i18n($createdPostsCount),
                              implode(', ', $createdPosts));
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message)); 
      });
    }
    
  }
  
} // End QNRWP_Admin_Tools class

