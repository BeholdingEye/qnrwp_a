<?php
/*
 * sidebar-left.php
 */

defined( 'ABSPATH' ) || exit;

if (($GLOBALS['QNRWP_GLOBALS']['layout'] == 'three-cols' || $GLOBALS['QNRWP_GLOBALS']['layout'] == 'left-sidebar')): ?>
  <!-- Left Sidebar -->
  <aside id="sidebar-left" class="sidebar sidebar-left widget-area">
    <?php dynamic_sidebar('qnrwp-sidebar-left'); ?>
  </aside><!-- End of Left Sidebar -->
<?php endif;