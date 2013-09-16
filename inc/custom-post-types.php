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
 * Read: // http://wp.tutsplus.com/tutorials/creative-coding/the-rewrite-api-post-types-taxonomies/
 *
 * NOTE: This template script have some examples of how to register custom post types, taxonomies or custom fields.
 *	     They are commented so as to use them as sample code. Please use them and delete what you don't need :-)
 *		 To do: Build a function that does this work
 */

////////////////////////////////
// Register Custom Post Types
////////////////////////////////

function custom_types_register() {
	
	/*
	////////////
	// TEAM
	////////////

	$post_name = 'team';
	$taxonomy  = 'team-category'; 
		
	// CUSTOM TAXONOMY: Places Child Type
	
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
	
	// Adding qTranslate to taxonomy editor
	if (function_exists('qtrans_getLanguage')) {
		add_action($taxonomy.'_add_form', 'qtrans_modifyTermFormFor');
		add_action($taxonomy.'_edit_form', 'qtrans_modifyTermFormFor');
	}
	
	// CUSTOM POST TYPE
	$cpt_args = array(
		'labels' 				=> get_custom_post_type_labels($post_name, 'Entry', 'Team'),
		'description'       	=> '',
		'public'                => true,
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


	//////////////////
	// CASE STUDIES
	//////////////////
	
	$post_name = 'case';	

	$cpt_args = array(
		'labels' 				=> get_custom_post_type_labels($post_name, 'Case', 'Cases'),
		'description'       	=> '',
		'public'                => true,
	    'show_ui'               => true,
	    'show_in_menu'          => true,
	    'capability_type' 		=> 'page',
	    'hierarchical'			=> false,
	    'rewrite'               => array( 'slug' => 'clients/case-studies', 'with_front' => false ),
	    'query_var' 			=> true,
	    'has_archive'           => false,
	    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' )	// 'title','editor','thumbnail','excerpt','custom-fields','page-attributes'
	);

	register_post_type( $post_name, $cpt_args );


	//////////////////
	// TESTIMONIALS
	//////////////////
	
	$post_name = 'testimonial';

	$cpt_args = array(
		'labels' 				=> get_custom_post_type_labels($post_name, 'Testimonial', 'Testimonials'),
		'description'       	=> '',
		'public'                => true,
	    'show_ui'               => true,
	    'show_in_menu'          => true,
	    'capability_type' 		=> 'page',
	    'hierarchical'			=> false,
	    'rewrite'               => array( 'slug' => 'clients/testimonials', 'with_front' => false ),
	    'query_var' 			=> true,
	    'has_archive'           => false,
	    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' )	// 'title','editor','thumbnail','excerpt','custom-fields','page-attributes'
	);

	register_post_type( $post_name, $cpt_args );*/
}
add_action( 'init', 'custom_types_register' );


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
			'from' => 'service',
			'to' => 'case'
		) );
	}
	add_action( 'p2p_init', 'my_connection_types' );
}*/


///////////////////
// Rewrite Rules
///////////////////

/*function add_custom_taxonomy_to_permalink() {

	add_rewrite_rule("^about/team/([^/]+)/([^/]+)/?",'index.php?post_type=team&team-category=$matches[1]&team=$matches[2]','top');
}
add_action('init','add_custom_taxonomy_to_permalink');

function custom_team_link( $post_link, $id = 0 ) {
	$post = get_post($id);
	if ( is_wp_error($post) || 'team' != $post->post_type || empty($post->post_name) )
		return $post_link;
	// Get the genre:
	$terms = get_the_terms($post->ID, 'team-category');
	if( is_wp_error($terms) || !$terms ) {
		$team_category = 'uncategorised';
	}
	else {
		$team_category_obj = array_pop($terms);
		$team_category = $team_category_obj->slug;
	}
	return home_url(user_trailingslashit( "about/team/$team_category/$post->post_name" ));
}
add_filter( 'post_type_link', 'custom_team_link' , 10, 2 );*/


/////////////////////
// Admin Functions
/////////////////////

