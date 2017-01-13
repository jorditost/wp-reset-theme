<?php

/**
 * Wordpress Admin Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    1.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */


/////////////////
// Custom Menu
/////////////////

// Edit admin menu items
// http://wp.tutsplus.com/tutorials/creative-coding/customizing-your-wordpress-admin/
// http://code.tutsplus.com/articles/customizing-your-wordpress-admin--wp-24941

function change_post_menu_label() {
    global $menu;
    global $submenu;

    foreach($menu as $key=>$menu_item) {

        if (empty($menu_item[0])) continue;

        // Ratings
        if ($menu_item[0] == 'Posts') {
            $menu[$key][0] = 'Blog';

        // Ratings
        } /*else if ($menu_item[0] == 'kk Star Ratings') {
            $menu[$key][0] = 'Ratings';

        // Maps
        } else if ($menu_item[0] == 'MapPress') {
            $menu[$key][0] = 'Maps';

        // Register
        } else if ($menu_item[0] == 'Pie Register') {
            $menu[$key][0] = 'Registration';

            // Change colors
            $menu[$key][4] = 'menu-top toplevel_page_register';
            $menu[$key][5] = 'toplevel_page_register';
        }*/
    }

    // Remove menu items
    remove_menu_page('edit-comments.php');
    remove_menu_page('link-manager.php');

    echo '';
}
add_action( 'admin_menu', 'change_post_menu_label' );

// Remove admin menus
// http://wp.tutsplus.com/tutorials/creative-coding/customizing-your-wordpress-admin/
function remove_admin_menus() {
    // Remove menus
    //remove_menu_page('edit.php');         // Remove Posts
    remove_menu_page('edit-comments.php');  // Remove Comments
    //remove_menu_page('link-manager.php'); // Remove Links
    //remove_menu_page('tools.php');
}
add_action( 'admin_menu', 'remove_admin_menus' );

// Custom menu order
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;

    return array(
        'index.php',                        // Dashboard
        'separator1',                       // First separator

        'edit.php?post_type=page',          // Pages
        'edit.php',                         // Posts
        //'edit.php?post_type=cpt_name',      // Custom Post Type

        'upload.php',                       // Media
        //'link-manager.php',               // Links
        //'edit-comments.php',              // Comments

        'separator2',                       // Second separator

        'themes.php',                       // Appearance
        'plugins.php',                      // Plugins
        'users.php',                        // Users
        'tools.php',                        // Tools
        'options-general.php',              // Settings
        'edit.php?post_type=acf',           // ACF

        'separator-last', // Last separator

        // From here are placed other plugin menus
    );
}
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
add_filter('menu_order', 'custom_menu_order');


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
// add_filter('show_admin_bar', '__return_false');

///////////////////////
// TinyMCE Functions
///////////////////////

// Change TinyMCE height
function wptiny($initArray){
    $initArray['height'] = '300px';
    return $initArray;
}
add_filter('tiny_mce_before_init', 'wptiny');

// Action that adds TinyMCE Editor on Post Excerpt
/*function tinymce_excerpt_js(){ ?>
<script type="text/javascript">
        jQuery(document).ready( tinymce_excerpt );
            function tinymce_excerpt() {
                jQuery("#excerpt").addClass("mceEditor");
                tinyMCE.execCommand("mceAddControl", false, "excerpt");
            }
</script>
<?php }
add_action( 'admin_head-post.php', 'tinymce_excerpt_js');
add_action( 'admin_head-post-new.php', 'tinymce_excerpt_js');*/

function tinymce_css(){ ?>
<style type='text/css'>
            #postexcerpt .inside{margin:0;padding:0;background:#fff;}
            #postexcerpt .inside p{padding:0px 0px 5px 10px;}
            #postexcerpt #excerpteditorcontainer { border-style: solid; padding: 0; }
</style>
<?php }
add_action( 'admin_head-post.php', 'tinymce_css');
add_action( 'admin_head-post-new.php', 'tinymce_css');
?>
