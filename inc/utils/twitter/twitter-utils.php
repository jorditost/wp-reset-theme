<?php 
/**
 * Twitter Functions Utils
 *
 * @copyright  Copyright © 2011-2012 Jordi Tost
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    2.0
 *
 * @Developer Jordi Tost (Follow Me: @jorditost)
 *
 * 18/06/2013 - Integration of Twitter API 1.1 with the tmhOAuth lib
 *				https://github.com/themattharris/tmhOAuth
 */

require 'tmhOAuth/tmhOAuth.php';

/*	
	// $twitter_request
	array(
		'screen_name' => $screen_name,
		'count' 	  => $tweet_count
	)

	// $oauth_params format
	array(
		'consumer_key'    => 'vCXf51354Kw1yN2Q2bW5g',
		'consumer_secret' => 'bBI8ngzcL7Agfjs50cNdiqDi4UrPmlxsZZQHQAHVHY',
		'user_token'      => '111026634-sGLJImmx9Ht4e0OuuEglQ6yJnVQXrEBzLZdwhQaj',
		'user_secret'     => 'VFcsI6uHe4jBWkgvM3zqdSOziCr7KSUAv5CLE2ro',
	)
*/

function get_twitter_timeline($twitter_request, $oauth_params) {
	// TO DO
}

function the_last_tweet($screen_name, $oauth_params) {
	echo get_last_tweet($screen_name, $oauth_params);
}

function get_last_tweet($screen_name, $oauth_params) {

	$cache_file = TEMPLATEPATH . '/inc/twitter/cache/twitter_cache';
	$mtime = time() - filemtime($cache_file);
   
    // Start with the cache

    // Cache doesn't exist or is too old
	if(!file_exists($cache_file) || (file_exists($cache_file) && $mtime > 86400)) {

		$tmhOAuth = new tmhOAuth($oauth_params);
		
		$code = $tmhOAuth->request('GET', 
		                            $tmhOAuth->url('1.1/statuses/user_timeline'), 
		                            array(
		                            	'screen_name' => $screen_name,
		                            	'count' 	  => 1
		                            ));
		
		$response = $tmhOAuth->response;

		if (!$response || !is_array($response) || empty($response['response']))
			return "";

		// Decode JSON Response
		$tweet = json_decode($response['response']);

		// Open new Cache fiel
		$cache_static = fopen($cache_file, 'wb');

		// Parse Tweet
		$ltweet = $tweet[0];
		$ltt = format_text_links($ltweet->text);
		$ltpd = substr($ltweet->created_at, 0, 16);
					
		$inhtml = '<span class="tweet-text">'. $ltt .'</span><span class="tweet-time">' . $ltpd . '</span>';
		
		fwrite($cache_static, $inhtml);

		fclose($cache_static);

	// Read cache
	} else {
		$inhtml = file_get_contents($cache_file);
	}

    // End of caching
	return $inhtml;
}
?>