<?php 
/**
 * Custom Post Types Functions File
 *
 * @copyright  Copyright © 2013 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 * Read: http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-the-basics/
 * 		 http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/
 *		 http://wp.tutsplus.com/tutorials/theme-development/innovative-uses-of-wordpress-post-types-and-taxonomies/
 *
 * NOTE: This template script have some examples of how to register custom post types, taxonomies or custom fields.
 *		 Our example Custom Post Type is "team", and our Custom Taxonomy "team-category".
 *	     They are commented so as to use them as sample code. Please use them and delete what you don't need :-)
 *		 To do: Build a function that does this work
 */

////////////////////////////////
// Register Custom Post Types
////////////////////////////////

/*function custom_types_register() {

	////////////
	// TEAM
	////////////

	$post_name = 'team';
	$taxonomy  = 'team-category'; 
		
	// CUSTOM TAXONOMY
	
	$labels = array(
		'name' 							=> _x( 'Categories', 'taxonomy general name' ),
		'singular_name' 				=> _x( 'Category', 'taxonomy singular name' ),
		'search_items' 					=> __( 'Search Categories' ),
		'popular_items'              	=> __( 'Popular Categories' ),
		'all_items' 					=> __( 'All Categories' ),
		'parent_item'                	=> __( 'Parent Category' ),
		'edit_item' 					=> __( 'Edit Category' ),
		'update_item' 					=> __( 'Update Category' ),
		'add_new_item' 					=> __( 'Add Category' ),
		'new_item_name' 				=> __( 'New Category' ),
		'separate_items_with_commas'	=> __( 'Separate Categories with commas' ),
		'add_or_remove_items'			=> __( 'Add or remove Categories' ),
		'choose_from_most_used' 		=> __( 'Choose from the most used Categories' )
	    );

	$args = array(
	    'label'                         => 'Categories',
	    'labels'                        => $labels,
	    'public'                        => true,
	    'hierarchical'                  => true,
	    'show_ui'                       => true,
	    'show_in_nav_menus'             => true,
	    'args'                          => array( 'orderby' => 'term_order' ),
	    'rewrite'                       => array( 'slug' => 'about/team' ) // array( 'slug' => 'about/team', 'with_front' => false )
	    //'query_var'                     => true
	);

	register_taxonomy( $taxonomy, $post_name, $args );
	
	// Adding qTranslate to taxonomy creator/editor
	if (function_exists('qtrans_getLanguage')) {
		add_action($taxonomy.'_add_form', 'qtrans_modifyTermFormFor');
		add_action($taxonomy.'_edit_form', 'qtrans_modifyTermFormFor');
	}
	
	// CUSTOM POST TYPE
	$cpt_args = array(
		'labels' 				=> get_custom_post_type_labels($post_name, 'Entry', 'Team'),
		'description'       	=> '',
		'public'                => true,
		//'exclude_from_search' => true,
		//'publicly_queryable'	=> false,
		//'show_in_nav_menus'	=> false,
	    'show_ui'               => true,
	    'show_in_menu'          => true,
	    'capability_type' 		=> 'page',
	    'hierarchical'			=> false,
	    'rewrite'               => array( 'slug' => 'about/team' ), // array( 'slug' => 'about/team', 'with_front' => false )
	    'query_var' 			=> true,
	    'has_archive'           => false,
	    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' )	// 'title','editor','thumbnail','excerpt','custom-fields','page-attributes'
	);

	register_post_type( $post_name, $cpt_args );
}
add_action( 'init', 'custom_types_register' );*/


/////////////////
// Flush Rules
/////////////////

function custom_plugin_activation() {
	// Register types to register the rewrite rules
	custom_types_register();

	// Then flush them
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'custom_plugin_activation');


function custom_plugin_deactivation() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'custom_plugin_deactivation');


//////////////////////
// Connection Types
//////////////////////

// Posts 2 Posts plugin: 
// https://github.com/scribu/wp-posts-to-posts/
// https://github.com/scribu/wp-posts-to-posts/wiki

/*if (function_exists('p2p_register_connection_type')) {
	
	function my_connection_types() {
		p2p_register_connection_type( array(
			'name' => 'posts_to_pages',
			'from' => 'post',
			'to' => 'page'
		) );
	}
	add_action( 'p2p_init', 'my_connection_types' );
}*/


///////////////////
// Rewrite Rules
///////////////////

// Post Type Archives
// http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-the-basics/
// http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/

