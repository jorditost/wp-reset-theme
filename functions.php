<?php
/**
 * Functions File
 *
 * @copyright  Copyright © 2013 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 * 02.07.2013 - Added jQuery in footer variable
 *              Global variable for site strings (for jQuery)
 *              More advanced clean wp_head
 *
 * Note: PHP vars are lowercase.
 *       Vars that are passed to jQuery are camelcase.   
 */


// ================ 
// ! Custom Utils   
// ================ 

require_once('inc/utils/Mobile_Detect.php');
require_once('inc/wp-utils/wp-utils.php');
//require_once('inc/wp-utils/wp-language-utils.php');
require_once('inc/wp-utils/wp-gallery-utils.php');
require_once('inc/custom-post-types.php');

// Mobile detection
global $detect;
global $isMobile;
global $isIpad;
global $isTablet;
global $isIE;

$detect   = new Mobile_Detect();
$isMobile = ( $detect->isMobile() ) ? true : false;
$isIpad   = ( $detect->isIpad() )   ? true : false;
$isTablet = ( $detect->isTablet() ) ? true : false;
$isIE     = ( $detect->isIE() )     ? true : false;

// Load jQuery in Footer
global $load_jquery_in_footer;
$load_jquery_in_footer = true;

// Test vars
global $test;
$test = true;

function mobile_class() {

    global $isMobile; 
    global $isIpad; 

    if ($isMobile || $isIpad || $isTablet) { 
        echo " mobile"; 
    }
}

// ====================== 
// ! BACKEND Functions   
// ======================

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

function get_include_part($file_name) {
    include (TEMPLATEPATH . '/inc/' . $file_name . '.php' );
}

// ====================== 
// ! Language Functions   
// ======================

// global $sitetrings;
// $siteStrings = array(
//         'see_all_activities' => __('[:en]See all activities[:de]Komplettes Programm sehen'),
//         'loading_activities' => __('[:en]Loading activities...[:de]Das Programm wird geladen...'),
//         'loading_error'      => __('[:en]A loading error occurred. Please try again later.[:de]Beim Laden der Seite ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.'),
//         'find_out_more'      => __('[:en]Find out more[:de]Mehr erfahren'),
//         'register'           => __('[:en]Register here[:de]Hier registrieren'),
//         'close'              => __('[:en]Close[:de]Schliessen')
//     );


// ===================== 
// ! Content Functions   
// =====================


// ============================= 
// ! Theme Admin Customization
// =============================

// Edit admin menus
// http://wp.tutsplus.com/tutorials/creative-coding/customizing-your-wordpress-admin/
function edit_admin_menus() {

    global $menu;
    
    // Change Posts to Blog
    //$menu[5][0] = 'Blog';

    // Remove menus
    //remove_menu_page('edit.php');           // Remove Posts
    remove_menu_page('edit-comments.php');  // Remove Comments
    //remove_menu_page('link-manager.php'); // Remove Links
    //remove_menu_page('tools.php');
}
add_action( 'admin_menu', 'edit_admin_menus' );

// Custom Menu Order
// function custom_menu_order($menu_ord) {
//     if (!$menu_ord) return true;
//     return array(
//         'index.php',                    // this represents the dashboard link
//         //'edit.php',                   //the posts tab
//         'edit.php?post_type=page',       //the pages tab
//         //'edit.php?post_type=umfrage'   //the "umfrage" tab
//     );
// }
// add_filter('custom_menu_order', 'custom_menu_order');
// add_filter('menu_order', 'custom_menu_order');

// Remove category from edit/add new post screen
// function my_list_terms_exclusions( $exclusions, $args ) {
//   global $pagenow;
//   if (in_array($pagenow,array('post.php','post-new.php'))) {
//     $exclusions = " {$exclusions} AND t.slug NOT IN ('twitter')";
//   }
//   return $exclusions;
// }
// add_filter('list_terms_exclusions', 'my_list_terms_exclusions', 10, 2);

// Remove admin bar
add_filter('show_admin_bar', '__return_false');

// ==================
// ! Notifications
// ==================

// Remove WP update notifications
// add_action('admin_menu','wphidenag');
// function wphidenag() {
//     remove_action( 'admin_notices', 'update_nag', 3 );
// }

// Remove notifications for login plugins
// function filter_plugin_updates( $value ) {
//     unset( $value->response['akismet/akismet.php'] );
//     unset( $value->response['wp-members/wp-members.php'] );
//     return $value;
// }
// add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

// Avoid plugin deactivation
// function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
//     // Remove edit link for all
//     if ( array_key_exists( 'edit', $actions ) )
//         unset( $actions['edit'] );
//     // Remove deactivate link for crucial plugins
//     if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
//         'theme-my-login/theme-my-login.php',
//         'wp-members/wp-members.php',
//         'wp-better-emails/wpbe.php'
//     )))
    
