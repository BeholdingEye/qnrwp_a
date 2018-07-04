<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP samples class, used by shortcode
 */
class QNRWP_Samples {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Sample Card hooks
   */
  private function hooks() {
    add_action('init', array($this, 'sample_post_type'));
    //add_filter('post_updated_messages', array($this, 'sample_updated_messages')); // TODO
    add_action('current_screen', array($this, 'add_help_text'));
    add_action('admin_menu', array($this, 'add_post_meta_boxes'));
    add_action('save_post', array($this, 'save_sample_meta'), 10, 2);
  }
  
  
  /**
   * Defines the Sample Card post type
   */
  public function sample_post_type() {
    register_post_type('qnrwp_sample_card',
      array(
        'labels'            => array(
                                      'name'                    => __('Sample Cards', 'qnrwp'),
                                      'singular_name'           => __('Sample Card', 'qnrwp'),
                                      'add_new'                 => __('Add New', 'qnrwp'),
                                      'add_new_item'            => __('Add New Sample Card', 'qnrwp'),
                                      'edit_item'               => __('Edit Sample Card', 'qnrwp'),
                                      'new_item'                => __('New Sample Card', 'qnrwp'),
                                      'view_item'               => __('View Sample Card', 'qnrwp'),
                                      'view_items'              => __('View Sample Cards', 'qnrwp'),
                                      'search_items'            => __('Search Sample Cards', 'qnrwp'),
                                      'not_found'               => __('No Sample Cards found', 'qnrwp'),
                                      'not_found_in_trash'      => __('No Sample Cards found in Trash', 'qnrwp'),
                                      'all_items'               => __('All Sample Cards', 'qnrwp'),
                                      'archives'                => __('Sample Card Archives', 'qnrwp'),
                                      'attributes'              => __('Sample Card Attributes', 'qnrwp'),
                                      'insert_into_item'        => __('Insert into Sample Card', 'qnrwp'),
                                      'uploaded_to_this_item'   => __('Uploaded to this Sample Card', 'qnrwp'),
                                      'featured_image'          => __('Sample Card Image', 'qnrwp'),
                                      'set_featured_image'      => __('Set Sample Card Image', 'qnrwp'),
                                      'remove_featured_image'   => __('Remove Sample Card Image', 'qnrwp'),
                                      'use_featured_image'      => __('Use as Sample Card Image', 'qnrwp'),
                                    ),
        'public'            => true,
        'has_archive'       => true,
        'delete_with_user'  => false,
        'rewrite'           => array(
                                      'slug' => 'sample-cards', // Requires 'flushing' of permalinks
                                    ),
        'supports'          => array(
                                      'title',
                                      'editor',
                                      'revisions',
                                      'page-attributes',
                                      'thumbnail',
                                    ),
        'menu_icon'         => 'dashicons-images-alt',
      ));
  }
  
  
  /**
   * Defines sample-specific update messages TODO disabled, not working well
   */
  public function sample_updated_messages($messages) {
    $post             = get_post();
    $post_type        = get_post_type($post);
    $post_type_object = get_post_type_object($post_type);

    $messages['qnrwp_sample_card'] = array(
      0  => '', // Unused. Messages start at index 1.
      1  => __('Sample Card updated.', 'qnrwp'),
      2  => __('Custom field updated.', 'qnrwp'),
      3  => __('Custom field deleted.', 'qnrwp'),
      4  => __('Sample Card updated.', 'qnrwp'),
      /* translators: %s: date and time of the revision */
      5  => isset($_GET['revision']) ? sprintf(__('Sample Card restored to revision from %s', 'qnrwp'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
      6  => __('Sample Card published.', 'qnrwp'),
      7  => __('Sample Card saved.', 'qnrwp'),
      8  => __('Sample Card submitted.', 'qnrwp'),
      9  => sprintf(
                      __('Sample Card scheduled for: <strong>%1$s</strong>.', 'qnrwp'),
                      // translators: Publish box date format, see http://php.net/date
                      date_i18n(__('M j, Y @ G:i', 'qnrwp'), strtotime($post->post_date))
                   ),
      10 => __('Sample Card draft updated.', 'qnrwp'),
    );

    if ($post_type_object->publicly_queryable) {
      $permalink = get_permalink($post->ID);

      $view_link = sprintf('&nbsp;<a href="%s">%s</a>', esc_url($permalink), __('View Sample Card', 'qnrwp'));
      $messages['qnrwp_sample_card'][1] .= $view_link;
      $messages['qnrwp_sample_card'][6] .= $view_link;
      $messages['qnrwp_sample_card'][9] .= $view_link;

      $preview_permalink = add_query_arg('preview', 'true', $permalink);
      $preview_link      = sprintf('<a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), __('Preview Sample Card', 'qnrwp'));
      $messages[$post_type][8] .= $preview_link;
      $messages[$post_type][10] .= $preview_link;
    }

    return $messages;
  }
  

  /**
   * Displays contextual help for the Sample Card post type
   */
  public function add_help_text() {
    $screen = get_current_screen();
    //qnrwp_debug_printout($screen);
    
    // Construct generic sidebar for use in both screens
    $sHtml = '<p>'.esc_html__('More information:', 'qnrwp').'</p>';
    $sHtml .= '<p><a href="https://codex.wordpress.org/Posts_Screen" target="_blank">'.esc_html__('Edit Posts Documentation', 'qnrwp').'</a></p>';
    $sHtml .= '<p><a href="https://wordpress.org/support" target="_blank">'.esc_html__('Support Forums', 'qnrwp').'</a></p>';
    
    if ($screen->id == 'edit-qnrwp_sample_card') {
      //$screen->remove_help_tabs();
      $html = '<p>'.esc_html__('This table lists your Sample Cards. You may create new cards or edit exising ones in the usual way.', 'qnrwp').'</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'edit_sample_cards_help',
                                  'title'     => __('Sample Cards Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    } else if ($screen->id == 'qnrwp_sample_card') {
      //$screen->remove_help_tabs();
      $html = '<p>'.__('<em>How to create a Sample Card:</em>', 'qnrwp');
      $html .= '<ol>';
      $html .= '<li>'.__('Enter the <strong>title</strong> of the card. This should be similar to or the same as the name of the sample that the card is about.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Enter a short <strong>description</strong> of the sample in the main textarea. For good SEO, include the most important keyword.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Assign a <strong>Sample Card Image</strong> for the card. Image proportions should be 2:1, and width at least 768px, the media size the card will use by default. Other sizes may be specified in the shortcode and your images should match.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Fill out the <strong>Sample URL</strong> field with the URL of the sample the card should link to.', 'qnrwp').'</li>';
      $html .= '<li>'.__('If you have written a News post about the sample, fill out the <strong>Sample Info URL</strong> field with the URL of the post.', 'qnrwp').'</li>';
      $html .= '<li>'.__('Save and preview the card. If something does not look right, repeat the process until the card is perfect.', 'qnrwp').'</li>';
      $html .= '</ol>';
      $html .='</p>';
      $screen->add_help_tab(array(
                                  'id'        => 'sample_cards_help',
                                  'title'     => __('Sample Cards Help', 'qnrwp'),
                                  'content'   => $html,
                                  'priority'  => 10,
                                  ));
      $screen->set_help_sidebar($sHtml);
    }
  }
  
  
  /**
   * Creates meta boxes to be displayed on the post editor screen
   */
  public function add_post_meta_boxes() {
    add_meta_box(
      'qnrwp_sample_card_sample_url',                   // Unique ID
      esc_html__('Sample URL', 'qnrwp'),                // Title
      array($this, 'sample_url_meta_box'),              // Callback function to echo the HTML
      'qnrwp_sample_card',                              // Admin page (or post type); single screen ID, WP_Screen object, or array of screen IDs
      'normal',                                         // Context (normal / advanced / side)
      'low',                                            // Priority (default / core / high / low)
      null                                              // Callback args array, as the second parameter to callback function
   );
    add_meta_box(
      'qnrwp_sample_card_info_url',                     // Unique ID
      esc_html__('Sample Info URL', 'qnrwp'),           // Title
      array($this, 'sample_info_url_meta_box'),         // Callback function to echo the HTML
      'qnrwp_sample_card',                              // Admin page (or post type); single screen ID, WP_Screen object, or array of screen IDs
      'normal',                                         // Context (normal / advanced / side)
      'low',                                            // Priority (default / core / high / low)
      null                                              // Callback args array, as the second parameter to callback function
   );
  }
  
  
  /**
   * Displays the Sample URL meta box
   */
  public function sample_url_meta_box($post) { ?>
    <p>
      <label for="qnrwp_sample_card_sample_url"><?php esc_html_e('Enter the URL of the sample that the card should link to:', 'qnrwp'); ?></label>
      <br>
      <input class="widefat" type="url" 
                name="qnrwp_sample_card_sample_url" 
                id="qnrwp_sample_card_sample_url" 
                value="<?php echo esc_attr(get_post_meta($post->ID, 'qnrwp_sample_card_sample_url', true)); ?>" 
                size="30">
    </p>
  <?php }
  
  
  /**
   * Displays the Sample Info URL meta box
   */
  public function sample_info_url_meta_box($post) { ?>
    <p>
      <label for="qnrwp_sample_card_info_url"><?php esc_html_e('If you have written a News post about the sample, enter the post URL:', 'qnrwp'); ?></label>
      <br>
      <input class="widefat" type="url" 
                name="qnrwp_sample_card_info_url" 
                id="qnrwp_sample_card_info_url" 
                value="<?php echo esc_attr(get_post_meta($post->ID, 'qnrwp_sample_card_info_url', true)); ?>" 
                size="30">
    </p>
  <?php }

  
  /**
   * Saves the Sample Card meta data
   */
  public function save_sample_meta($post_id, $post) {
    if ($post->post_type == 'qnrwp_sample_card') {
      $sampleURL = isset($_POST['qnrwp_sample_card_sample_url']) ? filter_var($_POST['qnrwp_sample_card_sample_url'], FILTER_VALIDATE_URL) : '';
      update_post_meta($post_id, 'qnrwp_sample_card_sample_url', $sampleURL);
      $sampleInfoURL = isset($_POST['qnrwp_sample_card_info_url']) ? filter_var($_POST['qnrwp_sample_card_info_url'], FILTER_VALIDATE_URL) : '';
      update_post_meta($post_id, 'qnrwp_sample_card_info_url', $sampleInfoURL);
    }
  }

  
  /**
   * Returns samples HTML
   * 
   * May be called from shortcode or Ajax request, statically
   */
  public static function get_samples_html($sampleName, $sampleSize, $samplesNumber, $pageNumber) {
    $rHtml = '';
    $availablePostsCount = wp_count_posts('qnrwp_sample_card')->publish;
    if (!$availablePostsCount) return '';
    // We have cards
    $query = new WP_Query(apply_filters('qnrwp_sample_cards_query', array(
                                'post_type'       => 'qnrwp_sample_card',
                                'post_status'     => 'publish',
                                'nopaging'        => false,
                                'posts_per_page'  => $samplesNumber,
                                'paged'           => $pageNumber,
                                )));
    if ($query->have_posts()) {
      if ($pageNumber == 1) {
        // Create row and title opener 
        ob_start(); ?>
        <!-- Samples Row -->
        <div class="qnrwp-samples-row">
          <h2 class="qnrwp-samples-list-title"><?php echo esc_html($sampleName); // Place before the block ?></h2>
          <!-- Samples List -->
          <div class="qnrwp-samples-list-block">
      <?php $rHtml = ob_get_clean();
      }
      
      while ($query->have_posts()) {
        $query->the_post(); // Each sample card
        ob_start();
        QNRWP_Samples::render_sample_card($sampleSize);
        $rHtml .= ob_get_clean();
      }
      wp_reset_postdata();
      
      if ($availablePostsCount <= ($samplesNumber * $pageNumber)) 
              $rHtml .= '<!-- All qnrwp_sample_cards displayed -->'.PHP_EOL; // For JS Ajax caller to know when to delete 'Load more' button
      if ($pageNumber == 1) {
        $rHtml .= '</div><!-- End of Samples List -->'.PHP_EOL;
        if ($availablePostsCount > $samplesNumber) {
          // If another page of results is available, insert "Load more" button into row, after List flex
          $rHtml .= '<button class="qnrwp-samples-load-more" 
                        onclick="QNRWP.Samples.load_more(this,event,'
                        .'\''.esc_attr($sampleName).'\','
                        .'\''.esc_attr($sampleSize).'\','
                        .esc_attr($samplesNumber).','
                        .(esc_attr($pageNumber)+1)
                        .')">'.apply_filters('qnrwp_sample_cards_load_more_label', esc_html__('Load more', 'qnrwp')).'</button>'.PHP_EOL;
        }
        $rHtml .= '</div><!-- End of Samples Row -->'.PHP_EOL;
      }
      return $rHtml;
    } else return ''; // Shouldn't get here
  }
  
  
  /**
   * Returns samples HTML, called by QNRWP Ajax handler, using POST to pass datajson
   */
  public static function ajax_more_samples($datajson) {
    // 
    $dataL = json_decode($datajson, $assoc = true);
    // On error, returned string must begin with "ERROR:"
    if (!isset($dataL) || empty($dataL)) return 'ERROR: '.__('No request parameters sent', 'qnrwp');
    $rT = self::get_samples_html($dataL['sampleName'],
                                  $dataL['sampleSize'],
                                  $dataL['samplesNumber'],
                                  $dataL['pageNumber']);
    if (!$rT) return 'ERROR: '.__('No samples found', 'qnrwp');
    else return $rT;
  }
  
  
  /**
   * Returns one sample card HTML, called from this class or template
   * 
   * Assumes that a WP_Query has been initiated
   */
  public static function render_sample_card($sampleSize) {
    $sampleLink = get_post_meta(get_the_ID(), 'qnrwp_sample_card_sample_url', true) ? get_post_meta(get_the_ID(), 'qnrwp_sample_card_sample_url', true) : '';
    $sampleInfo = get_post_meta(get_the_ID(), 'qnrwp_sample_card_info_url', true) ? get_post_meta(get_the_ID(), 'qnrwp_sample_card_info_url', true) : '';
    // Construct item HTML
    $imageLink = $sampleLink ? $sampleLink : $sampleInfo; ?>

    <!-- Samples List Item -->
    <div class="qnrwp-samples-list-item">
      <?php if (has_post_thumbnail(get_the_ID())): ?>
        <?php if ($imageLink): ?><a href="<?php echo filter_var($imageLink, FILTER_VALIDATE_URL); ?>"><?php endif; the_post_thumbnail(get_the_ID(), $sampleSize, array('class' => 'qnrwp-samples-list-item-img')); ?><?php if ($imageLink): ?></a><?php endif; ?>
      <?php endif; ?>
      <div class="qnrwp-samples-list-item-text">
        <h3><?php the_title(); ?></h3>
        <?php the_content(); ?>
      </div>
      <div class="qnrwp-samples-list-item-buttons">
        <?php if ($sampleInfo): ?>
          <a href="<?php echo filter_var($sampleInfo, FILTER_VALIDATE_URL); ?>" title="<?php esc_attr_e('More info', 'qnrwp'); ?>"><span class="qnr-glyph qnr-glyph-info"></span></a>
        <?php endif; 
        if ($sampleLink): ?>
          <a href="<?php echo filter_var($sampleLink, FILTER_VALIDATE_URL); ?>" title="<?php esc_attr_e('View the sample', 'qnrwp'); ?>"><span class="qnr-glyph qnr-glyph-openpage"></span></a>
        <?php endif; ?>
      </div>
    </div><!-- End of Samples List Item --><?php 
  }

} // End QNRWP_Samples class
