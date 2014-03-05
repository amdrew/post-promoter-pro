<?php
function test_setting_times() {
	global $ppp_options, $ppp_social_settings;
	$days_ahead = 1;
	foreach ( $ppp_options['times'] as $time ) {
		$share_time = explode( ':', $time );
		$hours = (int)$share_time[0];
		$minutes = (int)$share_time[1];
		echo date("Y-m-d H:i:s", mktime($hours, $minutes, 0, date('n'), date('j') + $days_ahead, date('Y'))) . '<br />';
		$days_ahead++;
	}
	

}

function ppp_schedule_share() {
	if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
		global $ppp_options, $ppp_social_settings;

	}
}

function ppp_share_post( $post_id ) {

}