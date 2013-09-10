<?php
/*
 * This file outputs navigation between pages (not posts) in a single page.
 * Requires 'Next Page, Not Next Post' plugin
 */
?>

<?php if (function_exists('next_page_not_post')) : ?>
	
<div class="navigation">
	<div class="next-posts"><?php echo next_page_not_post('Next Page', 'true', 'sort_column=menu_order&sort_order=asc&parent=0&hierarchical=0'); ?></div>
	<div class="prev-posts"><?php echo previous_page_not_post('Previous Page', 'true', 'sort_column=menu_order&sort_order=asc&parent=0&hierarchical=0'); ?></div>
</div>

<?php endif; ?>