// Function that changes menu labels
/*function change_post_menu_label() {
    global $menu;
    global $submenu;
	
	// Change Custom Post Types menu items
	//$menu[28][0] = 'Case Studies';
    //$menu[30][0] = 'Meet Us';

    $menu[5][0] = 'Produkte';
    $submenu['edit.php'][5][0] = 'Alle Produkte';
    //$submenu['edit.php'][10][0] = 'Add Contacts';
    $submenu['edit.php'][15][0] = 'Kollektionen'; 	// Change name for categories
    //$submenu['edit.php'][16][0] = 'Labels'; 		// Change name for tags
    echo '';
}
add_action( 'admin_menu', 'change_post_menu_label' );*/

// Translate Custom Taxonomy
// Use it after register your taxonomy
/*if (function_exists('qtrans_getLanguage')) {
	// "customtag" is the name declared in register_taxonomy();
	//add_action('customtag_add_form', 'qtrans_modifyTermFormFor');
	//add_action('customtag_edit_form', 'qtrans_modifyTermFormFor');
}*/

// Add custon taxonomy column on posts list
/*function cases_change_columns($defaults) {
    $defaults['thinkmoto_casescategory'] = 'Case Categories';
    return $defaults;
}

function cases_custom_column($column_name, $post_id) {
    $taxonomy = $column_name;
    
    if ($taxonomy != "thinkmoto_casescategory")
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
add_filter('manage_thinkmoto_case_posts_columns', 'cases_change_columns' );
add_action('manage_thinkmoto_case_posts_custom_column', 'cases_custom_column', 10, 2);*/
	

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

