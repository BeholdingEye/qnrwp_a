<?php

defined( 'ABSPATH' ) || exit;

/**
 * Widgets class, for both frontend and admin (a singleton)
 */
class QNRWP_Widgets {
  
  use QNRWP_Singleton_Trait;
  
  
  /**
   * Class constructor
   */
  protected function __construct() {
    $this->hooks();
  }
  
  
  /**
   * Filter and action hooks related to widgets
   */
  private function hooks() {
    // Output page selection checkboxes (admin)
    add_action('in_widget_form', array($this, 'page_selection_checkboxes'), 10, 3);
    
    // Filter widget settings before saving (admin)
    add_filter('widget_update_callback', array($this, 'update_widget_settings'), 10, 4);
    
    // Conditionally display widget content (frontend)
    add_filter('widget_display_callback', array($this, 'display_widget_content'), 10, 3);
    
    // Populate our global with widget data
    add_filter('sidebars_widgets', array($this, 'sidebars_widgets'));
    
    // Widget titles
    add_filter('widget_title', array($this, 'remove_widget_title'));
  }
  
  
  /**
   * Removes titles on widgets that shouldn't have them displayed
   * 
   * Note that our "style.css" also declares ".widget_custom_html .widget-title"
   * to not display
   */
  public function remove_widget_title($wtitle) {
    $originalWidgetTitle = $wtitle;
    $newWidgetTitle = $wtitle;
    if (  stripos($wtitle, 'Copyright') !== false || 
          stripos($wtitle, 'Menu') !== false || 
          stripos($wtitle, 'Logo') !== false || 
          stripos($wtitle, 'Search') !== false || 
          stripos($wtitle, 'Social Links') !== false   ) {
      $newWidgetTitle = '';
    }
    // Let the child theme decide
    $newWidgetTitle = apply_filters('qnrwp_widget_title', $newWidgetTitle, $originalWidgetTitle);
    return $newWidgetTitle;
  }
  

