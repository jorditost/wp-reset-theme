<?php
/**
 * Functions File
 *
 * @copyright  Copyright © 2013 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 * Notes: PHP vars are lowercase.
 *        Vars that are passed to jQuery are camelcase.   
 */


//////////////////
// Custom Utils   
////////////////// 

require_once('inc/utils/Mobile_Detect.php');
require_once('inc/wp-utils/wp-utils.php');
require_once('inc/wp-utils/wp-language-utils.php');
require_once('inc/wp-utils/wp-gallery-utils.php');
require_once('inc/wp-utils/wp-client-utils.php');
require_once('inc/custom-post-types.php');


/////////////////////
// Inits & Globals
/////////////////////

// Load jQuery in Footer
global $load_jquery_in_footer;
$load_jquery_in_footer = true;

// Test vars
global $test;
$test = true;


//////////////////////
// Mobile Detection
//////////////////////

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

function mobile_class() {

    global $isMobile; 
    global $isIpad; 
    global $isTablet; 

    if ($isMobile) { 
        echo " mobile"; 
    }

    if ($isIpad || $isTablet) { 
        echo " tablet"; 
    }
}


////////////////////////
// Section Functions   
////////////////////////

//add_action('wp_head', 'check_section');

function get_section_class() {
    global $current_section;
    return (isset($current_section) && !empty($current_section)) ? 'section-' . $current_section : '';
}


////////////////////////
// Language Functions   
////////////////////////

global $sitetrings;
$siteStrings = array(
        // Usage: 'text_id' => __('[:en]Text in English[:de]Text auf Deutsch')
    );

function get_site_text($text_id) {
    global $siteStrings;
    return $siteStrings[$text_id];
}

function get_language_code() {
    if (function_exists('qtrans_getLanguage')) {
        return qtrans_getLanguage();
    }
    return '';
}

// Get a custom field for the current language
function get_post_custom_field_lang($custom_field) {
    $lang = get_language_code();
    $lang_ext = (!empty($lang)) ? '_' . $lang : '';

    return get_post_custom_field($custom_field . $lang_ext);
}


///////////////////////
// Content Functions   
///////////////////////




///////////////////////////////
// Theme Admin Customization
///////////////////////////////

// Edit admin menus
// http://wp.tutsplus.com/tutorials/creative-coding/customizing-your-wordpress-admin/
function edit_admin_menus() {

    global $menu;
    
    // Change Posts to Blog
    //$menu[5][0] = 'Blog';

    // Remove menus
    //remove_menu_page('edit.php');         // Remove Posts
    remove_menu_page('edit-comments.php');  // Remove Comments
    //remove_menu_page('link-manager.php'); // Remove Links
    //remove_menu_page('tools.php');
}
add_action( 'admin_menu', 'edit_admin_menus' );

// Custom Menu Order
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
    return array(
        'index.php',                    // this represents the dashboard link
        //'edit.php',                   //the posts tab
        'edit.php?post_type=page',       //the pages tab
        //'edit.php?post_type=umfrage'   //the "umfrage" tab
    );
}
//add_filter('custom_menu_order', 'custom_menu_order');
//add_filter('menu_order', 'custom_menu_order');

// Remove category from edit/add new post screen
function my_list_terms_exclusions( $exclusions, $args ) {
  global $pagenow;
  if (in_array($pagenow,array('post.php','post-new.php'))) {
    $exclusions = " {$exclusions} AND t.slug NOT IN ('twitter')";
  }
  return $exclusions;
}
//add_filter('list_terms_exclusions', 'my_list_terms_exclusions', 10, 2);

// Remove admin bar
add_filter('show_admin_bar', '__return_false');


///////////////////
// Notifications
///////////////////

// Uncomment to remove WP Version update notifications
//add_action('admin_menu','remove_wp_update_notifications');

// Remove notifications for login plugins
function filter_plugin_updates( $value ) {
    unset( $value->response['akismet/akismet.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

// Avoid plugin deactivation
function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
    // Remove edit link for all
    if ( array_key_exists( 'edit', $actions ) )
        unset( $actions['edit'] );
    // Remove deactivate link for crucial plugins
    if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
        //'plugin_folder/plugin_main_script.php'
    )))
    
    unset( $actions['deactivate'] );
    return $actions;
}
//add_filter( 'plugin_action_links', 'disable_plugin_deactivation', 10, 4 );


