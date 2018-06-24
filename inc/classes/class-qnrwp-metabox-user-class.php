<?php

defined('ABSPATH') || exit;

/**
 * Metabox class, allowing user to set own CSS class on post in admin (a singleton)
 * 
 * Code adapted from https://www.smashingmagazine.com/2011/10/create-custom-post-meta-boxes-wordpress
 */
final class QNRWP_Metabox_User_Class {
    
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks
   */
  private function hooks() {
    // Fire our meta box setup function on the post editor screen
    add_action('load-post.php', array($this, 'qnrwp_post_meta_boxes_setup'));
    add_action('load-post-new.php', array($this, 'qnrwp_post_meta_boxes_setup'));
    // Filter the post class hook with our custom post class function
    add_filter('post_class', array($this, 'qnrwp_user_post_class'));
  }
  
  
  /**
   * Meta box setup function
   */
  public function qnrwp_post_meta_boxes_setup() {
    // Add meta boxes on the 'add_meta_boxes' hook
    add_action('add_meta_boxes', array($this, 'qnrwp_add_post_meta_boxes'));
    // Save post meta on the 'save_post' hook
    add_action('save_post', array($this, 'qnrwp_save_user_post_class_meta'), 10, 2);
  }
  
  
  /**
   * Creates a meta box to be displayed on the post editor screen
   */
  public function qnrwp_add_post_meta_boxes() {
    add_meta_box(
      'qnrwp-user-post-class',                          // Unique ID
      esc_html__('User Post Class', 'qnrwp'),           // Title
      array($this, 'qnrwp_user_post_class_meta_box'),   // Callback function to echo the HTML
      'post',                                           // Admin page (or post type); single screen ID, WP_Screen object, or array of screen IDs
      'side',                                           // Context (normal / advanced / side)
      'default',                                        // Priority (default / core / high / low)
      null                                              // Callback args array, as the second parameter to callback function
   );
  }
  
  
  /**
   * Displays the post meta box
   */
  public function qnrwp_user_post_class_meta_box($post) {
    wp_nonce_field(basename(__FILE__), 'qnrwp_user_post_class_nonce'); ?>
    <p>
      <label for="qnrwp-user-post-class"><?php esc_html_e('Add a custom CSS class to this post.', 'qnrwp'); ?></label>
      <br>
      <input class="widefat" type="text" 
                name="qnrwp-user-post-class" 
                id="qnrwp-user-post-class" 
                value="<?php echo esc_attr(get_post_meta($post->ID, 'qnrwp_user_post_class', true)); ?>" 
                size="30">
    </p>
  <?php }

  
  /**
   * Saves the meta box's post metadata
   */
  public function qnrwp_save_user_post_class_meta($post_id, $post) {
    // Verify the nonce before proceeding
    if (!isset($_POST['qnrwp_user_post_class_nonce']) || !wp_verify_nonce($_POST['qnrwp_user_post_class_nonce'], basename(__FILE__)))
      return $post_id;
    // Get the post type object
    $post_type = get_post_type_object($post->post_type);
    // Check if the current user has permission to edit the post
    if (!current_user_can($post_type->cap->edit_post, $post_id)) return $post_id;
    // Get the posted data and sanitize it for use as an HTML class
    $new_meta_value = (isset($_POST['qnrwp-user-post-class']) ? sanitize_html_class($_POST['qnrwp-user-post-class']) : '');
    // Get the meta key
    $meta_key = 'qnrwp_user_post_class';
    // Get the meta value of the custom field key
    $meta_value = get_post_meta($post_id, $meta_key, true);
    // If a new meta value was added and there was no previous value, add it
    if ($new_meta_value && $meta_value == '') add_post_meta($post_id, $meta_key, $new_meta_value, true);
    // If the new meta value does not match the old value, update it
    elseif ($new_meta_value && $new_meta_value != $meta_value) update_post_meta($post_id, $meta_key, $new_meta_value);
    // If there is no new meta value but an old value exists, delete it
    elseif ($new_meta_value == '' && $meta_value) delete_post_meta($post_id, $meta_key, $meta_value);
  }

  
  /**
   * Adds user class to post class attribute
   */
  public function qnrwp_user_post_class($classes) {
    // Get the current post ID
    $post_id = get_the_ID();
    // If we have a post ID, proceed
    if (!empty($post_id)) {
      // Get the custom post class
      $post_class = get_post_meta($post_id, 'qnrwp_user_post_class', true);
      // If a post class was input, sanitize it and add it to the post class array
      if (!empty($post_class)) $classes[] = sanitize_html_class($post_class);
    }
    return $classes;
  }

} // End QNRWP_Metabox_User_Class