//     unset( $actions['deactivate'] );
//     return $actions;
// }
// add_filter( 'plugin_action_links', 'disable_plugin_deactivation', 10, 4 );

// ===============
// ! Theme Setup   
// ===============

add_action( 'after_setup_theme', 'my_theme_setup' );

if ( ! function_exists( 'my_theme_setup' ) ):

function my_theme_setup() {
    
    // Tiny MCE styles
    add_editor_style();
    
    // This theme uses post thumbnails
    add_theme_support( 'post-thumbnails' );
    
    // Register extra featured images
    // http://wordpress.org/plugins/multiple-post-thumbnails/
    /*if (class_exists('MultiPostThumbnails')) {
        new MultiPostThumbnails(
            array(
                'label' => 'Secondary Image',
                'id' => 'secondary-image',
                'post_type' => 'post'
            )
        );

        // Array with all registered IDs for compatibility with wp-gallery-utils.php
        global $exclude_thumb_ids;
        //$exclude_thumb_ids = array('secondary-image');
        $exclude_thumb_ids = array(
                'post'      => array('secondary-image'),
                'custom_pt' => array('secondary-image-cpt')
            );
    }*/

    // Images size
    /*if ( function_exists( 'add_image_size' ) ) { 
        
        set_post_thumbnail_size( 320, 200 );            // Retrieved as 'post-thumbnail'
        
        add_image_size( 'single-column', 320, 9999 );   // 320 pixels wide (and unlimited height)
        add_image_size( 'primary-column', 660, 9999 );  // 660 pixels wide (and unlimited height)
    }*/
    
    // Add support for menus
    register_nav_menu('main-menu', 'Main menu');
    register_nav_menu('footer-menu', 'Footer menu');
    
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );
}
endif;

// Excerpt box for Pages
if ( function_exists('add_post_type_support') ) {
    add_action('init', 'add_page_excerpts');
    function add_page_excerpts() {        
        add_post_type_support( 'page', 'excerpt' );
    }
}

// ================ 
// ! Init Scripts   
// ================ 

function my_scripts_method() {
    
    // Register jQuery
    // =================
    // by using the wp_enqueue_scripts hook (instead of the init hook which many articles reference), 
    // we avoid registering the alternate jQuery on admin pages, which will cause post editing (amongst other things) 
    // to break after upgrades often.
    
    global $load_jquery_in_footer;

    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', ( "//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ), false, false, $load_jquery_in_footer);

    global $detect;
    global $test;
    global $isMobile;
    global $isIpad;
    global $isTablet;
    global $isIE;
    global $siteStrings;
    
    // iScroll
    // if ( $isMobile ) {
    //     wp_enqueue_script('jquery-iscroll', get_template_directory_uri().'/js/iscroll-snap.min.js', array('jquery'), null, true);
    //     $deps = array('jquery', 'jquery-pjax', 'jquery-iscroll');
    // } else {
    
    //     $deps = array('jquery');
    // }
    
    $deps = array('jquery');
    
    // Site functions
    if ( $test ) {
        wp_enqueue_script('theme_functions', get_template_directory_uri().'/js/functions.js', $deps, null, true);
    } else {
        wp_enqueue_script('theme_functions', get_template_directory_uri().'/js/functions.min.js', $deps, null, true);
    }   
    
    // Localize script to use 'siteVars' in functions.js file
    wp_localize_script(
        'theme_functions', 
        'siteVars', 
        array( 
            'siteurl'     => get_option('siteurl'),
            //'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            //'lang'        => get_language_code(),
            //'siteStrings' => json_encode($siteStrings),
            'isMobile'    => $isMobile,
            'isIpad'      => $isIpad,
            'isTablet'    => $isTablet,
            'isIE'        => $isIE
        )
    );
    
    // Remove "Comment Reply" scripts
    wp_dequeue_script('comment-reply');
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method', 20 );

///////////////////
// Admin Scripts
///////////////////

/*function my_admin_scripts_method() {

    //wp_register_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker', get_template_directory_uri().'/js/ui.datepicker.js', array('jquery','jquery-ui-core'));

    wp_enqueue_script('admin-js-functions',get_template_directory_uri().'/js/admin.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'));

    //path to jQuery UI theme stylesheet
    wp_enqueue_style('jquery-ui','http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');

    wp_print_styles();
}
add_action('admin_print_scripts', 'my_admin_scripts_method');*/


// =============================== 
// ! HTML5 Reset initializations   
// =============================== 
    
// Clean up the <head>
function remove_head_links() {
    // remove_action('wp_head', 'rsd_link');
    // remove_action('wp_head', 'wlwmanifest_link');

    remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
    remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
    remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
    remove_action( 'wp_head', 'index_rel_link' ); // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
}
add_action('init', 'remove_head_links');
//remove_action('wp_head', 'wp_generator');
?>