<?php get_header(); ?>

	<div id="content">
		<h2><?php _e('Error 404 - Page Not Found','html5reset'); ?></h2>
		<?php 
			global $test;
			if ($test) {
				global $wp_query;
				wp_debug($wp_query);
			}
		?>
	</div>
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>