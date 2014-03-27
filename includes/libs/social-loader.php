<?php
global $ppp_twitter_oauth;
require_once( PPP_PATH . '/includes/libs/twitter.php');
$ppp_twitter_oauth = new PPP_Twitter();

global $ppp_facebook_oauth;
require_once( 'facebook/facebook.php' );

$config = array(
	'appId' => '652453918135601',
	'secret' => '3760af96dd3498761c307d0023bc446c',
	'fileUpload' => false, // optional
	'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
);

$ppp_facebook_oauth = new Facebook($config);