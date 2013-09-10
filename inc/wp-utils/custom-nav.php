<?php

	// Get the nav menu based on $menu_name (same as 'theme_location' or 'menu' arg to wp_nav_menu)
    // This code based on wp_nav_menu's code to get Menu ID from menu slug

    $menu_name = 'main-menu';
	
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
    	
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
			
		$menu_items = wp_get_nav_menu_items($menu->term_id);
	
		$menu_list = '<ul id="menu-' . $menu_name . '">';
		
		foreach ( (array) $menu_items as $key => $menu_item ) {
		
			//wp_debug($menu_item);
			
		    $title = $menu_item->title;
		    $url = $menu_item->url;
		    $page_slug = $menu_item->post_name;
		    
		    $class = (
		    			is_page($menu_item->object_id) ||
		    			($page_slug == 'portfolio' && 
		    				(	get_post_type() == 'thinkmoto_case' 	||
		    					is_post_type_archive('thinkmoto_case')  ||
		    					is_tax('thinkmoto_casescategory')
		    				) 
		    			)
		    		) ? ' class="active"' : '';
		    
		    
		    
		    $menu_list .= '<li id="menu-item-' . $page_slug . '"'. $class .'><a href="' . $url . '">' . $title . '</a></li>';
		}
		$menu_list .= '</ul>';
		
		echo $menu_list;
	}
    
    // $menu_list now ready to output
?>