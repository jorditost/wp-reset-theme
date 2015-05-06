<?php 

/**
 * Wordpress Gallery Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.3
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 */

// Function to use with the LazyLoad plugin
// http://www.appelsiini.net/projects/lazyload
function the_lazy_post_thumbnail($size) {

	global $post;
	$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);

	if (!empty($thumb)) {
		$url    = $thumb['0'];
		$width  = $thumb['1'];
		$height = $thumb['2'];

		echo '<img class="lazy" data-original="'.$url.'" width="'.$width.'" height="'.$height.'">';
		echo '<noscript><img src="'.$url.'" width="'.$width.'" height="'.$height.'"></noscript>';
	}
}

function the_post_gallery_custom( $size = 'large', $show_videos = false, $tag = 'li', $exclude_thumb = true, $more_attr = '', $max_images = -1 ) {

	echo get_post_gallery_custom( $size, $show_videos, $tag, $exclude_thumb, $more_attr, $max_images );
}

function get_post_gallery_custom( $size = 'large', $show_videos = false, $tag = 'li', $exclude_thumb = true, $more_attr = '', $max_images = -1 ) {
	
	global $post;
	return get_post_gallery_by_ID( $post->ID, $size, $show_videos, $tag, $exclude_thumb, $more_attr, $max_images );
}

function has_post_gallery_custom($exclude_thumb = true) {
	global $post;

	$post_id = $post->ID;

	// Exclude thumb
	$exclude_array = array();

	if ( $exclude_thumb && has_post_thumbnail($post_id) ) $exclude_array[] = get_post_thumbnail_id( $post_id );

	// Exclude 'MultiPostThumbnails' thumbs
	if (class_exists('MultiPostThumbnails')) {

		global $exclude_thumb_ids;

		// Get thumb IDs defined for the current post type
		$post_type_thumb_ids = $exclude_thumb_ids[$post->post_type];

		foreach((array) $post_type_thumb_ids as $thumb_id) {

			$has_thumb = MultiPostThumbnails::has_post_thumbnail($post->post_type, $thumb_id, $post_id);
			if ($has_thumb) {
				$img_id = MultiPostThumbnails::get_post_thumbnail_id($post->post_type, $thumb_id, $post_id);
				$exclude_array[] = $img_id;
			}
		}
		unset($thumb_id);
	}
	
	// Exclude thumbnails as string separated by commas
	$exclude = implode (",", $exclude_array);

	// Get Images
	$images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent='.$post_id.'&exclude='.$exclude.'&numberposts='.$max_images.'&order=ASC&orderby=menu_order');

	return (is_array($images) && !empty($images));
}

