<?php
/**
 * Wordpress Functions Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    1.3.2
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 * 23.10.2012 - Get content for a given post page
 * 			  - Add 'the_content_split' filter manually
 *			  - Twitter function
 *
 * 20.07.2012 - Split page content added
 * 02.12.2012 - New functions for pages (get_page_resume_by_slug, permalink and more text)
 * 03.12.2012 - Twitter Utils in a separate file
 * 				the_split_content();
 * 11.12.2012 - wp_custom_nav_menu()
 * 17.12.2012 - Add 'check_section' action
 * 08.01.2013 - wp_list_custom_posts()
 * 09.01.2013 - has_content()
 * 18.01.2013 - 'check_section' updated to get section in a taxonomy archive page
 * 29.01.2013 - Bug in 'get_content_page' fixed
 * 12.02.2013 - 'wp_list_post_types' for hierarchical list of posts from a given custom post type 
 *				(fixes bug in 'wp_list_pages' marking custom post as active)
 * 14.02.2013 - New function 'the_contact_content' and filter 'parse_contact_content' that
 *				add icons to the contact page
 * 28.02.2013 - 'get_page_ID_by_path' accepts custom post types
 * 12.02.2013 - 'content_split_pages' has default value for $num_pages
 *			  - 'get_sidebar_page'
 * 26.05.2013 - Remove 'custom_menu_order' (now in custom-post-types.php)
 * 30.05.2013 - Add 'clear_br_tags'
 * 11.06.2013 - Add 'get_first_term'
 * 16.06.2013 - Conditional Tags Functions - is_index()
 * 18.06.2013 - Convert URLs in a text to Links
 * 30.06.2013 - 'the_content_split' as function (not filter)
 * 08.07.2013 - 'get_first_term_object' added
 * 05.09.2013 - 'get_content_pages_count' added
 * 08.09.2013 - 'the_content_split' bug fixed
 */


// ==================
// ! LOOP Functions
// ==================

if ( !function_exists( 'get_the_slug' ) ) { 
		
	function get_the_slug() {
		global $post;
		return $post->post_name;
	}	
}

if ( !function_exists( 'the_slug' ) ) { 
		
	function the_slug() {
		echo get_the_slug();
	}	
}

// ==============================
// ! CONDITIONAL TAGS Functions
// ==============================

function get_current_template( $echo = false ) {

    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}

if (!function_exists('is_index')) {

	function is_index() {
		return is_home() || is_front_page() || get_current_template() == 'index.php';
	}
}

// ======================
// ! CATEGORY Functions
// ======================

function get_category_ID_by_slug( $slug ) {
	$cat = get_category_by_slug( $slug );
	if ($cat) return $cat->term_id;
}

function get_link_category_ID_by_slug( $slug ) {
	$cat = get_term_by('slug', $slug, 'link_category');	
	if ($cat) return $cat->term_taxonomy_id;
}

// =====================
// ! CONTENT Functions  
// =====================

function get_the_content_with_formatting ($more_link_text = '(more…)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

// Function that formats content like 'the_content'
function format_content( $content ) {

	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

// Function that removes "Private" in private posts
function the_title_trim( $title ) {
			
    $title = esc_attr($title);
    //$title = attribute_escape($title);
	
    $findthese = array(
        '#Privado:#',
        '#Privat:#',
        '#Protected:#',
        '#Private:#'
    );

    $replacewith = array(
        '', // What to replace "Privado:" with
        '', // What to replace "Privat:" with
        '', // What to replace "Protected:" with
        '' 	// What to replace "Private:" with
    );

    $title = preg_replace($findthese, $replacewith, $title);
    return $title;
}
add_filter('the_title', 'the_title_trim');


function the_content_limit( $max_char, $more_link_text = "", $stripteaser = 0, $more_file = '' ) {
	
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]', $content);
    $content = strip_tags($content);
 
	if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $espacio);

		echo '<span>'.$content.'</span>';

		if ( $more_link_text != "" ) {
			echo '&nbsp;<a class="more" href="';
			the_permalink();
			echo '">'.$more_link_text.'</a>';
		} else {
			echo "…";
		}
	}
	else {
		echo '<span>'.$content.'</span>';
		if ( strlen($content)>$max_char && $more_link_text != "" ) {		  
			if ( $more_link_text != "" ) {
				echo '&nbsp;<a class="more" href="';
				the_permalink();
				echo '">'.$more_link_text.'</a>';
			} else {
				echo "…";
			}
		}
	}
}

