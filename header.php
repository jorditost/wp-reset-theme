<!DOCTYPE html>

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js<?php mobile_class(); ?>" <?php language_attributes(); ?>><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. -->

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	
	<?php if (is_search()) echo '<meta name="robots" content="noindex, nofollow" />'; ?>

	<title><?php
		   	  if (is_home() || is_front_page()) {
		         bloginfo('name'); echo ' | '; bloginfo('description'); }
		      else {
		          bloginfo('name'); echo ' | '; }
		          
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot;'; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive'; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot;'; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); }
		      elseif (is_404()) {
		         echo 'Not Found'; }
		      
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?></title>
	
	<meta name="title" content="<?php
		   	  if (is_home() || is_front_page()) {
		         bloginfo('name'); echo ' | '; bloginfo('description'); }
		      else {
		          bloginfo('name'); echo ' | '; }
		          
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot;'; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive'; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot;'; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); }
		      elseif (is_404()) {
		         echo 'Not Found'; }
		      
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>">

	<meta name="description" content="<?php bloginfo('description'); ?>" />
	
	<meta name="author" content="Your Name here">
	<meta name="Copyright" content="Copyright Your Name Here <?php echo date("Y"); ?>. All Rights Reserved.">
	
	<meta name="google-site-verification" content="">
	<!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->

	<!--  Mobile Viewport meta tag
	j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width -->
	<!-- Uncomment to use; use thoughtfully!
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	-->
	
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico">
	<!-- This is the traditional favicon.
		 - size: 16x16 or 32x32
		 - transparency is OK
		 - see wikipedia for info on browser support: http://mky.be/favicon/ -->
		 
	<!--<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/images/apple-touch-icon.png">-->
	<!-- The is the icon for iOS's Web Clip.
		 - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
		 - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
		 - Transparency is not recommended (iOS will put a black BG behind the icon) -->
	
	<!-- CSS: screen, mobile & print are all in the same file -->
	<?php echo is_production(); ?>
	<?php if (!is_production()): ?>
		<link rel="stylesheet/less" type="text/css" href="<?php bloginfo('template_directory'); ?>/less/style.less">
		<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.7.0/less.min.js"></script>
	<?php else: ?>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<?php endif; ?>
	
	<!-- all our JS is at the bottom of the page, except for Modernizr. -->
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<?php if (!is_production()): ?>
		<script src="http://modernizr.com/downloads/modernizr-latest.js"></script>
	<?php else: ?>
		<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-min.js"></script>
	<?php endif; ?>
	
	<!-- Application-specific meta tags -->
	<!-- Windows 8 -->
	<meta name="application-name" content="" /> 
	<meta name="msapplication-TileColor" content="" /> 
	<meta name="msapplication-TileImage" content="" />
	<!-- Twitter -->
	<meta name="twitter:card" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:url" content="">
	<meta name="twitter:image" content="">
	<!-- Facebook -->
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php //if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	
	<div id="wrap">

		<div id="header">
			<div class="inside">
				<a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a>
				<div id="nav" class="nav-menu group" role="navigation">
					<?php wp_nav_menu( array('menu' => 'main-menu') ); ?>
				</div>
			</div>
		</div><!-- #header -->

		<div id="main" class="inside group">

