<?php
/*
 * Footer
 */

echo '<!-- Footer Row -->'.PHP_EOL;
echo '<div id="footer-row" class="footer-row">'.PHP_EOL;

$footerUpperActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-upper');
$footerMiddleActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-middle');
$footerLowerActive = QNRWP_Widgets::is_active_sidebar_widgets('qnrwp-row-footer-lower');
if ($footerUpperActive || $footerMiddleActive || $footerLowerActive) {
  if ($footerUpperActive) {
    echo '<div id="footer-row-upper" class="footer-row-upper widget-area">'.PHP_EOL;
    dynamic_sidebar('qnrwp-row-footer-upper');
    echo '</div>'.PHP_EOL;
  }
  if ($footerMiddleActive) {
    echo '<div id="footer-row-middle" class="footer-row-middle widget-area">'.PHP_EOL;
    dynamic_sidebar('qnrwp-row-footer-middle');
    echo '</div>'.PHP_EOL;
  }
  if ($footerLowerActive) {
    echo '<div id="footer-row-lower" class="footer-row-lower widget-area">'.PHP_EOL;
    dynamic_sidebar('qnrwp-row-footer-lower');
    echo '</div>'.PHP_EOL;
  }
}

echo '</div><!-- End of Footer -->'.PHP_EOL;

print_late_styles();
wp_footer();
?>
</body>
</html>
