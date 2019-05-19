<?php

// settings_page_qnrwp_theme_options_submenu is the slug of this page

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
  <style>
    div.wrap,
    div.wrap * {
      font-family: inherit;
      box-sizing: border-box;
    }
    div.wrap {
      font-size: inherit;
    }
    
    /* Overrides for jQuery tabs*/
    
    div#tabs > ul {
      margin: 0;
      padding: 0;
    }
    div#tabs > ul > li {
      display: inline-block;
      margin: 0;
      padding: 0;
      border: none;
      box-sizing: border-box;
      border-radius: 6px 6px 0 0;
      background: #ccc;
    }
    div#tabs > ul > li,
    div#tabs > ul > li > a {
      text-decoration: none;
      font-size: 1.1em;
      color: #666;
      box-shadow: none;
    }
    div#tabs > ul > li > a {
      display: block;
      position: relative;
      margin: 0;
      padding: 0.5em 1em 0.25em;
    }
    div#tabs > ul > li.ui-tabs-active {
      font-weight: bold;
      margin-top: 0;
      margin-bottom: 0;
      background: gray;
    }
    div#tabs > ul > li.ui-tabs-active,
    div#tabs > ul > li.ui-tabs-active > a {
      color: white;
    }
    div.tab-panel {
      display: block;
      position: relative;
      margin: 0;
      padding: 1em;
      width: 100%;
      border-top: solid thin gray;
    }
    
  </style>
  <script>
    jQuery(function() {
      jQuery("#tabs").tabs();
    });
  </script>
  <div id="tabs" class="qnrwp-settings-panels-wrap">
    
    <ul>
      <li><a href="#tab-settings"><?php esc_html_e('Settings', 'qnrwp'); ?></a></li>
      <li><a href="#tab-documentation"><?php esc_html_e('Documentation', 'qnrwp'); ?></a></li>
      <li><a href="#tab-tools"><?php esc_html_e('Tools', 'qnrwp'); ?></a></li>
    </ul>
    
    <!-- Tab 1 -->
    <div id="tab-settings" class="tab-panel">
      <h1><?php esc_html_e('QNRWP Settings', 'qnrwp'); ?></h1>
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
                                    <p><em><?php esc_html_e('This should usually be turned on, but may be turned off for debugging.', 'qnrwp'); ?></em></p></td>
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
                                    <p><em><?php esc_html_e('This will not prevent access to things that WP permits access to according to user permissions, but will streamline the interface to better focus on content. The most important change is that pages defining QNRWP Widgets will not appear on the Pages page.', 'qnrwp'); ?></em></p></td>
          </tr>
          
        </table>
        
        <hr>

        <?php submit_button(); ?>
      </form>
    </div><!-- Tab 1 end (settings) -->
    
    <!-- Tab 2 -->
    <div id="tab-documentation" class="tab-panel">
      <style>
        div#tabs-documentation,
        div#tabs-documentation * {
          box-sizing: border-box;
        }
        div#tabs-documentation {
          border: none;
          background: transparent;
        }
        div#tabs-documentation,
        div#tabs-documentation dt,
        div#tabs-documentation dd,
        div#tabs-documentation p {
          font-size: 110% !important;
        }
        div#tabs-documentation code {
          font-family: monospace;
        }
        div#tabs-documentation > ul {
          margin: 0;
          padding: 0;
          text-align: center;
          border: none;
          background: transparent;
        }
        div#tabs-documentation > ul li {
          display: inline-block;
          margin: 0;
          text-transform: uppercase;
          outline: none;
          border: solid thin silver;
          border-bottom: none;
          border-radius: 6px 6px 0 0;
          background: white;
        }
        div#tabs-documentation > ul li,
        div#tabs-documentation > ul li a {
          text-decoration: none;
          float: none;
          color: #666;
        }
        div#tabs-documentation > ul li a {
          display: block;
          position: relative;
          margin: 0;
          padding: 0.75em 1em 0.35em;
          box-shadow: none;
          border-bottom: solid 4px white;
        }
        div#tabs-documentation > ul li:hover a {
          color: black;
        }
        div#tabs-documentation > ul li.ui-tabs-active {
          padding-bottom: 0;
        }
        div#tabs-documentation > ul li.ui-tabs-active a {
          color: black;
          border-bottom: solid 4px #4f5b6c;
        }
        div#tabs-documentation > div {
          display: block
          position: relative;
          margin: 0 auto;
          max-width: 750px;
          border: none;
          border-top: solid thin silver;
        }
        div#tabs-documentation > div li {
          margin-left: 2em;
          margin-right: 1em;
          list-style-type: disc !important;
        }
        div#tabs-documentation > div h1 {
          margin-top: 1em;
          text-align: center;
        }
        div#tabs-documentation > div h3 {
          font-size: 1.1em;
        }
        
        div#tab-documentation-content ul li dl + p {
          margin-top: 0;
          margin-bottom: 2em;
        }
      </style>
      <script>
        jQuery(function() {
          jQuery("#tabs-documentation").tabs();
        });
      </script>
      
      <div id="tabs-documentation">
        <?php
          require_once QNRWP_DIR . 'inc/markdown/MarkdownInterface.php';
          require_once QNRWP_DIR . 'inc/markdown/Markdown.php';
          require_once QNRWP_DIR . 'inc/markdown/MarkdownExtra.php';
        ?>
        <ul>
          <li><a href="#tab-documentation-content"><?php esc_html_e('Content Editing', 'qnrwp'); ?></a></li>
          <li><a href="#tab-documentation-programming"><?php esc_html_e('Programming', 'qnrwp'); ?></a></li>
          <li><a href="#tab-documentation-todo"><?php esc_html_e('TODO', 'qnrwp'); ?></a></li>
        </ul>
        
        <div id="tab-documentation-content">
          <?php
            $fileText = file_get_contents(QNRWP_DIR . 'documentation/README_CONTENT_EDITING.md');
            //$MD = new Markdown();
            echo Michelf\MarkdownExtra::defaultTransform($fileText);
          ?>
        </div>
        
        <div id="tab-documentation-programming">
          <?php
            $fileText = file_get_contents(QNRWP_DIR . 'documentation/README_PROGRAMMING.md');
            //$MD = new Markdown();
            echo Michelf\MarkdownExtra::defaultTransform($fileText);
          ?>
        </div>
        
        <div id="tab-documentation-todo">
          <?php
            $fileText = file_get_contents(QNRWP_DIR . 'documentation/TODO.md');
            //$MD = new Markdown();
            echo Michelf\MarkdownExtra::defaultTransform($fileText);
          ?>
        </div>
      </div><!-- Tabs documentation end -->
      
    </div><!-- Tab 2 end (documentation) -->
    
    <!-- Tab 3 -->
    <div id="tab-tools" class="tab-panel">
      <p>
        Under construction.
      </p>
    </div><!-- Tab 3 end (tools) -->
    
  </div><!-- Panels wrap end -->
</div><!-- WP wrap end -->