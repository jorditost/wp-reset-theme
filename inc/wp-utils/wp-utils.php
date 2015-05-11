<?php
/**
 * Wordpress Functions Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */

/////////////////////
// DEBUG Functions   
/////////////////////

function print_query() {
    global $wp_query;
    wp_debug($wp_query);
}

function wp_debug($variable,$die=false){
    echo '<pre>'.print_r($variable, true).'</pre>';
    if($die) die();
}

add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}


////////////////////////
// Template Functions   
////////////////////////

function get_include_part($file_name) {
    include (TEMPLATEPATH . '/inc/' . $file_name . '.php' );
}

function get_current_template( $echo = false ) {

    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
    if( $echo )
        echo $GLOBALS['current_theme_template'];
    else
        return $GLOBALS['current_theme_template'];
}


////////////////////////////////
// CONDITIONAL TAGS Functions
////////////////////////////////

if (!function_exists('is_index')) {

	function is_index() {
		return is_home() || is_front_page() || get_current_template() == 'index.php';
	}
}


////////////////////
// LOOP Functions
////////////////////

if ( !function_exists('get_the_slug') ) { 	
	function get_the_slug() {
		global $post;
		return $post->post_name;
	}	
}

if ( !function_exists('the_slug') ) { 
	function the_slug() {
		echo get_the_slug();
	}	
}


/////////////////////
// TITLE Functions
/////////////////////