/*add_action('admin_init', 'add_custom_boxes');
add_action('save_post', 'save_custom_postdata');


function add_custom_boxes() {
	
	// Affects only cases
	add_meta_box("thinkmoto_case_details", __("Case details"), "thinkmoto_case_details_meta_box", "thinkmoto_case", "advanced", "default");
	
	// Affects only jobs
	add_meta_box("thinkmoto_job_details", __("Job details"), "thinkmoto_job_details_meta_box", "thinkmoto_job", "advanced", "default");
	
	// Über uns
	//if ( is_subpage_of( $_REQUEST['post'], 'ueber-uns' ) ) {
	//	add_meta_box("byrk_about-us", __("Berufstitel"), "byrk_aboutus_meta_options", "page", "side", "default");
	//}
	
	// remove custom fields meta box
	remove_meta_box( 'postcustom', 'thinkmoto_case', 'advanced' );
	remove_meta_box( 'postcustom', 'thinkmoto_job', 'advanced' );
	//remove_meta_box( 'postcustom', 'post', 'advanced' );
	//remove_meta_box( 'postcustom', 'page', 'advanced' );
}

function save_custom_postdata() {  
	// verify if this is an auto save routine. 
  	// If it is our form has not been submitted, so we dont want to do anything
  	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  	
	global $post;

	// Get current page
	$page_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	$page_slug = get_page_slug_by_ID($page_id);
	
	// Cases
	if ($post->post_type == 'thinkmoto_case') {
		
		// Eigenschaften
		update_post_meta($post->ID, 'thinkmoto_case_year', $_POST['thinkmoto_case_year']);
		
		update_post_meta($post->ID, 'thinkmoto_case_keywords', $_POST['thinkmoto_case_keywords']);
		update_post_meta($post->ID, 'thinkmoto_case_keywords_en', $_POST['thinkmoto_case_keywords_en']);
		
		update_post_meta($post->ID, 'thinkmoto_case_linktext', $_POST['thinkmoto_case_linktext']);
		update_post_meta($post->ID, 'thinkmoto_case_linktext_en', $_POST['thinkmoto_case_linktext_en']);
		
		update_post_meta($post->ID, 'thinkmoto_case_link', $_POST['thinkmoto_case_link']);
		update_post_meta($post->ID, 'thinkmoto_case_link_en', $_POST['thinkmoto_case_link_en']);
		
		update_post_meta($post->ID, 'thinkmoto_case_technicalpartner', $_POST['thinkmoto_case_technicalpartner']);
		update_post_meta($post->ID, 'thinkmoto_case_technicalpartner_en', $_POST['thinkmoto_case_technicalpartner_en']);
		
		update_post_meta($post->ID, 'thinkmoto_case_awards', $_POST['thinkmoto_case_awards']);
		update_post_meta($post->ID, 'thinkmoto_case_awards_en', $_POST['thinkmoto_case_awards_en']);
		
		update_post_meta($post->ID, 'thinkmoto_case_videos', $_POST['thinkmoto_case_videos']);
  	}

	// Jobs
	if ($post->post_type == 'thinkmoto_job') {
		
		// Eigenschaften
		update_post_meta($post->ID, 'thinkmoto_job_level', $_POST['thinkmoto_job_level']);
		update_post_meta($post->ID, 'thinkmoto_job_level_en', $_POST['thinkmoto_job_level_en']);
		update_post_meta($post->ID, 'thinkmoto_job_employment', $_POST['thinkmoto_job_employment']);
		update_post_meta($post->ID, 'thinkmoto_job_employment_en', $_POST['thinkmoto_job_employment_en']);
		update_post_meta($post->ID, 'thinkmoto_job_duration', $_POST['thinkmoto_job_duration']);
		update_post_meta($post->ID, 'thinkmoto_job_duration_en', $_POST['thinkmoto_job_duration_en']);
		update_post_meta($post->ID, 'thinkmoto_job_start', $_POST['thinkmoto_job_start']);
		update_post_meta($post->ID, 'thinkmoto_job_start_en', $_POST['thinkmoto_job_start_en']);
  	}

  	// Über uns
	//if ( is_subpage_of( $post->ID, 'ueber-uns' ) ) {
	//	update_post_meta($post->ID, 'about-us_title', $_POST['about-us_title']);
	//}
}

// Cases meta box
function thinkmoto_case_details_meta_box() {

    global $post;
    $custom = get_post_custom($post->ID);  
	?>
		<p>
			<label><? _e("Year"); ?>:</label><br />
			<input name="thinkmoto_case_year" value="<?php echo $custom["thinkmoto_case_year"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Text / Keywords"); ?>:</label><br />
			<textarea name="thinkmoto_case_keywords" rows="4" style="width:98%;"><?php echo $custom["thinkmoto_case_keywords"][0]; ?></textarea>
		</p>
		<p>
			<label><? _e("Text / Keywords - <em>Englisch</em>"); ?>:</label><br />
			<textarea name="thinkmoto_case_keywords_en" rows="4" style="width:98%;"><?php echo $custom["thinkmoto_case_keywords_en"][0]; ?></textarea>
		</p>
		<p>
			<label><? _e("Link text (text placed before the link)"); ?>:</label><br />
			<input name="thinkmoto_case_linktext" value="<?php echo $custom["thinkmoto_case_linktext"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Link text (text placed before the link) - <em>Englisch</em>"); ?>:</label><br />
			<input name="thinkmoto_case_linktext_en" value="<?php echo $custom["thinkmoto_case_linktext_en"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Link (add 'http://')"); ?>:</label><br />
			<input name="thinkmoto_case_link" value="<?php echo $custom["thinkmoto_case_link"][0]; ?>" placeholder="http://" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Link (add 'http://') - <em>Englisch</em>"); ?>:</label><br />
			<input name="thinkmoto_case_link_en" value="<?php echo $custom["thinkmoto_case_link_en"][0]; ?>" placeholder="http://" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Technikpartner"); ?>:</label><br />
			<input name="thinkmoto_case_technicalpartner" value="<?php echo $custom["thinkmoto_case_technicalpartner"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Technikpartner - <em>Englisch</em>"); ?>:</label><br />
			<input name="thinkmoto_case_technicalpartner_en" value="<?php echo $custom["thinkmoto_case_technicalpartner_en"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Awards"); ?>:</label><br />
			<input name="thinkmoto_case_awards" value="<?php echo $custom["thinkmoto_case_awards"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Awards - <em>Englisch</em>"); ?>:</label><br />
			<input name="thinkmoto_case_awards_en" value="<?php echo $custom["thinkmoto_case_awards_en"][0]; ?>" type="text" style="width:98%;" />
		</p>
		<p>
			<label><? _e("Videos (zB. Embed Code von Vimeo)"); ?>:</label><br />
			<!-- <input name="thinkmoto_case_videos" value="<?php echo $custom["thinkmoto_case_videos"][0]; ?>" type="text" style="width:98%;" /> -->
			<textarea name="thinkmoto_case_videos" rows="8" style="width:98%;"><?php echo $custom["thinkmoto_case_videos"][0]; ?></textarea>
		</p>
	<?php
}
*/


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