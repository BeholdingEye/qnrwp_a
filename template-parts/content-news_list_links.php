<?php
/**
 * content-news_list_links.php
 */

// Previous and Next posts pages links
$nextPostsLink = get_next_posts_link('&laquo;&nbsp;'.esc_html__('Older', 'qnrwp'));
$prevPostsLink = get_previous_posts_link(esc_html__('Newer', 'qnrwp').'&nbsp;&raquo;');
if ($prevPostsLink || $nextPostsLink) { // Could be only one page of posts ?>
  <div class="qnrwp-excerpts-pages-links">
    <span class="qnrwp-excerpts-older"><?php echo $nextPostsLink; ?></span><span class="qnrwp-excerpts-newer"><?php echo $prevPostsLink; ?></span><!-- No whitespace -->
  </div>
<?php 
}