// Add custom post type yearly/monthly/daily archives
/*function add_custom_post_type_archives() {

	$post_name = 'custom_post_name';

	// Add day archive (and pagination)
	add_rewrite_rule($post_name . '/([0-9]{4})/([0-9]{2})/([0-9]{2})/page/?([0-9]{1,})/?','index.php?post_type=' . $post_name . '&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]','top');
	add_rewrite_rule($post_name . '/([0-9]{4})/([0-9]{2})/([0-9]{2})/?','index.php?post_type=' . $post_name . '&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]','top');

	// Add month archive (and pagination)
	add_rewrite_rule($post_name . '/([0-9]{4})/([0-9]{2})/page/?([0-9]{1,})/?','index.php?post_type=' . $post_name . '&year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]','top');
	add_rewrite_rule($post_name . '/([0-9]{4})/([0-9]{2})/?','index.php?post_type=' . $post_name . '&year=$matches[1]&monthnum=$matches[2]','top');

	// Add year archive (and pagination)
	add_rewrite_rule($post_name . '/([0-9]{4})/page/?([0-9]{1,})/?','index.php?post_type=' . $post_name . '&year=$matches[1]&paged=$matches[2]','top');
	add_rewrite_rule($post_name . '/([0-9]{4})/?','index.php?post_type=' . $post_name . '&year=$matches[1]','top');

}
add_action('init','add_custom_post_type_archives');*/

// Add custom taxonomy terms to post type permalinks
/*function add_custom_taxonomy_to_permalink() {

	$post_name    = 'team';
	$taxonomy     = 'team-category';
	$rewrite_slug = 'about/team'; // The rewrite slug set in 'register_post_type'

	add_rewrite_rule('^' . $rewrite_slug . '/([^/]+)/([^/]+)/?','index.php?post_type=' . $post_name . '&' . $taxonomy . '=$matches[1]&' . $post_name . '=$matches[2]','top');
}
add_action('init','add_custom_taxonomy_to_permalink');

function custom_post_type_link( $post_link, $id = 0 ) {

	$post_name    = 'team';
	$taxonomy     = 'team-category';
	$rewrite_slug = 'about/team'; // The rewrite slug set in 'register_post_type'

	$post = get_post($id);
	if (is_wp_error($post) || $post_name != $post->post_type || empty($post->post_name))
		return $post_link;
	
	// Get the current taxonomy term
	$terms = get_the_terms($post->ID, $taxonomy);
	if ( is_wp_error($terms) || !$terms ) {
		$taxonomy_term = 'uncategorised';
	} else {
		$taxonomy_term_obj = array_pop($terms);
		$taxonomy_term = $taxonomy_term_obj->slug;
	}

	return home_url(user_trailingslashit( $rewrite_slug . '/$taxonomy_term/$post->post_name' ));
}
add_filter( 'post_type_link', 'custom_post_type_link' , 10, 2 );*/


/////////////////////
// Admin Functions
/////////////////////

// Function that changes menu labels
/*function change_post_menu_label() {
    global $menu;
    global $submenu;
	
	// Change Custom Post Types menu items
    //$menu[5][0] = 'Produkte';
    //$submenu['edit.php'][5][0] = 'All Products';
    //$submenu['edit.php'][15][0] = 'Collections'; 	// Change name for categories
    //$submenu['edit.php'][16][0] = 'Labels'; 		// Change name for tags
    echo '';
}
add_action( 'admin_menu', 'change_post_menu_label' );*/

// Add custon taxonomy column on posts list
/*function team_change_columns($defaults) {
    $defaults['team-category'] = 'Team Category';
    return $defaults;
}

function team_custom_column($column_name, $post_id) {
    $taxonomy = $column_name;
    
    if ($taxonomy != 'team-category')
    	return;

    $post_type = get_post_type($post_id);
    $terms = get_the_terms($post_id, $taxonomy);
 
    if ( !empty($terms) ) {
        foreach ( $terms as $term )
            $post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->term_id}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
        echo join( ', ', $post_terms );
    }
    else echo '<i>No terms.</i>';
}
add_filter('manage_team_posts_columns', 'team_change_columns' );
add_action('manage_team_posts_custom_column', 'team_custom_column', 10, 2);*/
	

