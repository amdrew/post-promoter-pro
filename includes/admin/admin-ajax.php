<?php

add_action( 'wp_ajax_ppp_bitly_connect', 'ppp_get_bitly_auth' );
function ppp_get_bitly_auth() {
	global $ppp_bitly_oauth;

	var_dump( $ppp_bitly_oauth->ppp_get_bitly_auth( $_POST['username'], $_POST['password'], $_POST['apikey'] ) );
	die(); // this is required to return a proper result
}