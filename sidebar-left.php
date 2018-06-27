<?php
/*
 * Left sidebar
 */

echo '<!-- Left Sidebar -->'.PHP_EOL;
echo '<div id="sidebar-left" class="sidebar sidebar-left widget-area">' .PHP_EOL;
dynamic_sidebar('qnrwp-sidebar-left'); // Usually WP blogging widgets
echo '</div><!-- End of Left Sidebar -->'.PHP_EOL;