// Filter the request to just give posts for the given taxonomy, if applicable.
function taxonomy_filter_restrict_manage_posts() {
    global $typenow;

    // If you only want this to work for your specific post type,
    // check for that $type here and then return.
    // This function, if unmodified, will add the dropdown for each
    // post type / taxonomy combination.

    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
    	$filters = get_object_taxonomies( $typenow );

        foreach ( $filters as $tax_slug ) {
            $tax_obj = get_taxonomy( $tax_slug );
            wp_dropdown_categories( array(
                'show_option_all' => __('Show All '.$tax_obj->label ),
                'taxonomy' 	  => $tax_slug,
                'name' 		  => $tax_obj->name,
                'orderby' 	  => 'name',
                'selected' 	  => $_GET[$tax_slug],
                'hierarchical' 	  => $tax_obj->hierarchical,
                'show_count' 	  => false,
                'hide_empty' 	  => true
            ) );
        }
    }
}	
add_action( 'restrict_manage_posts', 'taxonomy_filter_restrict_manage_posts' );


function taxonomy_filter_post_type_request( $query ) {
	global $pagenow, $typenow;
	
	if ( 'edit.php' == $pagenow ) {
		$filters = get_object_taxonomies( $typenow );
		foreach ( $filters as $tax_slug ) {
			$var = &$query->query_vars[$tax_slug];
			if ( isset( $var ) ) {
				$term = get_term_by( 'id', $var, $tax_slug );
				$var = $term->slug;
			}
		}
	}
}
add_filter( 'parse_query', 'taxonomy_filter_post_type_request' );


////////////////////////////
// CUSTOM FIELD Functions   
////////////////////////////

add_action('admin_init', 'add_custom_boxes');
//add_action('save_post', 'save_custom_postdata');

function add_custom_boxes() {
	
	$page_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];

	// Meta box for a given post type
	// add_meta_box('custom_meta_box_id', __('Title'), 'custom_meta_box_function', 'post_type', 'advanced', 'default');

	// Target subpage
	// if ( is_subpage($page_id) ) {
	// 	add_meta_box('custom_meta_box_id', __("Title"), 'custom_meta_box_function', 'page', 'advanced', 'default');
	// }
	
	// remove custom fields meta box
	remove_meta_box( 'postcustom', 'post', 'advanced' );
	remove_meta_box( 'postcustom', 'page', 'advanced' );
}

function save_custom_postdata() {  
	// verify if this is an auto save routine. 
  	// If it is our form has not been submitted, so we dont want to do anything
  	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  	
	global $post;

	// Get current page
	$page_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	$page_slug = get_page_slug_by_ID($page_id);
	
	// Target post type
	// if ($post->post_type == 'post_type') {
	// 	update_post_meta($post->ID, 'custom_field_id', $_POST['custom_field_id']);
	// }

  	// Target subpage
	// if ( is_subpage($page_id) ) {
	// //if (is_subpage_of($page_id, 'parent_page_slug')) {
	// 	add_meta_box('custom_meta_box_id', __("Title"), 'custom_meta_box_function', 'page', 'advanced', 'default');
	// }
}

// Custom meta box
/*function custom_meta_box_function() {

    global $post;
    $custom = get_post_custom($post->ID);  
	?>
		<p>
			<label for="custom_field_id"><? _e("Description of this input field"); ?>:</label><br />
			<input name="custom_field_id" value="<?php echo $custom["custom_field_id"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label for="custom_field_id"><? _e("Description of this textarea field"); ?>:</label><br />
			<textarea name="custom_field_id" rows="4" style="width:98%;"><?php echo $custom["custom_field_id"][0]; ?></textarea>
		</p>
		<p>
			<input id="custom_field_id" name="custom_field_id" type="checkbox"' . <?php echo ($custom["custom_field_id"][0]) ? ' checked="checked"' : '' ?> . ' style="margin-right:10px;" />
			<label for="custom_field_id"><? _e("Description of this checkbox field"); ?></label>
		</p>
	<?php
}*/


////////////////////
// Register Utils
////////////////////

function get_custom_post_type_labels($post_name, $singular_name, $plural_name) {

	return array(
				'name' 					=> __( $plural_name ),
				'singular_name'			=> __( $singular_name ),
				'add_new'				=> _x('Add New', $post_name),
				'add_new_item' 			=> __('Add New '.$singular_name),
				'edit_item' 			=> __('Edit '.$singular_name),
				'new_item' 				=> __('New '.$singular_name),
				'view_item' 			=> __('View '.$singular_name),
				'search_items' 			=> __('Search '.$singular_name),
				'not_found' 			=> __('No '.strtolower($singular_name).' found'),
				'not_found_in_trash' 	=> __('No '.strtolower($singular_name).' found in Trash')
			);
}
?>