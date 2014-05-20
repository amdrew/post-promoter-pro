<?php

add_action( 'wp_ajax_ppp_bitly_connect', 'ppp_get_bitly_auth' );
function ppp_get_bitly_auth() {
	$whatever = intval( $_POST['whatever'] );

	$whatever += 10;

        echo $whatever;

	die(); // this is required to return a proper result
}