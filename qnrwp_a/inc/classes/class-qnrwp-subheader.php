<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP subheader class, CPT used by custom widget
 */
class QNRWP_Subheader {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Subheader hooks
   */
  private function hooks() {
    add_action('init', array($this, 'subheader_post_type'));
    //add_filter('post_updated_messages', array($this, 'subheader_updated_messages')); // TODO
    add_action('current_screen', array($this, 'add_help_text'));
  }
  
  
  /**
   * Defines the Subheader post type
   */
  public function subheader_post_type() {
    register_post_type('qnrwp_subheader',
      array(
        'labels'                => array(
                                          'name'                    => __('Subheaders', 'qnrwp'),
                                          'singular_name'           => __('Subheader', 'qnrwp'),
                                          'add_new'                 => __('Add New', 'qnrwp'),
                                          'add_new_item'            => __('Add New Subheader', 'qnrwp'),
                                          'edit_item'               => __('Edit Subheader', 'qnrwp'),
                                          'new_item'                => __('New Subheader', 'qnrwp'),
                                          'view_item'               => __('View Subheader', 'qnrwp'),
                                          'view_items'              => __('View Subheaders', 'qnrwp'),
                                          'search_items'            => __('Search Subheaders', 'qnrwp'),
                                          'not_found'               => __('No Subheaders found', 'qnrwp'),
                                          'not_found_in_trash'      => __('No Subheaders found in Trash', 'qnrwp'),
                                          'all_items'               => __('All Subheaders', 'qnrwp'),
                                          'archives'                => __('Subheader Archives', 'qnrwp'),
                                          'attributes'              => __('Subheader Attributes', 'qnrwp'),
                                          'insert_into_item'        => __('Insert into Subheader', 'qnrwp'),
                                          'uploaded_to_this_item'   => __('Uploaded to this Subheader', 'qnrwp'),
                                          'featured_image'          => __('Subheader Image', 'qnrwp'),
                                          'set_featured_image'      => __('Set Subheader Image', 'qnrwp'),
                                          'remove_featured_image'   => __('Remove Subheader Image', 'qnrwp'),
                                          'use_featured_image'      => __('Use as Subheader Image', 'qnrwp'),
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
        'menu_icon'             => 'dashicons-schedule',
      ));
  }
  
  
  /**
   * Defines subheader-specific update messages TODO disabled, not working well
   */
  public function subheader_updated_messages($messages) {
    $post             = get_post();
    $post_type        = get_post_type($post);
    $post_type_object = get_post_type_object($post_type);

    $messages['qnrwp_subheader'] = array(
      0  => '', // Unused. Messages start at index 1.
      1  => __('Subheader updated.', 'qnrwp'),
      2  => __('Custom field updated.', 'qnrwp'),
      3  => __('Custom field deleted.', 'qnrwp'),
      4  => __('Subheader updated.', 'qnrwp'),
      /* translators: %s: date and time of the revision */
      5  => isset($_GET['revision']) ? sprintf(__('Subheader restored to revision from %s', 'qnrwp'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
      6  => __('Subheader published.', 'qnrwp'),
      7  => __('Subheader saved.', 'qnrwp'),
      8  => __('Subheader submitted.', 'qnrwp'),
      9  => sprintf(
                      __('Subheader scheduled for: <strong>%1$s</strong>.', 'qnrwp'),
                      // translators: Publish box date format, see http://php.net/date
                      date_i18n(__('M j, Y @ G:i', 'qnrwp'), strtotime($post->post_date))
                   ),
      10 => __('Subheader draft updated.', 'qnrwp'),
    );

    if ($post_type_object->publicly_queryable) {
      $permalink = get_permalink($post->ID);

      $view_link = sprintf('&nbsp;<a href="%s">%s</a>', esc_url($permalink), __('View Subheader', 'qnrwp'));
      $messages['qnrwp_subheader'][1] .= $view_link;
      $messages['qnrwp_subheader'][6] .= $view_link;
      $messages['qnrwp_subheader'][9] .= $view_link;

      $preview_permalink = add_query_arg('preview', 'true', $permalink);
      $preview_link      = sprintf('<a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), __('Preview Subheader', 'qnrwp'));
      $messages[$post_type][8] .= $preview_link;
      $messages[$post_type][10] .= $preview_link;
    }

    return $messages;
  }
  

  /**
   * Displays contextual help for the Subheader post type
   */
  public function add_help_text() {
    $screen = get_current_screen();
    //qnrwp_debug_printout($screen);
    
    // Construct generic sidebar for use in both screens
    $sHtml = '<p>'.esc_html__('More information:', 'qnrwp').'</p>';
    $sHtml .= '<p><a href="https://codex.wordpress.org/Posts_Screen" target="_blank">'.esc_html__('Edit Posts Documentation', 'qnrwp').'</a></p>';
    $sHtml .= '<p><a href="https://wordpress.org/support" target="_blank">'.esc_html__('Support Forums', 'qnrwp').'</a></p>';
    
    if ($screen->id == 'edit-qnrwp_subheader') {
      //$screen->remove_help_tabs();
      $html = '<p>'.esc_html__('This table lists your Subheaders. You may create new Subheaders or edit exising ones in the usual way.', 'qnrwp').'</p>';
      $html .= '<p>'.esc_html__('More details and instructions are in the help pane of the Subheader edit screen.', 'qnrwp').'</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'edit_subheaders_help',
                                  'title'     => __('Subheaders Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    } else if ($screen->id == 'qnrwp_subheader') {
      //$screen->remove_help_tabs();
      $html = '<p>'.__('<em>How to create a Subheader:</em>', 'qnrwp');
      $html .= '<ol>';
      $html .= '<li>'.__('Enter the <strong>title</strong> of the Subheader. The title will appear in the selection menu of the QNRWP Custom Widget on the Appearance/Widgets page when you add the widget to a Sidebar, usually the Sub Header Row.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Enter the Subheader <strong>definition string</strong> in the main textarea. The format of the definition string is described in the Documentation pane of QNRWP Theme Settings.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Assign a <strong>Subheader Image</strong>. The image should be at least as wide as the maximum page width, and at least 600px high. If the Subheader will be defined to feature a parallax scrolling effect, the image should be taller than the default maximum Subheader height of 600px. You may need to experiment for the right look.', 'qnrwp').'</li>';
      $html .= '<li>'.__('The Subheader that you create with the above steps will be the default Subheader for all pages on the site. To create an <strong>individual Subheader</strong> for particular pages, make a new Subheader, with the same title as the page on which the Subheader should appear, and set the new Subheader as a child of the defining Subheader. Child Subheaders should not contain a definition string, but may instead contain content that will be displayed in the Subheader for that page. If you place no content, then by default the title of the page will appear over the Subheader image. Likewise, the Subheader Image assigned to a child Subheader will be shown on the page that the child is for.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Save the Subheader(s) and in Appearance/Widgets, add the QNRWP Custom Widget to the Sub Header Row sidebar. Select the Subheader from the selection menu and Save.', 'qnrwp').'</li>';
      $html .= '</ol>';
      $html .='</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'subheaders_help',
                                  'title'     => __('Subheaders Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    }
  }

} // End QNRWP_Subheader class