  /**
   * Outputs page selection checkboxes, for widget to appear on
   */
  public function page_selection_checkboxes($widget, $return, $instance) {
    if (QNRWP::get_setting('widget-visibility') === 0)
          return;
    if ($widget->number != '__i__') {
      // Page(s) to display the widget field
      $pagesVal = !empty($instance['qnrwp_pages']) ? $instance['qnrwp_pages'] : '';
      $pagesL = array();
      if ($pagesVal) $pagesL = explode(',', $pagesVal);
      $pagesOutputID = esc_attr($widget->get_field_id('qnrwp_pages'));
      $pagesOutputName = esc_attr($widget->get_field_name('qnrwp_pages'));
      // HTML form
      echo self::get_pages_form_for_widget($pagesVal, $pagesL, $pagesOutputID, $pagesOutputName);
    }
  }
  
  
  /**
   * Returns HTML to use in a widget form so pages can be selected for widget to appear on
   * 
   * @param   string      $pagesVal   Value that is stored in db
   * @param   array       $pagesL     Array exploded from pagesVal, listing checked pages options
   * @param   int         $outputID   Output field ID matching the ID for saving the value that is updated by JS on checkbox clicks
   * @param   string      $outputName Output field Name
   */
  public static function get_pages_form_for_widget($pagesVal, $pagesL, $outputID, $outputName) {
    $pages = get_pages(); // 'publish' status only
    $rP = '';
    foreach ($pages as $page) {
      // Test if this page is in $pagesL array parameter, select if so
      $checked = in_array($page->ID, $pagesL) ? ' checked="checked"' : ''; // Don't convert page ID to string...
      $rP .= '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
                          .' value="'.$page->ID.'">'.esc_html($page->post_title).'</label><br>'.PHP_EOL;
    }
    //if ($rP) {
    
    // All custom post types, if any (reverse order of concatenating)
    $customPostTypesL = get_post_types(array('public'=>true,'_builtin'=>false), 'names', 'and'); // Names of custom post types
    if ($customPostTypesL) {
      $checked = in_array('-3', $pagesL) ? ' checked="checked"' : '';
      $rP = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
              .' value="-3" name="qnrwp-all-cpt" id="qnrwp-all-cpt">'.esc_html__('All Custom Post Types', 'qnrwp').'</label><br>'.PHP_EOL . $rP;
    }
    // All News Posts checkbox
    $checked = in_array('-1', $pagesL) ? ' checked="checked"' : '';
    $rP = '&nbsp;&nbsp;<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-1" name="qnrwp-all-news" id="qnrwp-all-news">'.esc_html__('All News Posts', 'qnrwp').'</label><br>'.PHP_EOL . $rP;
    // All Pages Except the Following checkbox
    $checked = in_array('-2', $pagesL) ? ' checked="checked"' : '';
    $rP = '<label><input onclick="javascript:qnrwp_collect_pages_options_for_widget(event)" type="checkbox"'.$checked
            .' value="-2" name="qnrwp-all-except" id="qnrwp-all-except">'.esc_html__('All except the following', 'qnrwp').':</label><br>'.PHP_EOL . $rP;
    // Input field collecting the checked values as a setting to save, ID passed in param, as well as previous value
    // Class attribute is for JS identification as the ID and name are set by WP code
    //$rP .= esc_html__('Output', 'qnrwp').': <input name="'.$outputName.'" id="'.$outputID.'" class="qnrwp-setting-output-field" value="'.$pagesVal.'">'.PHP_EOL;
    $rP .= '<input type="hidden" name="'.$outputName.'" id="'.$outputID.'" class="qnrwp-setting-output-field" value="'.$pagesVal.'">'.PHP_EOL; // 
    // No <form> wrap, that's handled by the WP widget code, but DIV parent wrap for JS
    $rPWrap = '<hr style="margin: 1em 0;">';
    $rPWrap .= '<p>'.esc_html__('Select the page(s) to display this Widget. Click "All except the following:" to display on all, excluding the selected.', 'qnrwp').'</p>'.PHP_EOL;
    $rPWrap .= '<div style="box-sizing:border-box;padding:0 1em 1em;height:auto;overflow:auto;border:none">'.PHP_EOL; // solid thin #EEE
    $rP = $rPWrap . $rP;
    $rP .= '</div>'.PHP_EOL;
    
    //} else $rP = '<p>'.esc_html__('No pages that could display the widget exist yet.', 'qnrwp').'</p>';
    return $rP;
  }
  
  
  /**
   * Updates widget settings
   */
  public function update_widget_settings($instance, $new_instance, $old_instance, $widget) {
    // Child theme may use the same WP hook (at priority 11+) for its own functionality if our widget visibility is turned off
    if (QNRWP::get_setting('widget-visibility') === 0)
          return $instance;
		//$instance = array();
		$instance['qnrwp_pages'] = (!empty($new_instance['qnrwp_pages'])) ? strip_tags($new_instance['qnrwp_pages']) : '';
		$instance['qnrwp_widget'] = (!empty($new_instance['qnrwp_widget'])) ? strip_tags($new_instance['qnrwp_widget']) : '';
		return $instance;
  }
  
  
  /**
   * Displays widget content if set so in widget form
   */
  public function display_widget_content($instance, $widget, $args) {
    //qnrwp_debug_printout('----------', $append=true);
    //qnrwp_debug_printout($args, $append=true);
    if (QNRWP::get_setting('widget-visibility') === 0) {
      // Offer child theme to control setting of our global required by query filter in our news class
      //if (apply_filters('qnrwp_widget_being_shown', true)) $GLOBALS['QNRWP_GLOBALS']['widgetBeingShown'] = $widget->id_base;
      // Child theme may use the same WP hook (at priority 11+) for its own functionality; returning false will prevent widget display
      return $instance;
    }
    $pagesL = array();
    if (isset($instance['qnrwp_pages'])) $pagesL = explode(',', $instance['qnrwp_pages']);
    // Decide whether to show the widget on this page
    $showWidget = self::is_widget_visible($pagesL);
    //if ($showWidget) {
      //$GLOBALS['QNRWP_GLOBALS']['widgetBeingShown'] = $widget->id_base; // For query filter in our news class
    //}
    return ($showWidget !== false) ? $instance : false; // If false is returned, widget won't display
  }
  
  
  /**
   * Returns true/false if widget is to be shown on page
   */
  public static function is_widget_visible($pagesL) {
    $allExcept = in_array('-2', $pagesL);
    $allNews = in_array('-1', $pagesL);
    $allCPT = in_array('-3', $pagesL);
    // News page has a different page ID from its post ID; search page is treated same as news page
    $thisPage = (is_home() || is_search() || is_archive()) ? get_option('page_for_posts') : get_the_ID();
    $showWidget = false;
    $postType = get_post_type();
    $customPostTypesL = get_post_types(array('public'=>true,'_builtin'=>false), 'names', 'and'); // Names of custom post types
    if ($customPostTypesL) {
      foreach ($customPostTypesL as $CPT) {
        if (($postType == $CPT && ! (is_home() || is_search() || is_archive()) && $allCPT && ! $allExcept)
              || ($postType == $CPT && ! (is_home() || is_search() || is_archive()) && ! $allCPT && $allExcept)) {
          $showWidget = true;
          break;
        }
      }
    } // Careful with the ifs and $showWidget value...
    if ($postType == 'post' && ! (is_home() || is_search() || is_archive()) && $allNews && ! $allExcept) $showWidget = true;
    else if ($postType == 'post' && ! (is_home() || is_search() || is_archive()) && ! $allNews && $allExcept) $showWidget = true;
    else if (($postType == 'page' || (is_home() || is_search() || is_archive())) && in_array($thisPage, $pagesL) && ! $allExcept) $showWidget = true;
    else if (($postType == 'page' || (is_home() || is_search() || is_archive())) && ! in_array($thisPage, $pagesL) && $allExcept) $showWidget = true;
    else if ($GLOBALS['QNRWP_GLOBALS']['pageTemplate'] == '404.php' && $allExcept) $showWidget = true;
    return $showWidget;
  }
  
  
  /**
   * Our more developed way of finding out if sidebar is active, considers widget visibility
   * 
   * Called from QNRWP::get_layout() and from template
   */
  public static function is_active_sidebar_widgets($sidebar) {
    // The is_active_sidebar() call will fire the 'sidebars_widgets' hook, producing our $GLOBALS['QNRWP_GLOBALS']['sidebarsWidgets']
    // We offer the child theme an alternative if our widget visibility is turned off; the child theme will have access to our global
    if (QNRWP::get_setting('widget-visibility') === 0)
          return apply_filters('qnrwp_is_active_sidebar', is_active_sidebar($sidebar));
    if (!is_active_sidebar($sidebar)) return false; // No widgets in sidebar
    else {
      foreach ($GLOBALS['QNRWP_GLOBALS']['sidebarsWidgets'][$sidebar] as $widgetInstanceID) {
        // If widget is active on this page, return true
        $wiBase = preg_replace('/-\d+$/', '', $widgetInstanceID);
        if ($wiBase !== $widgetInstanceID) { // Multiwidget, the usual kind TODO single use widgets??
          $wiNumber = substr($widgetInstanceID, strrpos($widgetInstanceID, '-')+1);
          $wiOption = get_option('widget_'.$wiBase);
          $pagesL = array();
          if (isset($wiOption[$wiNumber]['qnrwp_pages'])) $pagesL = explode(',', $wiOption[$wiNumber]['qnrwp_pages']);
          if (self::is_widget_visible($pagesL)) return true;
        }
      }
      return false; // No widgets marked to display on this page in this sidebar
    }
  }
  
  
  /**
   * Filters sidebar_widgets when is_active_sidebar() is run, so we can populate our global with widget data instead of running an internal WP function
   */
  public function sidebars_widgets($sidebars_widgets) {
    $GLOBALS['QNRWP_GLOBALS']['sidebarsWidgets'] = $sidebars_widgets;
    return $sidebars_widgets;
  }

} // End QNRWP_Widgets class

