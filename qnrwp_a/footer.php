<?php
/*
 * footer.php
 */

defined( 'ABSPATH' ) || exit;

?>

</div><!-- End of Content Row -->

<!-- Page Footer Row -->
<footer id="footer-row" class="footer-row">
<?php 
$footerUpperActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-upper');
$footerMiddleActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-middle');
$footerLowerActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-lower');
?>
<?php if ($footerUpperActive || $footerMiddleActive || $footerLowerActive): ?>
  <?php if ($footerUpperActive): ?>
  <div id="footer-row-upper" class="footer-row-upper widget-area">
    <?php dynamic_sidebar('qnrwp-row-footer-upper'); ?>
  </div>
  <?php endif; ?>
  <?php if ($footerMiddleActive): ?>
  <div id="footer-row-middle" class="footer-row-middle widget-area">
    <?php dynamic_sidebar('qnrwp-row-footer-middle'); ?>
  </div>
  <?php endif; ?>
  <?php if ($footerLowerActive): ?>
  <div id="footer-row-lower" class="footer-row-lower widget-area">
    <?php dynamic_sidebar('qnrwp-row-footer-lower'); ?>
  </div>
  <?php endif; ?>
<?php endif; ?>
<?php
if (QNRWP::get_setting('cookie-notice-placement') == 1) {
  QNRWP_UI_Parts::render_cookie_notice();
}
?>
</footer><!-- End of page Footer -->
<?php 

print_late_styles();
wp_footer();

?>
</body>
</html>
