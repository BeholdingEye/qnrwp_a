<?php

defined( 'ABSPATH' ) || exit;

/**
 * QNRWP samples class, used by shortcode
 */
class QNRWP_Samples {
  
  /**
   * Returns samples HTML
   * 
   * May be called from shortcode or Ajax request, statically
   */
  public static function get_samples_html($sampleName, $sampleCategories, $sampleSize, $samplesNumber, $pageNumber) {
    $rHtml = '';
    // Get posts matching criteria
    $thePosts = get_posts(array(
                                'post_type' => 'post',
                                'nopaging' => false,
                                'posts_per_page' => $samplesNumber,
                                'paged' => $pageNumber,
                                'category_name' => $sampleCategories,
                                ));
    if ($thePosts) {
      // Get the next page of results to see if there are any
      $thePosts1 = get_posts(array(
                                  'post_type' => 'post',
                                  'nopaging' => false,
                                  'posts_per_page' => $samplesNumber,
                                  'paged' => $pageNumber + 1,
                                  'category_name' => $sampleCategories,
                                  ));
      $samplesCount = 0;
      if ($pageNumber == 1) {
        $rHtml .= '<!-- Samples Row -->'.PHP_EOL;
        $rHtml .= '<div class="qnrwp-samples-row">'.PHP_EOL;
        $rHtml .= '<h2 class="qnrwp-samples-list-title">'.$sampleName.'</h2>'.PHP_EOL; // Place before the block
        $rHtml .= '<!-- Samples List -->'.PHP_EOL;
        $rHtml .= '<div class="qnrwp-samples-list-block">'.PHP_EOL; // Opening Samples List block
      }
      // Loop over posts
      foreach ($thePosts as $key => $thePost) {
        if (has_post_thumbnail($thePost->ID)) {
          // Custom meta values for the post
          // WP has tricky meta functions with multi-dimensional arrays,
          //   we use a relatively simple one TODO dynamic naming??
          $sampleInfo = get_post_custom_values('Sample-Info', $thePost->ID) ? get_post_custom_values('Sample-Info', $thePost->ID)[0] : '';
          $sampleLink = get_post_custom_values('Sample-Link', $thePost->ID) ? get_post_custom_values('Sample-Link', $thePost->ID)[0] : '';
          if (!$sampleInfo && !$sampleLink) continue;
          // Construct item HTML
          $imageLink = $sampleLink ? $sampleLink : $sampleInfo;
          $rHtml .= '<!-- Samples List Item -->'.PHP_EOL;
          $rHtml .= '<div class="qnrwp-samples-list-item">'.PHP_EOL;
          $rHtml .= '<a href="'.filter_var($imageLink, FILTER_VALIDATE_URL).'">';
          $rHtml .= get_the_post_thumbnail($thePost->ID, $sampleSize, array('class' => 'qnrwp-samples-list-item-img'));
          $rHtml .= '</a>';
          $rHtml .= '<div class="qnrwp-samples-list-item-text">'.PHP_EOL;
          $rHtml .= '<h3>'.get_the_title($thePost->ID).'</h3>'.PHP_EOL;
          $rHtml .= apply_filters('the_content', $thePost->post_content);
          $rHtml .= '</div>'.PHP_EOL; // End of item text
          $rHtml .= '<div class="qnrwp-samples-list-item-buttons">'.PHP_EOL;
          if ($sampleInfo) {
            $rHtml .= '<a href="'.filter_var($sampleInfo, FILTER_VALIDATE_URL).'" title="'.esc_attr__('More info', 'qnrwp').'"><span class="qnr-glyph qnr-glyph-info"></span></a>';
          }
          if ($sampleLink) {
            $rHtml .= '<a href="'.filter_var($sampleLink, FILTER_VALIDATE_URL).'" title="'.esc_attr__('View the sample', 'qnrwp').'"><span class="qnr-glyph qnr-glyph-openpage"></span></a>'.PHP_EOL;
          }
          $rHtml .= '</div></div><!-- End of Samples List Item -->'.PHP_EOL; // 
          $samplesCount += 1;
        }
      }
      if ($samplesCount > 0) {
        if (!$thePosts1) $rHtml .= '<!-- All samples items displayed -->'.PHP_EOL; // For JS Ajax caller to know when to delete 'Load more' button
        if ($pageNumber == 1) {
          $rHtml .= '</div><!-- End of Samples List -->'.PHP_EOL;
          if ($thePosts1) {
            // If another page of results is available, insert "Load more" button into row, after List flex
            $rHtml .= '<button class="qnrwp-samples-load-more" 
                          onclick="QNRWP.Samples.load_more(this,event,'
                          .'\''.$sampleName.'\','
                          .'\''.$sampleCategories.'\','
                          .'\''.$sampleSize.'\','
                          .$samplesNumber.','
                          .($pageNumber+1)
                          .')">'.esc_html__('Load more', 'qnrwp').'</button>'.PHP_EOL;
          }
          $rHtml .= '</div><!-- End of Samples Row -->'.PHP_EOL;
        }
        return $rHtml; // Return nothing if no Samples List items obtained
      }
    }
    return ''; // Either no posts found or no samples
  }
  
  
  /**
   * Returns samples HTML, called by QNRWP Ajax handler, using POST to pass datajson
   */
  public static function ajax_more_samples($datajson) {
    // 
    $dataL = json_decode($datajson, $assoc = true);
    // On error, returned string must begin with "ERROR:"
    if (!isset($dataL) || empty($dataL)) return 'ERROR: '.__('No request parameters sent', 'qnrwp');
    $rT = self::get_samples_html(esc_attr($dataL['sampleName']),
                                  esc_attr($dataL['sampleCategories']),
                                  esc_attr($dataL['sampleSize']),
                                  esc_attr($dataL['samplesNumber']),
                                  esc_attr($dataL['pageNumber']));
    if (!$rT) return 'ERROR: '.__('No samples found', 'qnrwp');
    else return $rT;
  }

} // End QNRWP_Samples class
