<?php

// settings_page_qnrwp_theme_menu is the slug of this page TODO ??

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
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

  <h1><?php esc_html_e('QNRWP Documentation', 'qnrwp'); ?></h1>
  
  <div id="tabs-documentation">
    <?php
      require_once QNRWP_DIR . 'inc/markdown/MarkdownInterface.php';
      require_once QNRWP_DIR . 'inc/markdown/Markdown.php';
      require_once QNRWP_DIR . 'inc/markdown/MarkdownExtra.php';
    ?>
  
    <div id="tab-documentation-content">
      <?php
        $fileText = file_get_contents(QNRWP_DIR . 'documentation/README_CONTENT_EDITING.md');
        //$MD = new Markdown();
        echo Michelf\MarkdownExtra::defaultTransform($fileText);
      ?>
    </div>

  </div><!-- Tabs documentation end -->
    
</div><!-- WP wrap end -->
