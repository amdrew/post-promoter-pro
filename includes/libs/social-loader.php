<?php
global $ppp_twitter_oauth;
ppp_set_social_tokens();

require_once( PPP_PATH . '/includes/libs/twitter.php');
$ppp_twitter_oauth = new PPP_Twitter();