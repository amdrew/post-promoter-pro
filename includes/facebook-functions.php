<?php
/**
 * Return if Facebook account is found
 * @return bool If the Twitter object exists
 */
function ppp_facebook_enabled() {
	global $ppp_social_settings;

	if ( isset( $ppp_social_settings['facebook'] ) && !empty( $ppp_social_settings['facebook'] ) ) {
		return true;
	}

	return false;
}

function ppp_fb_register_service( $services ) {
	$services[] = 'fb';

	return $services;
}
add_filter( 'ppp_register_social_service', 'ppp_fb_register_service', 10, 1 );

function ppp_fb_account_list_icon( $string ) {
	return '<span class="dashicons icon-ppp-fb"></span>';
}
add_filter( 'ppp_account_list_icon-fb', 'ppp_fb_account_list_icon', 10, 1 );

function ppp_fb_account_list_avatar( $string ) {

	if ( ppp_facebook_enabled() ) {
		global $ppp_social_settings;
		$avatar_url = $ppp_social_settings['facebook']->avatar;
		$string = '<img class="ppp-social-icon" src="' . $avatar_url . '" />';
	}

	return $string;
}
add_filter( 'ppp_account_list_avatar-fb', 'ppp_fb_account_list_avatar', 10, 1 );

function ppp_fb_account_list_name( $string ) {

	if ( ppp_facebook_enabled() ) {
		global $ppp_social_settings;
		$string  = $ppp_social_settings['facebook']->name;
	}

	return $string;
}
add_filter( 'ppp_account_list_name-fb', 'ppp_fb_account_list_name', 10, 1 );

function ppp_fb_account_list_actions( $string ) {

	if ( ! ppp_facebook_enabled() ) {
		global $ppp_facebook_oauth, $ppp_social_settings;
		$li_authurl = $ppp_facebook_oauth->ppp_get_facebook_auth_url( get_bloginfo( 'url' ) . $_SERVER['REQUEST_URI'] );

		$string = '<a class="button-primary" href="' . $li_authurl . '">' . __( 'Connect to Facebook', 'ppp-txt' ) . '</a>';
	} else {
		$string  = '<a class="button-primary" href="' . admin_url( 'admin.php?page=ppp-social-settings&ppp_social_disconnect=true&ppp_network=facebook' ) . '" >' . __( 'Disconnect from Facebook', 'ppp-txt' ) . '</a>&nbsp;';
	}

	return $string;
}
add_filter( 'ppp_account_list_actions-fb', 'ppp_fb_account_list_actions', 10, 1 );

/**
 * Sets the constants for the oAuth tokens for Twitter
 * @param  array $social_tokens The tokens stored in the transient
 * @return void
 */
function ppp_set_fb_token_constants( $social_tokens ) {
	if ( !empty( $social_tokens ) && property_exists( $social_tokens, 'facebook' ) ) {
		define( 'PPP_FB_APP_ID', $social_tokens->facebook->app_id );
		define( 'PPP_FB_APP_SECRET', $social_tokens->facebook->app_secret );
	}
}
add_action( 'ppp_set_social_token_constants', 'ppp_set_fb_token_constants', 10, 1 );

/**
 * Capture the oauth return from facebook
 * @return void
 */
function ppp_capture_facebook_oauth() {
	if ( isset( $_REQUEST['fb_access_token'] ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'ppp-social-settings' ) ) {
		global $ppp_facebook_oauth;
		$ppp_facebook_oauth->ppp_initialize_facebook();
		wp_redirect( admin_url( 'admin.php?page=ppp-social-settings' ) );
		die();
	}
}
add_action( 'admin_init', 'ppp_capture_facebook_oauth', 10 );

/**
 * Capture the disconnect request from Facebook
 * @return void
 */
function ppp_disconnect_facebook() {
	global $ppp_social_settings;
	$ppp_social_settings = get_option( 'ppp_social_settings' );
	if ( isset( $ppp_social_settings['facebook'] ) ) {
		unset( $ppp_social_settings['facebook'] );
		update_option( 'ppp_social_settings', $ppp_social_settings );
		delete_option( '_ppp_li_facebook_expires' );
	}
}
add_action( 'ppp_disconnect-facebook', 'ppp_disconnect_facebook', 10 );
