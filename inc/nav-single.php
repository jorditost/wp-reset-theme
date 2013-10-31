<?php
/*
 * This file outputs navigation between pages (not posts) in a single page.
 * Requires 'Next Page, Not Next Post' plugin
 */
?>

<?php if (function_exists('next_page_not_post')) : ?>

<?php 
	// Get post type
	$post_type = get_query_var('post_type');
?>
	
<div class="navigation">
	<div class="next-posts"><?php echo next_page_not_post('Next', NULL, 'post_type=' . $post_type . '&sort_column=menu_order&sort_order=asc&parent=0&hierarchical=0'); ?></div>
	<div class="prev-posts"><?php echo previous_page_not_post('Previous', NULL, 'post_type=' . $post_type . '&sort_column=menu_order&sort_order=asc&parent=0&hierarchical=0'); ?></div>
</div>

<?php else: ?>

<div class="navigation">
	<div class="next-posts"><?php previous_post_link('%link', 'Prev Post'); ?></div>
	<div class="prev-posts"><?php next_post_link('%link', 'Next Post'); ?></div>
</div>

<?php endif; ?>