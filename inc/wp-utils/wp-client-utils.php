<?php 

/**
 * Wordpress Client Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    1.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */


// ======================= 
// ! DASHBOARD Functions   
// ======================= 

add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

// Remove all unnecessary widgets

add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');
function wpc_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['normal']['core']['dashboard_recent_comments']['dashboard_plugins']['dashboard_quick_press']['dashboard_primary']['dashboard_secondary']);
}
?>