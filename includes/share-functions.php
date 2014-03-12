<?php
function ppp_get_timestamps( $month, $day, $year, $post_id ) {
	global $ppp_options, $ppp_social_settings;
	$days_ahead = 1;
	$times = array();
	$offset = (int) -( get_option( 'gmt_offset' ) ); // Make the timestamp in the users' timezone, b/c that makes more sense

	foreach ( $ppp_options['times'] as $time ) {
		$share_time = explode( ':', $time );

		$hours   = (int)$share_time[0] + $offset;
		$minutes = (int)$share_time[1];
		
		$timestamp = mktime( $hours, $minutes, 0, $month, $day + $days_ahead, $year );
		$times[strtotime( date_i18n( 'd-m-Y H:i:s', $timestamp , true ) )] = 'share_date_' . $days_ahead . '_' . $post_id;
		$days_ahead++;
	}
	
	return $times;
}

function ppp_schedule_share() {
	if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
		global $ppp_options, $ppp_social_settings;
		$post_id = $_POST['post_ID'];

		$timestamps = ppp_get_timestamps( $_POST['mm'], $_POST['jj'], $_POST['aa'], $post_id );
		foreach ( $timestamps as $timestamp => $name ) {
			wp_schedule_single_event( $timestamp, 'ppp_share_post_event', array( $post_id, $name ) );
		}
	}
}
add_action( 'ppp_share_post_event', 'ppp_share_post', 10, 2 );

function ppp_share_post( $post_id, $name ) {
	global $ppp_options, $ppp_social_settings, $ppp_twitter_oauth;
	$post = get_post( $post_id, OBJECT );

	$tweet = $post->post_title . ' ' . get_permalink( $post_id ) . ' via @' . $ppp_social_settings['twitter']['user']->screen_name;

	$status = $ppp_twitter_oauth->ppp_tweet( $tweet );

	if ( $ppp_options['enable_debug'] == '1' ) {
		update_post_meta( $post_id, '_ppp-' . $name . '-status', $status );
	}
}

function ppp_remove_scheduled_shares( $post_id ) {
	$days_ahead = 1;
	while ( $days_ahead <= 6 ) {
		$name = 'share_date_' . $days_ahead . '_' . $post_id;
		wp_clear_scheduled_hook( 'ppp_share_post_event', array( (string)$post_id, $name ) );

		$days_ahead++;
	}
}