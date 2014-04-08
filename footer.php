		</div><!-- #main -->
	</div><!-- #wrap -->

	<div id="footer">
		<div class="inside">
			<div id="footer-nav" class="nav-menu group"><?php wp_nav_menu( array('theme_location' => 'footer-menu')); ?></div>
			<div class="copyright">&copy; <?php echo date("Y");?></div>
		</div>
	</div><!-- #footer -->

	<?php wp_footer(); ?>

<!-- Asynchronous google analytics; this is the official snippet.
	 Replace UA-XXXXXX-XX with your site's ID and uncomment to enable.
	 
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXX-XX']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->
	
</body>

</html>
