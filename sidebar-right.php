<?php
/*
 * sidebar-right.php
 */

if (($GLOBALS['QNRWP_GLOBALS']['layout'] == 'three-cols' || $GLOBALS['QNRWP_GLOBALS']['layout'] == 'right-sidebar')): ?>
  <!-- Right Sidebar -->
  <aside id="sidebar-right" class="sidebar sidebar-right widget-area">
    <?php dynamic_sidebar('qnrwp-sidebar-right'); ?>
  </aside><!-- End of Right Sidebar -->
<?php endif;
