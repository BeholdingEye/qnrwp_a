<?php

// settings_page_qnrwp_theme_options_submenu is the slug of this page

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
  
  <h1><?php esc_html_e('QNRWP Theme Settings', 'qnrwp'); ?></h1>
  <form method="post" action="options.php"> 
    <?php settings_fields('qnrwp-group'); ?>
    
    <h2 class="title"><?php esc_html_e('Optional theme features', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('Enable or disable features according to requirements. Disabling unused features will reduce the amount of code that will be loaded.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Contact forms -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Contact forms', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-contactforms]" id="qnrwp_settings_array_feature-contactforms" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-contactforms'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-contactforms'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Contact forms', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution, perhaps for Mailchimp integration, which QNRWP contact forms do not provide.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Carousels -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Carousels', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-carousels]" id="qnrwp_settings_array_feature-carousels" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-carousels'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-carousels'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Carousels', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution, perhaps for swiping support on mobiles, which QNRWP carousels do not feature.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Subheaders -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Subheaders', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-subheaders]" id="qnrwp_settings_array_feature-subheaders" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-subheaders'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-subheaders'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Subheaders', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution, or do not require subheaders.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Sample Cards -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Sample cards', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-samplecards]" id="qnrwp_settings_array_feature-samplecards" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-samplecards'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-samplecards'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Sample cards', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution, or do not require sample cards.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Meta Tags -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Meta tags', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-metatags]" id="qnrwp_settings_array_feature-metatags" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-metatags'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-metatags'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Meta tags', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution, or do not require meta tags.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Main Nav Menu -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Main navigation menu', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-mainnavmenu]" id="qnrwp_settings_array_feature-mainnavmenu" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-mainnavmenu'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-mainnavmenu'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP Javascript widget for the main navigation menu', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party menu solution for the main navigation menu in the header.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Menu shortcode -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Menu shortcode', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-menushortcode]" id="qnrwp_settings_array_feature-menushortcode" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-menushortcode'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-menushortcode'], false)
                                  : ''; // Unchecked by default
                                  ?>>
                                <?php esc_html_e('Enable QNRWP menu shortcode', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('Enable menu shortcode that allows placing menus in arbitrary places. This uses Quicknr Interface Hierarchical Menu Javascript widget that is powerful but difficult to work with.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Bootstrap -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Bootstrap', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[feature-bootstrap]" id="qnrwp_settings_array_feature-bootstrap" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['feature-bootstrap'])
                                  ? checked(1, get_option('qnrwp_settings_array')['feature-bootstrap'], false)
                                  : ''; // Unchecked by default
                                  ?>>
                                <?php esc_html_e('Enable Bootstrap 4.3.1', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('Use at your own risk: Bootstrap 4.3.1 requires jQuery 1.9.1 - 3, WordPress uses jQuery 1.12.4. If in doubt, leave this disabled.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>
    
    <h2 class="title"><?php esc_html_e('Cookie notice', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('EU cookie and privacy notice compliance. The notice is required if persistent cookies are used.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Cookie notice -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Cookie notice', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[cookie-notice]" id="qnrwp_settings_array_cookie-notice" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['cookie-notice'])
                                  ? checked(1, get_option('qnrwp_settings_array')['cookie-notice'], false)
                                  : '';
                                  ?>>
                                <?php esc_html_e('Display cookie/privacy notice', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('You may like to disable this if using a third-party solution or do not require the notice.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Textarea -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Notice text', 'qnrwp'); ?></th>
        <td><p><?php esc_html_e('Enter the text of the notice, excluding any link to a Privacy or Terms page.', 'qnrwp') ?></p>
        <textarea style="width:100%;"
                  name="qnrwp_settings_array[cookie-notice-text]" id="qnrwp_settings_array_cookie-notice-text"><?php 
                                    if (isset(get_option('qnrwp_settings_array')['cookie-notice-text'])
                                              && !empty(get_option('qnrwp_settings_array')['cookie-notice-text']))
                                      echo esc_html(get_option('qnrwp_settings_array')['cookie-notice-text']); 
                                    else echo esc_html_e('By using this site you agree to our use of cookies.', 'qnrwp');
                                    ?></textarea></td>
      </tr>
      
      <!-- Post ID -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Terms page Post ID', 'qnrwp') ?></th>
        <td><p><?php esc_html_e('Enter the Post ID number of a Privacy or Terms page that will be linked to after the notice. May be left blank if no such link is to appear.', 'qnrwp') ?></p>
        <input type="number" name="qnrwp_settings_array[cookie-notice-postid]" id="qnrwp_settings_array_cookie-notice-postid" 
                                  value="<?php 
                                    if (isset(get_option('qnrwp_settings_array')['cookie-notice-postid'])
                                              && !empty(get_option('qnrwp_settings_array')['cookie-notice-postid']))
                                      echo esc_attr(get_option('qnrwp_settings_array')['cookie-notice-postid']); 
                                    ?>"></td>
      </tr>
      
      <!-- Link text -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Link text', 'qnrwp') ?></th>
        <td><p><?php esc_html_e('Enter the text of the link. May be left blank for no link.', 'qnrwp') ?></p>
        <input type="text" name="qnrwp_settings_array[cookie-notice-linktext]" id="qnrwp_settings_array_cookie-notice-linktext" 
                                  value="<?php 
                                    if (isset(get_option('qnrwp_settings_array')['cookie-notice-linktext'])
                                              && !empty(get_option('qnrwp_settings_array')['cookie-notice-linktext']))
                                      echo esc_attr(get_option('qnrwp_settings_array')['cookie-notice-linktext']); 
                                    else echo esc_attr_e('Details', 'qnrwp');
                                  ?>"></td>
      </tr>
      
      <!-- Checkbox: Placement -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Placement', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[cookie-notice-placement]" id="qnrwp_settings_array_cookie-notice-placement" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['cookie-notice-placement'])
                                  ? checked(1, get_option('qnrwp_settings_array')['cookie-notice-placement'], false)
                                  : '';
                                  ?>> 
                                <?php esc_html_e('Place the notice at the end of the document, below the footer', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('If this is turned off, the notice will be at the top of the document, above the header.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: CSS Position -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('CSS Position', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[cookie-notice-cssposition]" id="qnrwp_settings_array_cookie-notice-cssposition" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['cookie-notice-cssposition'])
                                  ? checked(1, get_option('qnrwp_settings_array')['cookie-notice-cssposition'], false)
                                  : '';
                                  ?>> 
                                <?php esc_html_e('Position the notice fixed to the window edge above the document content', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('If this is turned off, the notice will be positioned in the document content.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>

    <h2 class="title"><?php esc_html_e('Layout options', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('Layout preferences.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Fixed header -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Fixed header', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[header-fixed]" id="qnrwp_settings_array_header-fixed" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['header-fixed'])
                                  ? checked(1, get_option('qnrwp_settings_array')['header-fixed'], false)
                                  : 'checked="checked"';
                                  ?>> 
                                <?php esc_html_e('Fix header to top of window, not moving up out of view on scroll', 'qnrwp'); ?></label></td>
      </tr>
      
      <!-- Max page width -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Max page width', 'qnrwp'); ?></th>
        <td><p><?php esc_html_e('Enter the maximum pixel width for pages, between 1200 and 2500. If left blank, the default of 1600 will be used. This setting will set the "max-width" CSS attribute on ".header-row, .content-row, .middle-row, .content-box, .footer-row". You must ensure that page components can accommodate.', 'qnrwp'); ?></p>
        <input type="number" name="qnrwp_settings_array[max-page-width]" id="qnrwp_settings_array_max-page-width" 
                                  min="1200"
                                  max="2500"
                                  value="<?php 
                                    if (isset(get_option('qnrwp_settings_array')['max-page-width'])
                                              && !empty(get_option('qnrwp_settings_array')['max-page-width']))
                                      echo max(min(intval(esc_attr(get_option('qnrwp_settings_array')['max-page-width'])), 2500), 1200);
                                    else echo 1600;
                                  ?>"></td>
      </tr>
      
    </table>
    
    <hr>
      
    <h2 class="title"><?php esc_html_e('Widgets', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('Widgets preferences.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Widget visibility -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Widget visibility', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[widget-visibility]" id="qnrwp_settings_array_widget-visibility" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['widget-visibility'])
                                  ? checked(1, get_option('qnrwp_settings_array')['widget-visibility'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>> 
                                <?php esc_html_e('Enable widget visibility control by this theme and the resulting automation of page layout', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('This should only be turned off if using another widget visibility solution such as Jetpack, in which case this theme provides hooks for alternative layout functionality to be coded in the child theme.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>
      
    <h2 class="title"><?php esc_html_e('News posts', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('News posting preferences.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Featured Image -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Featured Image', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[news-featured-image]" id="qnrwp_settings_array_news-featured-image" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['news-featured-image'])
                                  ? checked(1, get_option('qnrwp_settings_array')['news-featured-image'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>> 
                                <?php esc_html_e('Place Featured Image at the start of the post', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('If this is turned off, the Featured Image shortcode may be used to place the image in the post. Either way, the Featured Image is required for the post thumbnail on the News page.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: WP Emojis -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('WordPress emojis', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[news-wpemojisdisabled]" id="qnrwp_settings_array_news-wpemojisdisabled" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['news-wpemojisdisabled'])
                                  ? checked(1, get_option('qnrwp_settings_array')['news-wpemojisdisabled'], false)
                                  : 'checked="checked"'; // Checked by default
                                  ?>>
                                <?php esc_html_e('Disable WordPress emojis', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('Uncheck this if you would like to enable WP emojis. They are a blogging feature. QNRWP being a business theme, you may prefer to keep them disabled.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>

    <h2 class="title"><?php esc_html_e('Code options', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('Coding preferences.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Code combine -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Code combine', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[code-combine]" id="qnrwp_settings_array_code-combine" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['code-combine'])
                                  ? checked(1, get_option('qnrwp_settings_array')['code-combine'], false)
                                  : 'checked="checked"';
                                  ?>> 
                                <?php esc_html_e('Combine and minify theme Javascript and CSS code files', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('This should usually be turned on, but may be turned off for debugging. If the PHP process cannot write to the theme and child theme directories, the files will not be minified and combined.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>
      
    <h2 class="title"><?php esc_html_e('Admin interface', 'qnrwp'); ?></h2>
    <p><?php esc_html_e('Admin interface preferences.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkbox: Simplify -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Simplify interface', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[admin-simplify]" id="qnrwp_settings_array_admin-simplify" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['admin-simplify'])
                                  ? checked(1, get_option('qnrwp_settings_array')['admin-simplify'], false)
                                  : 'checked="checked"';
                                  ?>> 
                                <?php esc_html_e('Simplify admin interface for editors and other non-admins', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('This will not prevent access to things that WP permits access to according to user permissions, but will streamline the interface to better focus on content.', 'qnrwp'); ?></em></p></td>
      </tr>
      
      <!-- Checkbox: Wider Editor -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Wider block editor', 'qnrwp'); ?></th>
        <td><label><input type="checkbox" name="qnrwp_settings_array[admin-wider-editor]" id="qnrwp_settings_array_admin-wider-editor" 
                                value="1" <?php 
                                  echo isset(get_option('qnrwp_settings_array')['admin-wider-editor'])
                                  ? checked(1, get_option('qnrwp_settings_array')['admin-wider-editor'], false)
                                  : 'checked="checked"';
                                  ?>> 
                                <?php esc_html_e('Make the block editor wider', 'qnrwp'); ?></label>
                                <p><em><?php esc_html_e('Disable this option if you encounter problems or a WP update solves the problem of the narrow block editor.', 'qnrwp'); ?></em></p></td>
      </tr>
      
    </table>
    
    <hr>

    <?php submit_button(); ?>
  </form>
</div><!-- WP wrap end -->