/////////////////
// Theme Setup   
/////////////////

add_action( 'after_setup_theme', 'my_theme_setup' );

if ( ! function_exists( 'my_theme_setup' ) ):

function my_theme_setup() {
    
    // Tiny MCE styles
    add_editor_style();
    
    // This theme uses post thumbnails
    add_theme_support( 'post-thumbnails' );
    
    // Register extra featured images
    // http://wordpress.org/plugins/multiple-post-thumbnails/
    // https://github.com/voceconnect/multi-post-thumbnails
    if (class_exists('MultiPostThumbnails')) {
        
        $types = array('post', 'page', 'custom_pt');
        foreach($types as $type) {
            new MultiPostThumbnails(array(
                'label' => 'Secondary Image',
                'id' => 'secondary-image',
                'post_type' => $type
                )
            );
        }

        // Array with all registered IDs for compatibility with wp-gallery-utils.php
        global $exclude_thumb_ids;
        $exclude_thumb_ids = array(
                'post'      => array('secondary-image'),
                'custom_pt' => array('secondary-image-cpt')
            );

        // Language images
        // Translate post images for pages, eBook Services and Software Details
        if (function_exists('qtrans_getLanguage')) {
            
            global $q_config;
            
            $types = array('page', 'ebook-service', 'software');

            // Languages
            $langs =  qtrans_getSortedLanguages(); //array('en', 'es');
            foreach($langs as $lang) {

                // Use default thumbnail for default language (Deutsch)
                if ($lang == $q_config['default_language']) continue;

                // Post types
                foreach($types as $type) {
                    new MultiPostThumbnails(
                        array(
                            'label' => 'Beitragsbild (' . strtoupper($lang) . ')',
                            'id' => 'thumbnail-'.$lang,
                            'post_type' => $type
                        )
                    );

                    // Add exclude

                    // If key exists, add thumbnail id
                    if ($exclude_thumb_ids[$type]) {
                        array_push($exclude_thumb_ids[$type], 'thumbnail-'.$lang);

                    // If not defined, create array key
                    } else {
                        $exclude_thumb_ids[$type] = array('thumbnail-'.$lang);
                    }
                }
            }
        }
    }

    // Images size
    if ( function_exists( 'add_image_size' ) ) { 
        
        //set_post_thumbnail_size( 320, 200 );            // Retrieved as 'post-thumbnail'   
        //add_image_size( 'single-column', 320, 9999 );   // 320 pixels wide (and unlimited height)
        //add_image_size( 'primary-column', 660, 9999 );  // 660 pixels wide (and unlimited height)
    }
    
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


//////////////////
// Init Scripts   
//////////////////

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
    
    if ( $isMobile ) {
        
    } else {
    
    }
    
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
            'ajaxurl'     => (function_exists('qtrans_getLanguage')) ? 
                                admin_url('admin-ajax.php?lang=' . qtrans_getLanguage()) : 
                                admin_url( 'admin-ajax.php' ),
            'lang'        => get_language_code(),
            'siteStrings' => json_encode($siteStrings),
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

function my_admin_scripts_method() {

    //wp_register_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker', get_template_directory_uri().'/js/ui.datepicker.js', array('jquery','jquery-ui-core'));

    wp_enqueue_script('admin-js-functions',get_template_directory_uri().'/js/admin.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'));

    // Path to jQuery UI theme stylesheet
    wp_enqueue_style('jquery-ui','http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');

    wp_print_styles();
}
//add_action('admin_print_scripts', 'my_admin_scripts_method');


/////////////////////////////////
// HTML5 Reset initializations   
/////////////////////////////////
    
// Clean up the <head>
function remove_head_links() {
    // remove_action('wp_head', 'rsd_link');
    // remove_action('wp_head', 'wlwmanifest_link');
    remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
    remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
    //remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
    //remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
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