// Parse contact page content to print
function parse_contact_content($content) {
    $content = str_replace('Email:', '<span class="email">Email:</span>', $content);
    return $content;
}

function the_contact_content() {
	add_filter('the_content', 'parse_contact_content');
	the_content();
}

function the_excerpt_limit( $max_char ) {

    $content = get_the_excerpt();
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]', $content);
    $content = strip_tags($content);
 
	if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $espacio);

		echo $content . '…';
	}
	else {
		echo $content;
		
		if ( strlen($content)>$max_char && $more_link_text != "" ) {		  
			echo '…';
		}
	}
}

function get_manual_excerpt() {
	global $post;
	return ( has_excerpt() ) ? get_the_excerpt() : '';
}

function the_manual_excerpt($apply_filters = true) {
	
	$content = get_manual_excerpt();
	
	if ($apply_filters) {
		//$content = get_the_excerpt();
	    $content = apply_filters('the_content', $content);
	    $content = str_replace(']]>', ']]', $content);
    }
    
	echo $content;
}

// Remove empty paragraphs from content
function remove_empty_paragraphs($content) {

    /*$pattern = "/<p[^>]*><\\/p[^>]*>/";   
    $content = preg_replace($pattern, '', $content);*/
    $content = str_replace("<p></p>","",$content);
    return $content;
}
add_filter('the_content', 'remove_empty_paragraphs');

// Returns if a page has content or not. Returns a boolean.
function has_content() {
	global $post;
	return get_the_content() != '';
}

// Returns if a page has content or post thumbnail
function page_has_content() {
	
	$content = get_the_content();
	$content = preg_replace("/(\r\n){3,}/","\r\n\r\n", trim($content));
	$content = preg_replace("/ +/", " ", $content);
			
	//If you want to get rid of the extra space at the start of the line:
	//$content = preg_replace("/^ +/", "", $content);
	
	//wp_debug($content);
	
	return ( !empty($content) || has_post_thumbnail() );
}

// Function that changes all <br> tags in a content for a span element
function clear_br_tags($content) {
	$content = str_ireplace('<br />', '<span class="sep"></span>', $content);
	return $content;
}

// =====================
// ! Paginated Content
// =====================

// Using this function in the loop, returns the content split
// function the_split_content() {

// 	global $post;
// 	echo format_content(the_content_split());
// }

// This function is a filter that splits content by using the_content()
/*function the_content_split($content) {
	
	global $pages;
	
	if (count($pages) > 1) $content = content_split_pages();
	
	return $content;
}*/
//add_filter('the_content', 'the_content_split', 1);

function the_content_split($class = 'block') {

	global $pages;
	
	// if (count($pages) > 1) $content = content_split_pages($class);
	// echo $content;

	echo content_split_pages($class);
}

function content_split_pages($class = 'block', $num_columns = 3) {
	
	global $pages;
	
	$content = '';
	$result  = '';
	
	for($i = 0; $i < count($pages); $i++) {

		// Apply content formatting		
		$content = apply_filters('the_content', $pages[$i]);
		$content = str_replace(']]>', ']]>', $content);
		
		//$class = (($i+1)%$num_columns == 0) ? $class.' last' : $class;
        
        $result .= '<div class="'.$class.'">'.$content.'</div>';
    }
	
	return $result;
}

function get_content_pages_count() {
	global $pages;
	return count($pages);	
}

// Function that returns the content for a given page
function the_content_page($pagenum) {
    
    global $pages;
    
    if (count($pages) > 1 && count($pages) >= $pagenum) {
        $content = get_content_page($pagenum);    
    }
    
    echo $content;
}

function get_content_page($pagenum) {
    
    global $pages;
    
    if (count($pages) < $pagenum) return '';

    $content = $pages[$pagenum-1];

    // Remove split content filter
    remove_filter('the_content', 'the_content_split', 1);

    // Apply content formatting
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]>', $content);

    return $content;
}

