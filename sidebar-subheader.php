<?php
/*
 * sidebar-subheader.php
 */
 
if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-subrow-header')): ?>
  <!-- Sub Header Row -->
  <aside id="sub-header-row" class="sub-header-row widget-area">
    <?php dynamic_sidebar('qnrwp-subrow-header'); ?>
  </aside><!-- End of Sub Header Row -->
<?php endif;