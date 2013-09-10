<?php 

/**
 * Language Functions Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */

// Just aplicable if qTranslate exists
if (function_exists('qtrans_getLanguage')) {

	// Hack for Menu creation feature with qTranslate active
	// Functions based on the 'Hack qTranslate Menu' plugin by Michele Menciassi
	// Author URI: http://www.medita.com/

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

	// Translate term name in wp_title(), in title tag inside head
	//add_filter( 'single_term_title', 'qtrans_useTermLib' );
}
?>