<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP carousel class, used by custom widget
 */
class QNRWP_Carousel {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Carousel hooks
   */
  private function hooks() {
    add_action('init', array($this, 'carousel_post_type'));
    //add_filter('post_updated_messages', array($this, 'carousel_updated_messages')); // TODO
    add_action('current_screen', array($this, 'add_help_text'));
  }
  
  
  /**
   * Defines the Carousel post type
   */
  public function carousel_post_type() {
    register_post_type('qnrwp_carousel',
      array(
        'labels'                => array(
                                          'name'                    => __('Carousels', 'qnrwp'),
                                          'singular_name'           => __('Carousel', 'qnrwp'),
                                          'add_new'                 => __('Add New', 'qnrwp'),
                                          'add_new_item'            => __('Add New Carousel', 'qnrwp'),
                                          'edit_item'               => __('Edit Carousel', 'qnrwp'),
                                          'new_item'                => __('New Carousel', 'qnrwp'),
                                          'view_item'               => __('View Carousel', 'qnrwp'),
                                          'view_items'              => __('View Carousels', 'qnrwp'),
                                          'search_items'            => __('Search Carousels', 'qnrwp'),
                                          'not_found'               => __('No Carousels found', 'qnrwp'),
                                          'not_found_in_trash'      => __('No Carousels found in Trash', 'qnrwp'),
                                          'all_items'               => __('All Carousels', 'qnrwp'),
                                          'archives'                => __('Carousel Archives', 'qnrwp'),
                                          'attributes'              => __('Carousel Attributes', 'qnrwp'),
                                          'insert_into_item'        => __('Insert into Carousel', 'qnrwp'),
                                          'uploaded_to_this_item'   => __('Uploaded to this Carousel', 'qnrwp'),
                                          'featured_image'          => __('Carousel Image', 'qnrwp'),
                                          'set_featured_image'      => __('Set Carousel Image', 'qnrwp'),
                                          'remove_featured_image'   => __('Remove Carousel Image', 'qnrwp'),
                                          'use_featured_image'      => __('Use as Carousel Image', 'qnrwp'),
                                    ),
        'public'                => true,
        'hierarchical'          => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'show_in_nav_menus'     => false,
        'has_archive'           => false,
        'delete_with_user'      => false,
        'rewrite'               => false,
        'supports'              => array(
                                          'title',
                                          'editor',
                                          'revisions',
                                          'page-attributes',
                                          'thumbnail',
                                    ),
        'menu_icon'             => 'dashicons-images-alt2',
      ));
  }
  
  
  /**
   * Defines carousel-specific update messages TODO disabled, not working well
   */
  public function carousel_updated_messages($messages) {
    $post             = get_post();
    $post_type        = get_post_type($post);
    $post_type_object = get_post_type_object($post_type);

    $messages['qnrwp_carousel'] = array(
      0  => '', // Unused. Messages start at index 1.
      1  => __('Carousel updated.', 'qnrwp'),
      2  => __('Custom field updated.', 'qnrwp'),
      3  => __('Custom field deleted.', 'qnrwp'),
      4  => __('Carousel updated.', 'qnrwp'),
      /* translators: %s: date and time of the revision */
      5  => isset($_GET['revision']) ? sprintf(__('Carousel restored to revision from %s', 'qnrwp'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
      6  => __('Carousel published.', 'qnrwp'),
      7  => __('Carousel saved.', 'qnrwp'),
      8  => __('Carousel submitted.', 'qnrwp'),
      9  => sprintf(
                      __('Carousel scheduled for: <strong>%1$s</strong>.', 'qnrwp'),
                      // translators: Publish box date format, see http://php.net/date
                      date_i18n(__('M j, Y @ G:i', 'qnrwp'), strtotime($post->post_date))
                   ),
      10 => __('Carousel draft updated.', 'qnrwp'),
    );

    if ($post_type_object->publicly_queryable) {
      $permalink = get_permalink($post->ID);

      $view_link = sprintf('&nbsp;<a href="%s">%s</a>', esc_url($permalink), __('View Carousel', 'qnrwp'));
      $messages['qnrwp_carousel'][1] .= $view_link;
      $messages['qnrwp_carousel'][6] .= $view_link;
      $messages['qnrwp_carousel'][9] .= $view_link;

      $preview_permalink = add_query_arg('preview', 'true', $permalink);
      $preview_link      = sprintf('<a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), __('Preview Carousel', 'qnrwp'));
      $messages[$post_type][8] .= $preview_link;
      $messages[$post_type][10] .= $preview_link;
    }

    return $messages;
  }
  

  /**
   * Displays contextual help for the Carousel post type
   */
  public function add_help_text() {
    $screen = get_current_screen();
    //qnrwp_debug_printout($screen);
    
    // Construct generic sidebar for use in both screens
    $sHtml = '<p>'.esc_html__('More information:', 'qnrwp').'</p>';
    $sHtml .= '<p><a href="https://codex.wordpress.org/Posts_Screen" target="_blank">'.esc_html__('Edit Posts Documentation', 'qnrwp').'</a></p>';
    $sHtml .= '<p><a href="https://wordpress.org/support" target="_blank">'.esc_html__('Support Forums', 'qnrwp').'</a></p>';
    
    if ($screen->id == 'edit-qnrwp_carousel') {
      //$screen->remove_help_tabs();
      $html = '<p>'.esc_html__('This table lists your Carousels. You may create new Carousels or edit exising ones in the usual way.', 'qnrwp').'</p>';
      $html .= '<p>'.esc_html__('More details and instructions are in the help pane of the Carousel edit screen.', 'qnrwp').'</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'edit_carousels_help',
                                  'title'     => __('Carousels Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    } else if ($screen->id == 'qnrwp_carousel') {
      //$screen->remove_help_tabs();
      $html = '<p>'.__('<em>How to create a Carousel:</em>', 'qnrwp');
      $html .= '<ol>';
      $html .= '<li>'.__('Enter the <strong>title</strong> of the Carousel. The title will appear in the selection menu of the QNRWP Custom Widget on the Appearance/Widgets page when you add the widget to a sidebar. When creating a carousel with the "carousel" shortcode, you will use the Carousel name to identify it.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Enter the Carousel <strong>definition string</strong> in the main textarea. The format of the definition string is described in the Documentation pane of QNRWP Theme Settings.', 'qnrwp').'</li>';
      $html .= '<li>'.__('The Carousel that you create with the above steps will define the Carousel object. To create <strong>individual slides</strong>, create new Carousel posts, as children of the defining Carousel. Child Carousel posts should not contain a definition string, but may instead contain content that will be displayed in the slide. They may be named anything, but "Slide 1", "Slide 2", etc. is a good convention.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Assign a <strong>Carousel Image</strong> to the child post. The width of the image should match the maximum width of the Carousel and the image sizes must be the same for all slides.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Save the Carousel(s) and in Appearance/Widgets, add the QNRWP Custom Widget to the a sidebar. Select the Carousel from the selection menu and Save. Alternatively, use the "carousel" shortcode to place the Carousel in a page. The shortcode is documented in the Documentation. The Widget supports Carousels with QNRWP-Largest images, 2000px wide. For other sizes, the shortcode must be used.', 'qnrwp').'</li>';
      $html .= '</ol>';
      $html .='</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'carousels_help',
                                  'title'     => __('Carousels Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    }
  }

} // End QNRWP_Carousel class
