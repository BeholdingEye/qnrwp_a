<?php

// settings_page_qnrwp_theme_menu is the slug of this page TODO ??

defined( 'ABSPATH' ) || exit;

if (isset($_POST['setup-tag']) && $_POST['setup-tag']) {
  if (isset($_POST['qnrwp_tools_create'])) QNRWP_Admin_Tools::create_pages();
  if (isset($_POST['qnrwp_tools_setuppages'])) QNRWP_Admin_Tools::setup_pages();
  if (isset($_POST['qnrwp_tools_settings'])) QNRWP_Admin_Tools::set_settings();
}
if (isset($_POST['blocks-tag']) && $_POST['blocks-tag']) {
  if (isset($_POST['qnrwp_tools_blocks'])) QNRWP_Admin_Tools::import_blocks();
}
?>
<div class="wrap">

  <h1><?php esc_html_e('QNRWP Tools', 'qnrwp'); ?></h1>
  <?php
  
    do_action('admin_notices'); 
  
    $active_tab = isset($_GET['tool']) ? $_GET['tool'] : '';
    
  ?>
  <h2 class="nav-tab-wrapper">
    <a href="?page=qnrwp_theme_menu" class="nav-tab<?php 
                echo $active_tab == '' ? ' nav-tab-active' : ''; ?>"><?php 
                esc_html_e('Setup', 'qnrwp'); ?></a>
    <a href="?page=qnrwp_theme_menu&tool=reusable_blocks" class="nav-tab<?php 
                echo $active_tab == 'reusable_blocks' ? ' nav-tab-active' : ''; ?>"><?php 
                esc_html_e('Reusable blocks', 'qnrwp'); ?></a>
  </h2>
  
  <?php if ($active_tab == ''): ?>
  
  <form method="post" action="" name="setup"> 
    <input type="hidden" name="setup-tag" value="1">
    
    <p><?php esc_html_e('Save time by automatically creating the usual pages. Existing pages with the same names will not be overwritten. You may also set up front and posts pages, and set all relevant settings to required values.', 'qnrwp'); ?></p>
    
    <table class="form-table">
      <!-- Checkboxes: Pages -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Create pages:', 'qnrwp'); ?></th>
        <td>
          <fieldset>
            <p><label><input type="checkbox" name="qnrwp_tools_create[homepage]" id="qnrwp_tools_create_homepage" 
                                value="<?php esc_html_e('Home', 'qnrwp'); ?>" checked="checked">
                                <?php esc_html_e('Home', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[newspage]" id="qnrwp_tools_create_newspage" 
                                value="<?php esc_html_e('News', 'qnrwp'); ?>" checked="checked">
                                <?php esc_html_e('News', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[aboutpage]" id="qnrwp_tools_create_aboutpage" 
                                value="<?php esc_html_e('About', 'qnrwp'); ?>" checked="checked">
                                <?php esc_html_e('About', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[teampage]" id="qnrwp_tools_create_teampage" 
                                value="<?php esc_html_e('Team', 'qnrwp'); ?>">
                                <?php esc_html_e('Team', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[contactpage]" id="qnrwp_tools_create_contactpage" 
                                value="<?php esc_html_e('Contact', 'qnrwp'); ?>">
                                <?php esc_html_e('Contact', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[servicespage]" id="qnrwp_tools_create_servicespage" 
                                value="<?php esc_html_e('Services', 'qnrwp'); ?>">
                                <?php esc_html_e('Services', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[productspage]" id="qnrwp_tools_create_productspage" 
                                value="<?php esc_html_e('Products', 'qnrwp'); ?>">
                                <?php esc_html_e('Products', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[shoppage]" id="qnrwp_tools_create_shoppage" 
                                value="<?php esc_html_e('Shop', 'qnrwp'); ?>">
                                <?php esc_html_e('Shop', 'qnrwp'); ?></label></p>
            <p><label><input type="checkbox" name="qnrwp_tools_create[portfolio]" id="qnrwp_tools_create_portfolio" 
                                value="<?php esc_html_e('Portfolio', 'qnrwp'); ?>">
                                <?php esc_html_e('Portfolio', 'qnrwp'); ?></label></p>
          </fieldset>
        </td>
      </tr>
    
      <!-- Checkbox: Setup Home / News -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Set up Home / News:', 'qnrwp'); ?></th>
        <td>
          <fieldset>
            <p><label><input type="checkbox" name="qnrwp_tools_setuppages" id="qnrwp_tools_setuppages" 
                                value="1" checked="checked">
                                <?php esc_html_e('Set up Home page as front page, News as posts page', 'qnrwp'); ?></label></p>
          </fieldset>
        </td>
      </tr>
    
      <!-- Checkbox: Setup preferences -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('All settings:', 'qnrwp'); ?></th>
        <td>
          <fieldset>
            <p><label><input type="checkbox" name="qnrwp_tools_settings" id="qnrwp_tools_settings" 
                                value="1" checked="checked">
                                <?php esc_html_e('Set all relevant settings (except Permalinks) to values specified in Documentation', 'qnrwp'); ?></label></p>
          </fieldset>
        </td>
      </tr>
      
    </table>
    
    <?php submit_button(esc_html__('Submit', 'qnrwp')); ?>
  </form>
  <?php elseif ($active_tab == 'reusable_blocks'): ?>
  
  <form method="post" action="" name="blocks"> 
    <input type="hidden" name="blocks-tag" value="1">
    
    <p><?php esc_html_e('Import reusable blocks offered by this theme. Existing blocks with the same name will not be overwritten.', 'qnrwp'); ?></p>
    
    <table class="form-table">
    
      <!-- Checkboxes: Blocks -->
      <tr valign="top">
        <th scope="row"><?php esc_html_e('Import blocks:', 'qnrwp'); ?></th>
        <td>
          <fieldset>
          <?php
          $fpL = qnrwp_get_directory_tree_filepaths_array(get_template_directory() . '/blocks');
          if ($fpL) {
            sort($fpL);
            $postsData = array();
            $checkCount = 0;
            foreach ($fpL as $fp) {
              $js = file_get_contents($fp);
              $jsL = json_decode($js, true);
              $checkCount += 1;
              ?>
              <p><label><input type="checkbox" name="qnrwp_tools_blocks[<?php echo $checkCount; ?>]" id="qnrwp_tools_blocks_<?php echo $checkCount; ?>" 
                                  value="<?php echo bin2hex($fp); ?>" checked="checked">
                                  <?php echo esc_html($jsL['title']); ?></label></p>
              <?php
            }
          }
          ?>
          </fieldset>
        </td>
      </tr>
      
    </table>
    
    <?php submit_button(esc_html__('Submit', 'qnrwp')); ?>
  </form>
  <?php endif; // Create pages tool end ?>
</div><!-- WP wrap end -->
