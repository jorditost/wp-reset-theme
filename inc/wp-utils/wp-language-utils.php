<?php 

/**
 * qTranslate Functions Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    1.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */

/**
 * @package Hack qTranslate Menu
 * @version 1.0
 */
/*
Plugin Name: Hack qTranslate Menu
Plugin URI: http://www.medita.com
Description: Hack for Menu creation feature with qTranslate active
Author: Michele Menciassi
Version: 1.0
Author URI: http://www.medita.com/
*/


function qtmh_setup_nav_menu_item( $menu_item ) {
	if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')){
		$menu_item->title = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage( $menu_item->title );
		$menu_item->title = qtrans_useTermLib( $menu_item->title );
		return $menu_item;
	}
}
add_filter('wp_setup_nav_menu_item', 'qtmh_setup_nav_menu_item', 0);


function qtmh_update_nav_menu_item ($menu_id, $menu_item_db_id, $args ){
	if ($args['menu-item-type'] == 'post_type'){		 
		$id = $args['menu-item-object-id'];
		$miopost = get_post($id); 
		$title = $miopost->post_title;
		$my_post = array();
		$my_post['ID'] = $menu_item_db_id;
  		$my_post['post_title'] = $title;
  		// Update the menu item with original title post
  		wp_update_post( $my_post );
	}
}
add_action('wp_update_nav_menu_item', 'qtmh_update_nav_menu_item', 10, 3);

// Translate Custom Taxonomy
// "customtag" is the name declared in register_taxonomy();
// add_action('customtag_add_form', 'qtrans_modifyTermFormFor');
// add_action('customtag_edit_form', 'qtrans_modifyTermFormFor');

/*add_action('thinkmoto_casescategory_add_form', 'qtrans_modifyTermFormFor');
add_action('thinkmoto_casescategory_edit_form', 'qtrans_modifyTermFormFor');

// Translate term name in wp_title(), in title tag inside head
add_filter( 'single_term_title', 'qtrans_useTermLib' );*/

?>