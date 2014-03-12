<?php
function ppp_register_meta_boxes() {
	add_meta_box( 'ppp_tweet_schedule_metabox', 'Post Promoter Pro', 'ppp_tweet_schedule_callback', 'post', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'ppp_register_meta_boxes', 12 );

function ppp_tweet_schedule_callback() {
	global $post;
	$ppp_post_override = get_post_meta( $post->ID, '_ppp_post_override', true );
	var_dump( $ppp_post_override );
}

function ppp_save_post_meta_boxes( $post_id, $post ) {
	if ( $post->post_type != 'post' )
		return $post->ID;

	// Do validation and saving of items in custom metabox here

	return $post->ID;
}
add_action( 'save_post', 'ppp_save_post_meta_boxes', 1, 2 ); // save the custom fields