// TEXT EDITOR - Add Next Page button on Tiny MCE editor
add_filter('mce_buttons','wysiwyg_editor');
function wysiwyg_editor($mce_buttons) {
	$mce_buttons = array('bold', 'italic', '|', 'bullist', 'numlist', 'blockquote', '|', 'justifyleft', 'justifycenter', 'justifyright', '|', 'link', 'unlink', '|', 'spellchecker', 'fullscreen', 'wp_adv', '|', 'wp_page');
	return $mce_buttons;
}

// ==========================
// ! Custom Field Functions
// ==========================

function get_post_custom_field_by_id($post_id, $custom_field, $end = '') {
	$meta = get_post_meta($post_id, $custom_field, true);
	return (( !empty($meta) ) ? $meta . $end : $meta);
}

function get_post_custom_field( $custom_field, $end = '') {
	global $post;
	$meta = get_post_meta($post->ID, $custom_field, true);
	//$meta = get_post_meta(get_the_ID(), $custom_field, true);
	return (( !empty($meta) ) ? $meta . $end : $meta);
}

/* Jobs */

function get_post_meta_dt( $post_id, $property_name, $title ) {
	
	$value =  get_post_meta($post_id, 'thinkmoto_job_' . $property_name, true);
	if ( !empty($value) ) echo '<dt>' . $title . ':</dt><dd>' . $value . '</dd>';
}

function get_post_details_list($post_id) {

	echo '<dl class="job-details group">';
	get_post_meta_dt($post_id, 'level',		'Level');
	get_post_meta_dt($post_id, 'employment',	'Anstellung');
	get_post_meta_dt($post_id, 'duration',	'Dauer');
	get_post_meta_dt($post_id, 'start',		'Beginn');
	echo '</dl>';
}

/**
*	Get all custom fields attached to a page
*/
if ( !function_exists('get_custom_fields_array') ) {
	function get_custom_fields_array()
	{
		global $post;
		$custom_fields = get_post_custom($post->ID);
		$hidden_field = '_';
		
		foreach( $custom_fields as $key => $value ){
			if( !empty($value) ) {
				$pos = strpos($key, $hidden_field);
				if( $pos !== false && $pos == 0 ) {
					unset($custom_fields[$key]);
				}
			}
		}

		return $custom_fields;
	}
}

if ( !function_exists('get_custom_fields_def_list') ) {

	function get_custom_fields_def_list($custom_field_labels, $list = 'ul')
	{
		$custom_fields = get_custom_fields_array();

		$return = '';
		
		if ($list == 'ul') {
			
			foreach( $custom_field_labels as $key => $value ){
				if( !empty($value) && !empty($custom_fields[$key][0])) {
					$return .= '<li><strong>' . $value . ':</strong> ' . $custom_fields[$key][0] . '</li>';
				}
			}
			$return = (!empty($return)) ? '<ul>' . $return . '</ul>' : '';
		
		} else {
			
			foreach( $custom_field_labels as $key => $value ){
				if( !empty($value) && !empty($custom_fields[$key][0])) {
					
					$return .= '<dt>' . $value . ':</dt><dd>' . $custom_fields[$key][0] . '</dd>';
				}
			}
			$return = (!empty($return)) ? '<dl>' . $return . '</dl>' : '';
		}

		return $return;
	}
}

// ==================
// ! POST Functions   
// ================== 

function get_first_category() {
	
	global $post;
	$category = get_the_category();
	return $category[0]->cat_name;
}

function the_first_category() {
	
	echo get_first_category();
}

function get_first_term_object($taxonomy) {

	global $post;

	$terms = get_the_terms( $post->ID, $taxonomy );

	$first_term = array_shift($terms);

	return $first_term;	
}

function get_first_term($taxonomy) {

	$first_term = get_first_term_object($taxonomy);
	
	if( $first_term ) {
		return $first_term->name;
	}
}

function the_first_term($taxonomy) {
	
	echo get_first_term($taxonomy);
}

/*function the_location_plain() {

    echo get_location_plain();
}

function get_location_plain() {

    global $post;

    $terms = get_the_terms( $post->ID, 'location' );
    if( $terms ) {
        $term_slugs = array_map('esc_attr', wp_list_pluck( $terms, 'name'));
        $return = implode(', ', $term_slugs);
    }

    return $return;
}*/


