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

    ////////////////////////////
    // Menu & Title Functions
    ////////////////////////////

    // Hack for Menu creation feature with qTranslate active
    // Functions based on the 'Hack qTranslate Menu' plugin by Michele Menciassi
    // Author URI: http://www.medita.com/

    function qtmh_setup_nav_menu_item( $menu_item ) {
        if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $menu_item->title = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage( $menu_item->title );
            $menu_item->title = qtrans_useTermLib( $menu_item->title );
            return $menu_item;
        }
    }
    add_filter('wp_setup_nav_menu_item', 'qtmh_setup_nav_menu_item', 0);

    function qtmh_update_nav_menu_item ($menu_id, $menu_item_db_id, $args ) {
        if ($args['menu-item-type'] == 'post_type') {
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


    //////////////////////
    // Language Chooser
    //////////////////////

    // This function acts like the 'qtrans_generateLanguageSelectCode' function from qTranslate but using language codes (ie: EN), not language names (ie: English)
    // http://wordpress.stackexchange.com/questions/71350/adding-a-filter-to-qtranslate-to-change-display-of-language-chooser
    function qtrans_SelectCode( $style = 'text', $id = '' ) {
        global $q_config;
        if( $style == '' )
            $style = 'text';
        if( is_bool( $style ) && $style )
            $style = 'image';
        if( is_404() )
            $url = get_option( 'home' );
        else
            $url = '';
        if( $id == '' )
            $id = 'qtranslate';

        $id .= '-chooser';
        switch( $style ) {
            case 'image':
            case 'text':
            case 'dropdown':
                echo '<ul class="qtrans_language_chooser" id="' . $id . '">';
                foreach( qtrans_getSortedLanguages() as $language ) {
                    $classes = array( 'lang-' . $language );
                    if( $language == $q_config['language'] )
                        $classes[] = 'active';

                    $language_text = $language;
                    //$language_text = ($style == 'code') ? $language : $q_config['language_name'][$language];

                    echo '<li class="' . implode( ' ', $classes ) . '"><a href="' . qtrans_convertURL( $url, $language ) . '"';
                    // set hreflang
                    echo ' hreflang="' . $language . '" title="' . $q_config['language_name'][$language] . '"';
                    if( $style == 'image' )
                        echo ' class="qtrans_flag qtrans_flag_' . $language . '"';
                    echo '><span';
                    if( $style == 'image' )
                        echo ' style="display:none"';
                    echo '>' . $language_text . '</span></a></li>';
                }
                echo "</ul><div class=\"qtrans_widget_end\"></div>";

                if( $style == 'dropdown' ) {
                    echo "<script type=\"text/javascript\">\n// <![CDATA[\r\n";
                    echo "var lc = document.getElementById('" . $id . "');\n";
                    echo "var s = document.createElement('select');\n";
                    echo "s.id = 'qtrans_select_" . $id . "';\n";
                    echo "lc.parentNode.insertBefore(s,lc);";
                    // create dropdown fields for each language
                    foreach( qtrans_getSortedLanguages() as $language ) {
                        echo qtrans_insertDropDownElementCode( $language, qtrans_convertURL( $url, $language ), $id );
                    }
                    // hide html language chooser text
                    echo "s.onchange = function() { document.location.href = this.value;}\n";
                    echo "lc.style.display='none';\n";
                    echo "// ]]>\n</script>\n";
                }
                break;
            case 'both':
                echo '<ul class="qtrans_language_chooser" id="' . $id . '">';
                foreach( qtrans_getSortedLanguages() as $language ) {
                    $language_text = $language;
                    echo '<li';
                    if( $language == $q_config['language'] )
                        echo ' class="active"';
                    echo '><a href="' . qtrans_convertURL( $url, $language ) . '"';
                    echo ' class="qtrans_flag_' . $language . ' qtrans_flag_and_text" title="' . $q_config['language_name'][$language] . '"';
                    echo '><span>' . $language_text . '</span></a></li>';
                }
                echo "</ul><div class=\"qtrans_widget_end\"></div>";
                break;
        }
    }

    // This function acts like the 'qtrans_insertDropDownElement' function from qTranslate but using language codes (ie: EN), not language names (ie: English)
    function qtrans_insertDropDownElementCode($language, $url, $id){
        global $q_config;
        $html ="
            var sb = document.getElementById('qtrans_select_".$id."');
            var o = document.createElement('option');
            var l = document.createTextNode('" . $language . "');
            ";
        if($q_config['language']==$language)
            $html .= "o.selected = 'selected';";
        $html .= "
            o.value = '".addslashes(htmlspecialchars_decode($url, ENT_NOQUOTES))."';
            o.appendChild(l);
            sb.appendChild(o);
            ";
        return $html;    
    }
}
?>