<?php

// settings_page_qnrwp_theme_options_submenu is the slug of this page

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
                                    <?php esc_html_e('Display cookie/privacy notice', 'qnrwp'); ?></label></td>
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
                                        else echo esc_html('By using this site you agree to our use of cookies.');
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
                                        else echo esc_attr('Details');
                                        ?>"></td>
          </tr>
          
        </table>
        
        <hr>
        
        <h2 class="title"><?php esc_html_e('Favicon', 'qnrwp'); ?></h2>
        <p><?php esc_html_e('This theme does not support the Site Icon API, offering a simpler, lighter solution. The small favicon and the larger Apple icon are supported. They should be uploaded to the Media Library and their URLs entered in the fields below.', 'qnrwp'); ?></p>
        
        <table class="form-table">
          <!-- Favicon URL -->
          <tr valign="top">
            <th scope="row"><?php esc_html_e('Favicon URL', 'qnrwp') ?></th>
            <td><p><?php esc_html_e('Enter the favicon.ico URL. If left blank, a favicon will not be used. Dimensions should be 32px x 32px.', 'qnrwp') ?></p>
            <input type="text" name="qnrwp_settings_array[favicon-url]" id="qnrwp_settings_array_favicon-url" 
                                      style="width:100%;max-width:700px;"
                                      value="<?php 
                                        if (isset(get_option('qnrwp_settings_array')['favicon-url'])
                                                  && !empty(get_option('qnrwp_settings_array')['favicon-url']))
                                          echo esc_attr(get_option('qnrwp_settings_array')['favicon-url']); 
                                        ?>"></td>
          </tr>
          
          <!-- Apple icon URL -->
          <tr valign="top">
            <th scope="row"><?php esc_html_e('Apple icon URL', 'qnrwp') ?></th>
            <td><p><?php esc_html_e('Enter the Apple icon URL. If left blank, an Apple icon will not be used. Dimensions should be 256px x 256px, and the file in PNG format.', 'qnrwp') ?></p>
            <input type="text" name="qnrwp_settings_array[appleicon-url]" id="qnrwp_settings_array_appleicon-url" 
                                      style="width:100%;max-width:700px;"
                                      value="<?php 
                                        if (isset(get_option('qnrwp_settings_array')['appleicon-url'])
                                                  && !empty(get_option('qnrwp_settings_array')['appleicon-url']))
                                          echo esc_attr(get_option('qnrwp_settings_array')['appleicon-url']); 
                                        ?>"></td>
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
                                    <p><em><?php esc_html_e('If this is turned off, the Featured Image shortcode may be used to place the image. The shortcode may also be used as the first line in the post to override the Large image size used by default.', 'qnrwp'); ?></em></p></td>
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
          <li><a href="#tab-documentation-content">Content Editing</a></li>
          <li><a href="#tab-documentation-programming">Programming</a></li>
          <li><a href="#tab-documentation-todo">TODO</a></li>
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