// ==================
// ! PAGE Functions  
// ==================

// Append page slug to body class
function add_body_class( $classes ) {
    global $post;
    if ( isset( $post ) && !is_home() ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_class' );


function is_subpage( $page_id ) {

	if ( $page_id ) {

		$post = get_page($page_id);
		$is_page = ($post != null);
	
	} else {
		global $post;
		$is_page = is_page();
	}
	                               
    if ( $is_page && $post->post_parent ) {		// test to see if the page has a parent
		return $post->post_parent;				// return the ID of the parent post
    } else {									// there is no parent so…
		return false;							// …the answer to the question is false
    }
}

function is_subpage_of_by_id( $parent_id ) {
	
	global $post;

	if (is_page()) {

		return $parent_id == $post->post_parent;
	}

	return false;
}

function is_subpage_of( $parent_slug ) {
	
	global $post;
	
	$parent_id = $post->post_parent;

	if (is_page() && $parent_id) {

		return $parent_id == get_page_ID_by_path($parent_slug);
	}

	return false;
}

function has_subpage() {
	
}

/*function is_subpage_of( $page_id, $parent_slug ) {
	
	if ( !$parent_slug ) return false;
	
	// get parent id
	$parent_id = is_subpage($page_id);
	
	//echo "is subpage of: " . $parent_id;
	
	if ( $parent_id ) {		
		return ($parent_id == get_page_ID_by_path($parent_slug));
	}
	
	return false;
}*/

function get_page_slug_by_ID($page_id) {

	$page = get_page($page_id);

	if ($page) return $page->post_name;
    else       return null;
}

function get_page_ID_by_path($page_slug, $post_type = 'page') {

    $page = get_page_by_path($page_slug, 'OBJECT', $post_type);
    
    if ($page) return $page->ID;
    else       return null;
}

function get_ID_by_page_name($page_name) {
   global $wpdb;
   $page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."' AND post_type = 'page'");
   return $page_name_id;
}

// This function returns formated content by slug. 
// setup_postdata, makes it safer (when working with global $post) than "get_page_content_by_slug"

function get_page_resume_by_slug($page_slug, $show_title = false, $more_text = '') {
    
	// global $post;
	// $post = get_page_by_path('beratung/herzlich-willkommen');

	// if ($post) :
 	// setup_postdata($post);

    $query_page = get_page_by_path($page_slug); 
    
    $return = '';
    if ($query_page) {

        //setup_postdata($query_page);
        
        if ($show_title) {
            $return .= '<h5>' . __($query_page->post_title) . '</h5>';
        }

        $return .= format_content($query_page->post_content);

        if (!empty($more_text)) {
			$return .= '<a class="more-link" href="'. get_permalink($query_page->ID) . '">' . $more_text . '</a>';
		}
	
        //wp_reset_postdata();
    }

    return $return;
}

// Use "get_page_resume_by_slug" instead

function get_page_content_by_slug($slug, $format_content = true, $more_text = '') {
	$query_page = get_page_by_path($slug);
	if (!$query_page) return "";

	$return = ($format_content) ? format_content($query_page->post_content) : false;

	if (!empty($more_text)) {
		$return .= '<a class="more-link" href="'. get_permalink($query_page->ID) . '">' . $more_text . '</a>';
	}

	return $return;
}

function get_page_link_by_slug($slug) {

	$page = get_page_by_path($slug);
	if (!$page) return "";

	return get_permalink($page->ID);
}

/*function has_children($child_of = null) {
    if(is_null($child_of)) {
            global $post;
            $child_of = ($post->post_parent != '0') ? $post->post_parent : $post->ID;
    }
    return (wp_list_pages("child_of=$child_of&echo=0")) ? true : false;
}*/

function has_children($post_id = null, $post_type = null) {

	if (is_null($post_id)) {
		global $post;
		$post_id = $post->ID;
	}

	if (is_null($post_type)) {
		global $post;
		$post_type = $post->post_type;
	}

    $children = get_pages("child_of=$post_id&post_type=$post_type");

    return (count( $children ) != 0) ? true : false;
}

// ================= 
// ! SIDEBAR Utils   
// ================= 

function get_sidebar_page() {

    // page content
    global $post;
    global $current_section;

    // If is subpage, get parent slug
    if ($post->post_parent != 0) {
        $post_parent = get_page( $post->post_parent );
        $parent_slug = $post_parent->post_name;
    }

    // Get page path
    $page_path = $parent_slug . '/' . $post->post_name . '/sidebar';

    // Get post
    $post = get_page_by_path( $page_path );

    if ($post) :
        $sidebar_page = true;
        setup_postdata($post);
?>
    <div class="page-widget post">
        <?php the_content(); ?>
    </div>
<?php
    wp_reset_postdata();
    endif;

    return $sidebar_page;
}


// ============== 
// ! MENU Utils   
// ============== 

// Change menu items ID adding related page slug

function change_nav_menu_id($current_id,$item_details){
	
	return 'menu-item-' . $item_details->post_name;
}
add_filter('nav_menu_item_id','change_nav_menu_id',10,2);

// Remove Navigation Container for custom menus
function my_wp_nav_menu_args( $args = '' )
{
	$args['container'] = false;
	return $args;
} // function

add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );


