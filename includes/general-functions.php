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