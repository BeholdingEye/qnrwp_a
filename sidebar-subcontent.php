<?php
/*
 * sidebar-subcontent.php
 */

if (QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-subrow-content')): ?>
  <!-- Sub Content Row -->
  <aside id="sub-content-row" class="sub-content-row widget-area">
    <?php dynamic_sidebar('qnrwp-subrow-content'); ?>
  </aside><!-- End of Sub Content Row -->
<?php endif;
