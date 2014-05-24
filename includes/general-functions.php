<?php

function ppp_link_tracking_enabled() {
	global $ppp_share_settings;
	$result = false;

	if ( isset( $ppp_share_settings['analytics'] ) && !empty( $ppp_share_settings['analytics'] ) ) {
		$result =  true;
	}

	return apply_filters( 'ppp_is_link_tracking_enabled', $result );
}

function ppp_get_post_slug_by_id( $post_id ) {
	$post_data = get_post( $post_id, ARRAY_A );
	$slug = $post_data['post_name'];

	return $slug;
}

/**
 * Return if twitter account is found
 * @return bool If the Twitter object exists
 */
function ppp_twitter_enabled() {
	global $ppp_social_settings;

	if ( isset( $ppp_social_settings['twitter'] ) && !empty( $ppp_social_settings['twitter'] ) ) {
		return true;
	}

	return false;
}

/**
 * Return if bitly account is found
 * @return bool If the Bitly object exists
 */
function ppp_bitly_enabled() {
	global $ppp_social_settings;

	if ( isset( $ppp_social_settings['bitly'] ) && !empty( $ppp_social_settings['bitly'] ) ) {
		return true;
	}

	return false;
}

/**
 * Get's the array of text replacements
 * @return array The array of text replacements, each with a token and description items
 */
function ppp_get_text_tokens() {
	return apply_filters( 'ppp_text_tokens', array() );
}

/**
 * Set the default array of tokens for replacement
 * @param  array $tokens The array of existing tokens
 * @return array         The array of tokens with the defaults added
 */
function ppp_set_default_text_tokens( $tokens ) {
	$tokens[] = array( 'token' => 'post_title', 'description' => __( 'The title of the post being shared', 'ppp-txt' ) );
	$tokens[] = array( 'token' => 'site_title', 'description' => __( 'The site title, from Settings > General' ) );

	return $tokens;
}
add_filter( 'ppp_text_tokens', 'ppp_set_default_text_tokens', 10, 1 );

/**
 * Iterate through all tokens we have registered and run the associated filter on them
 *
 * Devs can add a token to the array, and use ppp_replace_token-[token] as the filter to execute their replacements
 * @param  string $string The raw share text
 * @param  array  $args   Array of arguments, containing things like post_id
 * @return string         The raw string, with all tokens replaced
 */
function ppp_replace_text_tokens( $string, $args = array() ) {
	$tokens = wp_list_pluck( ppp_get_text_tokens(), 'token' );
	foreach ( $tokens as $key => $token ) {
		$string = apply_filters( 'ppp_replace_token-' . $token, $string, $args );
	}

	return $string;
}
add_filter( 'ppp_share_content', 'ppp_replace_text_tokens', 10, 2 );

/**
 * Replace the Post Title token with the post title
 * @param  string $string The string to search
 * @param  array $args    Array of arguements, like post_id
 * @return string         The string with the token {post_title} replaced
 */
function ppp_post_title_token( $string, $args ) {
	if ( !isset( $args['post_id'] ) ) {
		return $string;
	}

	return preg_replace( '"\{post_title\}"', get_the_title( $args['post_id'] ), $string );
}
add_filter( 'ppp_replace_token-post_title', 'ppp_post_title_token', 10, 2 );

/**
 * Replace the Site Title token with the site title
 * @param  string $string The string to search
 * @param  array $args    Array of arguements, like post_id
 * @return string         The string with the token {site_title} replaced
 */
function ppp_site_title_token( $string, $args ) {
	return preg_replace( '"\{site_title\}"', get_bloginfo(), $string );
}
add_filter( 'ppp_replace_token-site_title', 'ppp_site_title_token', 10, 2 );