// Custom navigation menu
function wp_custom_nav_menu($menu_name, $show_home = false, $hide_active = false) {
		
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
    	
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
			
		$menu_items = wp_get_nav_menu_items($menu->term_id);
	
		$menu_list = '<ul id="menu-' . $menu_name . '" class="menu">';

		// Home link
		if ($show_home && !(is_home() || is_front_page())) {
			$menu_list .= '<li id="menu-item-home"><a href="' . get_option('home') . '/">' . __('Home') . '</a></li>';
		}
		
		// Display menu
		foreach ( (array) $menu_items as $key => $menu_item ) {
		
			//wp_debug($menu_item);
			
			$title     = $menu_item->title;
			$url       = $menu_item->url;
			$page_slug = $menu_item->post_name;
		    
			$is_active = is_page($menu_item->object_id);

			// Hide active if necessary
			if ($is_active && $hide_active) {
				continue;
			}

		    $class 	   = $is_active ? ' class="active"' : '';
		    
		    $menu_list .= '<li id="menu-item-' . $page_slug . '"'. $class .'><a href="' . $url . '">' . $title . '</a></li>';
		}
		$menu_list .= '</ul>';
		
		echo $menu_list;
	}
}

// Function that lists pages from the same Custom Post Type
// For hierarchical structure see next function 'wp_list_post_types'
function wp_list_custom_posts( $args = '', $exclude_current = false) {


    global $post;
    $post_ID = $post->ID;

    if ( is_array($args) )
        $opts =  &$args;
    else
        parse_str($args, $opts);

    // Defaults
    $defaults = array(
        'post_type'         => $post->post_type,   					// custom post type
        'posts_per_page'    => -1,
        // 'tax_query'     => array(            					// custom taxonomy
        //         array(
        //             'taxonomy' => 'cpt_leistung_category',
        //             'field'    => 'slug',
        //             'terms' => $leistung_category
        //         )
        //     ),
        'post_parent'       => 0,                   				// only top pages
        'exclude'           => $exclude_current ? $post_ID : '',    // exclude current page
        'order'             => 'ASC',
        'orderby'           => 'menu_order',
        'before_current'	=> ''
    );

    $opts = array_merge($defaults, $opts);

    // Get posts
    $myposts = get_posts( $opts );
    foreach( $myposts as $post ) :  setup_postdata($post);

    	$is_current = ($post_ID == get_the_ID());

	    if ($exclude_current && $is_current)
	    	continue;

	    if ($is_current) :
?>
	<li class="active"><a href="<?php the_permalink(); ?>"><?php echo ($opts['before_current'] != '') ? $opts['before_current'] . ' ' : ''; the_title(); ?></a></li>
<?php else: ?>
	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endif; ?>

<?php 
    endforeach; 
    wp_reset_postdata();
}