function get_post_gallery_by_ID($post_id, $size = 'large', $show_videos = false, $tag = 'li', $exclude_thumb = true, $more_attr = '', $max_images = -1 ) {
	global $post;
	$result = '';

	// IMAGES
	
	// Exclude thumb
	$exclude_array = array();

	if ( $exclude_thumb && has_post_thumbnail($post_id) ) $exclude_array[] = get_post_thumbnail_id( $post_id );

	// Exclude 'MultiPostThumbnails' thumbs
	if (class_exists('MultiPostThumbnails')) {

		global $exclude_thumb_ids;

		// Get thumb IDs defined for the current post type
		$post_type_thumb_ids = $exclude_thumb_ids[$post->post_type];

		foreach((array) $post_type_thumb_ids as $thumb_id) {

			$has_thumb = MultiPostThumbnails::has_post_thumbnail($post->post_type, $thumb_id, $post_id);
			if ($has_thumb) {
				$img_id = MultiPostThumbnails::get_post_thumbnail_id($post->post_type, $thumb_id, $post_id);
				$exclude_array[] = $img_id;
			}
		}
		unset($thumb_id);
	}
	
	// Exclude thumbnails as string separated by commas
	$exclude = implode (",", $exclude_array);

	// Get Images
	$images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent='.$post_id.'&exclude='.$exclude.'&numberposts='.$max_images.'&order=ASC&orderby=menu_order');
	
	// Sort by menu order
	$arrKeys = sort_gallery_by_menu_order( $images );
	
	// More attributes
	$more_attr = ( !empty($more_attr) ) ? ' '.$more_attr : '';
	
	// Show ordered Images
	if (is_array( $arrKeys )) {
	
		foreach ( (array) $arrKeys as $attachment_id ) {			
			//$img_url   = wp_get_attachment_url($attachment_id); // Full size
			$thumb_url = wp_get_attachment_image_src($attachment_id, $size);
			
			$img_url = $thumb_url[0];
			
			$img_element = '<img src="'.$img_url.'" />';
			
			// If exists, wrap inside tags
			$result .= ( !empty($tag) ) ? '<'.$tag.' class="image"'.$more_attr.'>' . $img_element . '</'.$tag.'>' : $img_element;
		}
		unset($attachment_id);
	}
	
	//  VIDEOS	
	if ($show_videos) {
		
		$videos =& get_children( 'post_type=attachment&post_mime_type=video/mp4&post_parent='.$post_id );
				
		foreach ( (array) $videos as $attachment_id => $attachment ) {
			
			$video_url = wp_get_attachment_url($attachment_id);	// Full size						
			
			$video_element = '<video width="466" height="300" src="'.$video_url.'" type="video/mp4">Your browser has no HTML5 support</video><a class="play"><span></span></a>';
			
			// If exists, wrap inside tags
			$result .= ( !empty($tag) ) ? '<'.$tag.' class="video"'.$more_attr.'>' . $video_element . '</'.$tag.'>' : $video_element;	
		}
	}
		
	return $result;	
}


function get_post_img_gallery_src_array( $post_id, $exclude_thumb = true ) {
	
	if ( $post_id == null) return null;
	
	// Exclude thumb
	$thumb = '';
	if ( $exclude_thumb && has_post_thumbnail($post_id) ) $thumb = get_post_thumbnail_id( $post_id );
	
	// Get Images
	$images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent='.$post_id.'&exclude='.$thumb);
	
	// Sort by menu order
	$arrKeys = sort_gallery_by_menu_order( $images );
	
	$src_array = "";
	
	// Get ordered images source
	if (is_array( $arrKeys )) {
		$src_array = array();
		foreach ( (array) $arrKeys as $attachment_id ) {
			$thumb_url = wp_get_attachment_image_src($attachment_id, 'full'); // Full size
			$src_array[] = $thumb_url[0];
		}
		unset($attachment_id);
	}
	
	return $src_array;
}

function sort_gallery_by_menu_order( $gallery_array ) {
	
	// Get array keys representing attached image numbers
	$arrKeys = array_keys($gallery_array);

	/******BEGIN BUBBLE SORT BY MENU ORDER*************/
	
	// Put all image objects into new array with standard numeric keys (new array only needed while we sort the keys)
	foreach((array) $gallery_array as $oImage) {
		$arrNewImages[] = $oImage;
	}
	unset($oImage);

	// Bubble sort image object array by menu_order TODO: Turn this into std "sort-by" function in functions.php
	for($i = 0; $i < sizeof($arrNewImages) - 1; $i++) {
		for($j = 0; $j < sizeof($arrNewImages) - 1; $j++) {
			if((int)$arrNewImages[$j]->menu_order > (int)$arrNewImages[$j + 1]->menu_order) {
				$oTemp = $arrNewImages[$j];
				$arrNewImages[$j] = $arrNewImages[$j + 1];
				$arrNewImages[$j + 1] = $oTemp;
			}
		}
	}

	// Reset arrKeys array
	$arrKeys = array();

	// Replace arrKeys with newly sorted object ids
	foreach((array) $arrNewImages as $oNewImage) {
		$arrKeys[] = $oNewImage->ID;
	}
	unset($oNewImage);
	
	/*******END BUBBLE SORT BY MENU ORDER**************/

	return $arrKeys;
}

/* Remove hard coded dimensions from post thumbnail */

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );

function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
?>