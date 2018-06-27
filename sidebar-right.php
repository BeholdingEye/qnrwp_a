<?php
/*
 * Right sidebar
 */

echo '<!-- Right Sidebar -->'.PHP_EOL;
echo '<div id="sidebar-right" class="sidebar sidebar-right widget-area">'.PHP_EOL;
dynamic_sidebar('qnrwp-sidebar-right'); // Usually WP blogging widgets
echo '</div><!-- End of Right Sidebar -->'.PHP_EOL;