// Function that removes "Private" in private posts
function the_title_trim( $title ) {
			
    $title = esc_attr($title);
	
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


///////////////////////
// CONTENT Functions  
////////////////////////

// Function that formats content like 'the_content'
function format_content( $content ) {

	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

function get_the_content_with_formatting ($more_link_text = '(more…)', $stripteaser = 0, $more_file = '') {
	
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = format_content($content);
	return $content;
}

// Get content with a maximum number of chars
function the_content_limit( $max_char, $more_link_text = "", $stripteaser = 0, $more_file = '' ) {
	
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = format_content($content);
    $content = strip_tags($content);
 
	if ((strlen($content)>$max_char) && ($blank_space = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $blank_space);

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

// Remove empty paragraphs from content
function remove_empty_paragraphs($content) {

    /*$pattern = "/<p[^>]*><\\/p[^>]*>/";   
    $content = preg_replace($pattern, '', $content);*/
    $content = str_replace("<p></p>","",$content);
    return $content;
}
add_filter('the_content', 'remove_empty_paragraphs');

// Solution to WordPress adding br and p tags around shortcodes
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 12);

// Filter paragraphs on images
function filter_ptags_on_images($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
//add_filter('the_content', 'filter_ptags_on_images');

// Returns if a page has content or not. Returns a boolean.
function has_content() {
	global $post;

	$content = get_the_content();
	$content = preg_replace("/(\r\n){3,}/","\r\n\r\n", trim($content));
	$content = preg_replace("/ +/", " ", $content);

	//If you want to get rid of the extra space at the start of the line:
	$content = preg_replace("/^ +/", "", $content);

	return (!empty($content));
}

// Returns if a page has content or post thumbnail
function has_content_or_thumbnail() {
	
	return ( has_content() || has_post_thumbnail() );
}


///////////////////////
// Paginated Content
///////////////////////

function get_the_content_split($class = 'block') {
	
	global $pages;
	
	$content = '';
	$result  = '';
	
	if (count($pages) > 1) {
		for($i = 0; $i < count($pages); $i++) {
			$content = $pages[$i];
	        $result .= '<div class="'.$class.'">'.$content.'</div>';
	    }
	} else {
		$result = get_the_content();
	}
	
	return $result;
}

function the_content_split($class = 'block') {

	echo format_content(get_the_content_split($class));
}

// This function is a filter that splits content by using the_content()
function the_content_split_filter($content) {
	global $pages;
	if (count($pages) > 1) $content = get_the_content_split();
	return $content;
}
//add_filter('the_content', 'the_content_split_filter', 1);

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
    remove_filter('the_content', 'the_content_split_filter', 1);

    // Apply content formatting
    $content = format_content($content);

    return $content;
}


///////////////////////
// EXCERPT Functions
///////////////////////

function get_manual_excerpt_or_resume($max_chars) {
	global $post;
	return (has_excerpt()) ? get_the_excerpt() : get_the_content_limit($max_chars);
}

function get_manual_excerpt() {
	global $post;
	return (has_excerpt()) ? get_the_excerpt() : '';
}

function the_manual_excerpt($apply_filters = true) {
	
	$content = get_manual_excerpt();
	
	if ($apply_filters) {
	    $content = format_content($content);
    }
    
	echo $content;
}

// Get the excerpt with a maximum number of chars
function the_excerpt_limit( $max_char ) {

    $content = get_the_excerpt();
    $content = format_content($content);
    $content = strip_tags($content);
 
	if ((strlen($content)>$max_char) && ($blank_space = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $blank_space);

		echo $content . '…';
	}
	else {
		echo $content;
		
		if ( strlen($content)>$max_char && $more_link_text != "" ) {		  
			echo '…';
		}
	}
}

// Get the content with a maximum number of chars
function get_the_content_limit( $max_char ) {

    $content = get_the_content();
    //$content = format_content($content);
    //$content = strip_tags($content);
 
	if ((strlen($content)>$max_char) && ($blank_space = strpos($content, " ", $max_char ))) {
		$content = substr($content, 0, $blank_space);

		echo $content . '…';
	}
	else {
		echo $content;
		
		if ( strlen($content)>$max_char && $more_link_text != "" ) {		  
			echo '…';
		}
	}
}

function get_more_link($permalink, $more_text) {
	return '<a class="more-link" href="'. $permalink . '">' . $more_text . '</a>';
}


////////////////////////
// CATEGORY Functions
////////////////////////

function get_category_ID_by_slug( $slug ) {
	$cat = get_category_by_slug( $slug );
	if ($cat) return $cat->term_id;
}

function get_link_category_ID_by_slug( $slug ) {
	$cat = get_term_by('slug', $slug, 'link_category');	
	if ($cat) return $cat->term_taxonomy_id;
}

function get_first_category() {
	
	global $post;
	$category = get_the_category();
	return $category[0]->cat_name;
}

function the_first_category() {
	
	echo get_first_category();
}

function get_first_term_object($taxonomy, $only_parent_cat = false) {

	global $post;
	$terms = get_the_terms( $post->ID, $taxonomy );
	
	if ($terms && !is_wp_error($terms)) {
		$first_term = array_shift($terms);


		// If hierarchical, add parent term for child terms
        if ($only_parent_cat && $first_term->parent != 0) {

        	// Parent term
        	$parent_term = get_term($first_term->parent, $taxonomy);
        	if ($parent_term) {
        		return $parent_term;
        	}
        }

		return $first_term;	
	}
}

function get_first_term($taxonomy, $only_parent_cat = false) {

	$first_term = get_first_term_object($taxonomy);
	
	if( $first_term ) {

		// If hierarchical, add parent term for child terms
        if ($only_parent_cat && $first_term->parent != 0) {

        	// Parent term
        	$parent_term = get_term($first_term->parent, $taxonomy);
        	if ($parent_term) {
        		return $parent_term->name;
        	}
        }

		return $first_term->name;
	}
}

function the_first_term($taxonomy, $only_parent_cat = false) {
	
	echo get_first_term($taxonomy, $only_parent_cat);
}

function get_first_term_slug($taxonomy, $only_parent_cat = false) {

	$first_term = get_first_term_object($taxonomy);
	
	if ($first_term) {

		// If hierarchical, add parent term for child terms
        if ($only_parent_cat && $first_term->parent != 0) {

        	// Parent term
        	$parent_term = get_term($first_term->parent, $taxonomy);
        	if ($parent_term) {
        		return $parent_term->slug;
        	}
        }

		return $first_term->slug;
	}
}

// Outputs all terms of a given taxonomy to use them as classes (for filtering)
function get_the_terms_classes($taxonomy) {

    global $post;
    $tax_obj = get_taxonomy($taxonomy);
    if (!$tax_obj) { return; }

    $terms = get_the_terms( $post->ID, $taxonomy );
                            
    if ($terms && !is_wp_error($terms)) {

        $terms_array = array();
        foreach ($terms as $term) {
            $terms_array[] = $term->slug;

            // If hierarchical, add parent term for child terms
            if ($tax_obj->hierarchical && $term->parent != 0) {

            	// Parent term
            	$parent_term = get_term($term->parent, $taxonomy);
            	if ($parent_term) {
            		$terms_array[] = $parent_term->slug;
            	}
            }
        }

        // Check repeated values
        $terms_array = array_unique($terms_array);

        // Return categories separated by an empty space
        return join(' ', $terms_array);
    }
}

// Outputs the terms string splitted by a separator
function get_the_terms_string($taxonomy, $sep = ',') {

    global $post;
    $terms = get_the_terms( $post->ID, $taxonomy );
                            
    if ($terms && !is_wp_error($terms)) {

        $terms_array = array();
        foreach ($terms as $term) {
            $terms_array[] = $term->name;
        }
        return join($sep . ' ', $terms_array);
    }
}

////////////////////
// PAGE Functions  
////////////////////

// Append page slug to body class
function add_body_class( $classes ) {
    global $post;
    if ( isset( $post ) && !is_home() ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_class' );

function get_page_ID_by_path($path, $post_type = 'page') {

    $page = get_page_by_path($path, 'OBJECT', $post_type);
    
    if ($page) return $page->ID;
    else       return null;
}

function get_page_ID_by_page_name($page_name) {
   global $wpdb;
   $page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."' AND post_type = 'page'");
   return $page_name_id;
}

// Get page slug by ID
function get_page_slug_by_ID($page_id) {

	$page = get_page($page_id);

	if ($page) return $page->post_name;
    else       return null;
}

// Get page link by path
function get_page_link_by_path($path) {

	$page = get_page_by_path($path);
	if (!$page) return "";

	$permalink = get_permalink($page->ID);

	return (function_exists('qtrans_getLanguage')) ? qtrans_convertURL($permalink) : $permalink;
}

// Get page title by path
function get_page_title_by_path($path) {

	$page = get_page_by_path($path);
	if (!$page) return "";

	return (function_exists('qtrans_getLanguage')) ? __($page->post_title) : $page->post_title;
}

// Get page content by path
function get_page_content_by_path($path, $format_content = true, $more_text = '') {

	$query_page = get_page_by_path($path);
	if (!$query_page) return "";

	$return = ($format_content) ? format_content($query_page->post_content) : $query_page->post_content;

	if (!empty($more_text)) {
		$return .= get_more_link(get_permalink($query_page->ID), $more_text);
	}

	return $return;
}

// This function returns formated content by path. 
// setup_postdata, makes it safer (when working with global $post) than "get_page_content_by_slug"
function get_page_resume_by_path($path, $show_title = false, $more_text = '') {

    $query_page = get_page_by_path($path); 
    
    $return = '';
    if ($query_page) {
        
        if ($show_title) {
            $return .= '<h5>' . __($query_page->post_title) . '</h5>';
        }

        $return .= format_content($query_page->post_content);

        if (!empty($more_text)) {
			$return .= get_more_link(get_permalink($query_page->ID), $more_text);
		}
    }

    return $return;
}

// Subpages functions
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


////////////////////////////
// CUSTOM FIELD Functions
////////////////////////////

function get_post_custom_field_by_id($post_id, $custom_field) {
	return get_post_meta($post_id, $custom_field, true);
}

function get_post_custom_field($custom_field) {
	global $post;
	return get_post_custom_field_by_id($post->ID, $custom_field);
}

// Get an array with all custom fields attached to a page
if ( !function_exists('get_custom_fields_array') ) {
	
	function get_custom_fields_array() {
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

// Show all custom fields attached to a page
if ( !function_exists('get_custom_fields_def_list') ) {

	function get_custom_fields_def_list($custom_field_labels, $list = 'ul') {

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


///////////////////
// SIDEBAR Utils   
///////////////////

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
    $query_page = get_page_by_path( $page_path );

    if ($query_page) :
        $sidebar_page = true;
    	$post = $query_page;
        setup_postdata($post);
?>
    <div class="page-widget">
        <?php the_content(); ?>
    </div>
<?php
	wp_reset_postdata();
    endif;

    return $sidebar_page;
}


////////////////
// MENU Utils   
////////////////

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
// !!! This function only works for non hierarchical menus
function wp_custom_nav_menu($menu_name, $show_home = false, $hide_active = false) {
	
	// Value retrieved with action 'check_section'
    global $current_section;

    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
    	
		$menu 		= wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
	
		$menu_list  = '<ul id="menu-' . $menu_name . '" class="menu">';

		// Home link
		if ($show_home && !(is_home() || is_front_page())) {
			$menu_list .= '<li id="menu-item-home"><a href="' . get_option('home') . '/">' . __('Home') . '</a></li>';
		}
		
		// Display menu
		foreach ((array) $menu_items as $key => $menu_item) {

			// Parent pages only
			if ($menu_item->menu_item_parent != 0) 
				continue;
		
			$object_id = $menu_item->object_id;
			$title     = $menu_item->title;
			$url       = $menu_item->url;
			$page_slug = $menu_item->post_name;

			$object_id = $menu_item->object_id;
			$title     = $menu_item->title;
			$url       = function_exists('qtrans_getLanguage') ? qtrans_convertURL($menu_item->url) : $menu_item->url;
			$page_slug = ($menu_item->object == 'page') ? get_page_slug_by_ID($object_id) : $menu_item->post_name;

			$is_active = (isset($current_section)) ? ($page_slug == $current_section) : is_page($menu_item->object_id);

			// Hide active if necessary
			if ($is_active && $hide_active) {
				continue;
			}

		    $class = $is_active ? ' class="active"' : '';
		    
		    $menu_list .= '<li id="menu-item-' . $page_slug . '"'. $class .'><a href="' . $url . '">' . $title . '</a></li>';
		}
		$menu_list .= '</ul>';
		
		echo $menu_list;
	}
}


////////////////////////////////////////////
// CUSTOM POST TYPE Archives & List Utils   
////////////////////////////////////////////

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
        //             'taxonomy' => 'taxonomy_id',
        //             'field'    => 'slug',
        //             'terms' 	  => 'term_id'
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

// This function shows links to the post archives for a given post type. 
// It only accepts 'monthly' and 'yearly' archives. Default is 'monthly'
function wp_get_custom_post_archives($args, $echo = true) {

	$post_type    = isset($args['post_type'])    ? $args['post_type']    : 'post';
	$rewrite_slug = isset($args['rewrite_slug']) ? $args['rewrite_slug'] : $post_type;
	$type    	  = isset($args['type'])  		 ? $args['type']  		 : 'monthly';

    global $wpdb; 
    $sql = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = %s AND post_status = 'publish' GROUP BY YEAR(wp_posts.post_date), MONTH(wp_posts.post_date) ORDER BY wp_posts.post_date DESC", $post_type);
    $results = $wpdb->get_results($sql);

    if ( $results ) {

        foreach ($results as $r) {

            $year = date('Y', strtotime( $r->post_date ) );
            $month = date('F', strtotime( $r->post_date ) );
            $month_num = date('m', strtotime( $r->post_date ) );
            
            if ($type == 'yearly') {
                $link = get_bloginfo('siteurl') . '/' . $rewrite_slug . '/' . $year;
                $text = $year;
            } else {
                $link = get_bloginfo('siteurl') . '/' . $rewrite_slug . '/' . $year . '/' . $month_num;
                $text = $month . ' ' . $year;
            }

            $output .= '<li><a href="' . $link . '">' . $text . '</a></li>';
        }

        if (!$echo) {
            return $output;
        }

        echo $output;
    }
    
    return false;
}


///////////////////
// SECTION Utils   
///////////////////

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
        	$post_type = get_post_type() ? get_post_type() : get_query_var('post_type');
    	}

        $post_type_obj = get_post_type_object($post_type);

        // Get post type slug
        $post_type_slug = $post_type_obj->rewrite['slug'];
        // $post_type_slug = $post_type_obj->rewrite[slug];

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


//////////////////
// STRING Utils   
//////////////////

function get_relative_url($permalink) {

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
function nls2span($str) {

  return str_replace('<span></span>', '', '<span>' 
        . preg_replace('#([\r\n]\s*?[\r\n]){1,}#', '</span>$0<span>', $str) 
        . '</span>');
}

// Replace single <br> for a <p> tag
function nls2p($str) {

  return str_replace('<p></p>', '', '<p>' 
        . preg_replace('#([\r\n]\s*?[\r\n]){1,}#', '</p>$0<p>', $str) 
        . '</p>');
}

// Replace new line for <br> tag
function nls2br($str) {

  return str_replace("\n", '<br />', $str);
}

// Function that changes all <br> tags in a content for a span element
function clear_br_tags($content) {
	$content = str_ireplace('<br />', '<span class="sep"></span>', $content);
	return $content;
}

// Beautify links
function get_pretty_link($link) {

    // Remove http://
    $link = str_replace('http://', '', $link);

    // Remove "/" at the end
    $link = rtrim($link, '/');

    return $link;
}
?>