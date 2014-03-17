<?php
/**
 * Given a month, day, year and post ID, generate the timestamps and unique cron names
 */
function ppp_get_timestamps( $month, $day, $year, $post_id ) {
	global $ppp_options, $ppp_social_settings;
	$days_ahead = 1;
	$times = array();
	$offset = (int) -( get_option( 'gmt_offset' ) ); // Make the timestamp in the users' timezone, b/c that makes more sense

	$ppp_post_override = get_post_meta( $post_id, '_ppp_post_override', true );
	$ppp_post_override_data = get_post_meta( $post_id, '_ppp_post_override_data', true );
	$override_times = wp_list_pluck( $ppp_post_override_data, 'time' );

	$tweet_times = ( empty( $ppp_post_override ) && !empty( $override_times ) ) ? $ppp_options['times'] : $override_times;

	$times = array();
	foreach ( $tweet_times as $time ) {
		$share_time = explode( ':', $time );

		$hours   = (int)$share_time[0] + $offset;
		$minutes = (int)$share_time[1];

		$timestamp = mktime( $hours, $minutes, 0, $month, $day + $days_ahead, $year );
		$times[strtotime( date_i18n( 'd-m-Y H:i:s', $timestamp , true ) )] = 'sharedate_' . $days_ahead . '_' . $post_id;
		$days_ahead++;
	}

	return $times;
}

function ppp_schedule_share( $post_id, $post ) {
	if ( !isset( $_POST['post_status'] ) || $post->post_type != 'post' ) {
		return;
	}

	if ( ( $_POST['post_status'] == 'publish' && $_POST['original_post_status'] == 'publish' ) ||
	     ( $_POST['post_status'] == 'future' && $_POST['original_post_status'] == 'future' ) ) {
		// Be sure to clear any currently scheduled tweets so we aren't creating multiple instances
		// This will stop something from moving between draft and post and continuing to schedule tweets
		ppp_remove_scheduled_shares( $post_id );
	}

	if( ( $_POST['post_status'] == 'publish' && $_POST['original_post_status'] != 'publish' ) ||
		( $_POST['post_status'] == 'future' && $_POST['original_post_status'] == 'future' ) ) {
		global $ppp_options, $ppp_social_settings;

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

	$ppp_post_override = get_post_meta( $post_id, '_ppp_post_override', true );
	if ( !empty( $ppp_post_override ) ) {
		$ppp_post_override_data = get_post_meta( $post_id, '_ppp_post_override_data', true );
		$name_array = explode( '_', $name );
		$day = 'day' . $name_array[1];
		$tweet_text = $ppp_post_override_data[$day]['text'];
	}

	$tweet_text = isset( $tweet_text ) ? $tweet_text : $post->post_title;
	$tweet = $tweet_text . ' ' . get_permalink( $post_id );

	$status = $ppp_twitter_oauth->ppp_tweet( $tweet );

	if ( $ppp_options['enable_debug'] == '1' ) {
		update_post_meta( $post_id, '_ppp-' . $name . '-status', $status );
	}
}

function ppp_remove_scheduled_shares( $post_id ) {
	$days_ahead = 1;
	while ( $days_ahead <= 6 ) {
		$name = 'sharedate_' . $days_ahead . '_' . $post_id;
		wp_clear_scheduled_hook( 'ppp_share_post_event', array( $post_id, $name ) );

		$days_ahead++;
	}
}
