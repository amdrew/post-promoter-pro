<?php
/**
 * Returns if a link tracking method is enabled
 * @return boolean True if a form of link tracking is enabled, false if not
 */
function ppp_link_tracking_enabled() {
	global $ppp_share_settings;
	$result = false;

	if ( isset( $ppp_share_settings['analytics'] ) && !empty( $ppp_share_settings['analytics'] ) ) {
		$result =  true;
	}

	return apply_filters( 'ppp_is_link_tracking_enabled', $result );
}

/**
 * Get a post slug via the ID
 * @param  int $post_id The post ID
 * @return string       The slug of the post
 */
function ppp_get_post_slug_by_id( $post_id ) {
	$post_data = get_post( $post_id, ARRAY_A );
	$slug = $post_data['post_name'];

	return $slug;
}

/**
 * Get's the array of text replacements
 * @return array The array of text replacements, each with a token and description items
 */
function ppp_get_text_tokens() {
	return apply_filters( 'ppp_text_tokens', array() );
}

/**
 * Returns the number of says to setup shares for
 * @return  int The number of days
 */
function ppp_share_days_count() {
	return apply_filters( 'ppp_share_days_count', 6 );
}

/**
 * Returns if the shortener option is chosen
 * @return boolean	True/False if the shortener has been selected
 */
function ppp_is_shortener_enabled() {
	global $ppp_share_settings;

	return ( isset( $ppp_share_settings['shortener'] ) && !empty( $ppp_share_settings['shortener'] ) && $ppp_share_settings != '-1' );
}

/**
 * Strips slashes and html_entities_decode for sending to the networks.
 */
function ppp_entities_and_slashes( $string ) {
	return stripslashes( html_entity_decode( $string ) );
}

function ppp_add_image_sizes() {
	add_image_size( 'ppp-tw-share-image', 528, 222, true );
	add_image_size( 'ppp-li-share-image', 180, 110, true );

	do_action( 'ppp_add_image_sizes' );
}