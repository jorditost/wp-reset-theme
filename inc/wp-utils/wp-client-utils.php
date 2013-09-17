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


//////////////////////////
// UPDATE Notifications
//////////////////////////

// Remove WP update notifications
function remove_wp_update_notifications() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}

add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );


/////////////////////////
// DASHBOARD Functions   
/////////////////////////

// Remove all unnecessary widgets
function remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press',    'dashboard', 'side' );    // Quick Press widget
	remove_meta_box( 'dashboard_recent_drafts',  'dashboard', 'side' );    // Recent Drafts
	remove_meta_box( 'dashboard_primary',        'dashboard', 'side' );    // WordPress.com Blog
	remove_meta_box( 'dashboard_secondary',      'dashboard', 'side' );    // Other WordPress News
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );  // Incoming Links
	remove_meta_box( 'dashboard_plugins',        'dashboard', 'normal' );  // Plugins
	remove_meta_box( 'dashboard_recent_comments','dashboard', 'normal' );  // Recent Comments
	//remove_meta_box( 'dashboard_right_now',      'dashboard', 'normal' );  // Right now
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
?>