// Similar to 'wp_list_custom_posts' but hierarchical (and marks current post type with a class)
function wp_list_post_types( $args ) {
    $defaults = array(
        'numberposts'  => -1,
        'offset'       => 0,
        'orderby'      => 'menu_order',
        //'orderby'      => 'menu_order, post_title',
        'order'        => 'ASC',
        'post_type'    => 'page',
        'depth'        => 0,
        'show_date'    => '',
        'date_format'  => get_option('date_format'),
        'child_of'     => 0,
        'exclude'      => '',
        'include'      => '',
        'title_li'     => __('Pages'),
        'echo'         => 1,
        'link_before'  => '',
        'link_after'   => '',
        'exclude_tree' => '' );

    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );

    $output = '';
    $current_page = 0;

    // sanitize, mostly to keep spaces out
    $r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

    // Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
    $exclude_array = ( $r['exclude'] ) ? explode(',', $r['exclude']) : array();
    $r['exclude'] = implode( ',', apply_filters('wp_list_post_types_excludes', $exclude_array) );

    // Query pages.
    $r['hierarchical'] = 0;
    $pages = get_posts($r);

    if ( !empty($pages) ) {
        if ( $r['title_li'] )
            $output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

        global $wp_query;
        if ( ($r['post_type'] == get_query_var('post_type')) || is_attachment() )
            $current_page = $wp_query->get_queried_object_id();
        $output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

        if ( $r['title_li'] )
            $output .= '</ul></li>';
    }

    $output = apply_filters('wp_list_pages', $output, $r);

    if ( $r['echo'] )
        echo $output;
    else
        return $output;
}

// ===================== 
// ! Section Utils   
// =====================

// Function that saves in a global variable the current section of a page by its top level page
function check_section() {
    
    global $current_section;
    global $wp_query;
    global $post;

    // Pages
    if (is_page()) {
       
        // Check parent
        $post_parent = $post->post_parent;

        // subpage
        if ($post_parent) {
            $current_section = get_page_slug_by_ID($post_parent);
        // parent page
        } else {
            $current_section = $post->post_name;
        }

    // Custom Post Types
    } else if (is_single() || is_post_type_archive() || is_tax()) {

    	// If is taxonomy, get the post type related to it
    	if (is_tax()) {
    		$taxonomy = get_taxonomy( get_query_var('taxonomy') );
    		if ($taxonomy && count($taxonomy->object_type) >= 1) {
    			$post_type = $taxonomy->object_type[0];
    		}

    	// Custom Post Type
    	} else {

        	$post_type = get_post_type();
    	}

        $post_type_obj = get_post_type_object($post_type);

        // Get post type slug
        $post_type_slug = $post_type_obj->rewrite[slug];

        // Filter if page slug has a "/"
        $post_type_slug_array = explode('/', $post_type_slug);
        $post_type_slug = $post_type_slug_array[0];

    	// If there's a page with same rewrite rule as slug, check section by page
    	$page = get_page_by_path($post_type_slug);

	    if ($page) {

	    	$post_parent = $page->post_parent;

	    	// subpage
	    	if ($post_parent) {
	    	    $current_section = get_page_slug_by_ID($post_parent);
	    	// parent page
	    	} else {
	    	    $current_section = $page->post_name;
	    	}

	    } else {

	    	$current_section = $post_type;
	    }
    }
}
//add_action('wp_head', 'check_section');


// ================
// ! STRING Utils   
// ================ 

function get_relative_url( $permalink ) {
	
	//$siteurl = get_bloginfo('siteurl');
	//return str_replace($siteurl, '', $permalink);

	$siteurl = get_bloginfo('siteurl') . "/";
	return rtrim( str_replace($siteurl, '', $permalink), "/");
}

// Find URLs in Text, Make Links
function format_text_links($text) {

	// The Regular Expression filter
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

	// Check if there is a url in the text
	if(preg_match($reg_exUrl, $text, $url)) {

       // make the urls hyper links
       $formated_text = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $text);

	} else {

       // if no urls in the text just return the text
       $formated_text = $text;
	}

	return $formated_text;
}

// Replace single <br> for a <span> tag
function nls2span($str)
{
  return str_replace('<span></span>', '', '<span>' 
        . preg_replace('#([\r\n]\s*?[\r\n]){1,}#', '</span>$0<span>', $str) 
        . '</span>');
}

// Replace single <br> for a <p> tag
function nls2p($str)
{
  return str_replace('<p></p>', '', '<p>' 
        . preg_replace('#([\r\n]\s*?[\r\n]){1,}#', '</p>$0<p>', $str) 
        . '</p>');
}
?>