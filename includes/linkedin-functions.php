<?php
/**
 * Return if linkedin account is found
 * @return bool If the Linkedin object exists
 */
function ppp_linkedin_enabled() {
	global $ppp_social_settings;

	if ( isset( $ppp_social_settings['linkedin'] ) && !empty( $ppp_social_settings['linkedin'] ) ) {
		return true;
	}

	return false;
}

function ppp_li_connect_display() {
	?>
	<div>
	<?php
	global $ppp_linkedin_oauth, $ppp_social_settings;
	if ( !ppp_linkedin_enabled() ) { ?>
		<?php $li_authurl = $ppp_linkedin_oauth->ppp_get_linkedin_auth_url( get_bloginfo( 'url' ) . $_SERVER['REQUEST_URI'] ); ?>
		<a href="<?php echo $li_authurl; ?>"><?php _e( 'Connect to Linkedin', 'ppp-txt' ); ?></a>
	<?php } else { ?>
		<div class="ppp-social-profile ppp-linkedin-profile">
			<div class="ppp-linkedin-info">
				<?php _e( 'Signed in as', 'ppp-txt' ); ?>: <?php echo $ppp_social_settings['linkedin']->firstName . ' ' . $ppp_social_settings['linkedin']->lastName; ?>
				<br />
				<?php echo $ppp_social_settings['linkedin']->headline; ?>
			</div>
		</div>
		<a class="button-primary" href="<?php echo admin_url( 'admin.php?page=ppp-social-settings&ppp_social_disconnect=true&ppp_network=linkedin' ); ?>" ><?php _e( 'Disconnect from Linkedin', 'ppp-txt' ); ?></a>&nbsp;
	</div>
	<?php }
}
add_action( 'ppp_connect_display-li', 'ppp_li_connect_display' );

function ppp_capture_linkedin_oauth() {
	if ( isset( $_REQUEST['li_access_token'] ) && ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'ppp-social-settings' ) ) {
		global $ppp_linkedin_oauth;
		$ppp_linkedin_oauth->ppp_initialize_linkedin();
		wp_redirect( admin_url( 'admin.php?page=ppp-social-settings' ) );
		die();
	}
}
add_action( 'admin_init', 'ppp_capture_linkedin_oauth', 10 );

function ppp_disconnect_linkedin() {
	global $ppp_social_settings;
	$ppp_social_settings = get_option( 'ppp_social_settings' );
	if ( isset( $ppp_social_settings['linkedin'] ) ) {
		unset( $ppp_social_settings['linkedin'] );
		update_option( 'ppp_social_settings', $ppp_social_settings );
		delete_option( '_ppp_li_linkedin_expires' );
	}
}
add_action( 'ppp_disconnect-linkedin', 'ppp_disconnect_linkedin', 10 );

function ppp_li_query_vars( $vars ) {
	$vars[] = 'li_access_token';
	$vars[] = 'expires_in';

	return $vars;
}
add_filter( 'query_vars', 'ppp_li_query_vars' );

/**
 * Refreshes the Linkedin Access Token
 * @return void
 */
function ppp_li_execute_refresh() {
	if ( !ppp_linkedin_enabled() ) {
		return;
	}

	$expiration_date = get_option( '_ppp_linkedin_refresh', true );

	if ( current_time( 'timestamp' ) > $expiration_date ) {
		add_action( 'admin_notices', 'ppp_linkedin_refresh_notice' );
	}
}
add_action( 'admin_init', 'ppp_li_execute_refresh' );

function ppp_linkedin_refresh_notice() {
	global $ppp_linkedin_oauth, $ppp_social_settings;

	// Look for the tokens coming back
	$ppp_linkedin_oauth->ppp_initialize_linkedin();
	$expiration_date = get_option( '_ppp_linkedin_refresh', true );

	$token = $ppp_social_settings['linkedin']->access_token;
	$url = $ppp_linkedin_oauth->ppp_get_linkedin_auth_url( admin_url( 'admin.php?page=ppp-social-settings' ) );
	$url = str_replace( '?ppp-social-auth', '?ppp-social-auth&ppp-refresh=true&access_token=' . $token, $url );

	$days_left = round( ( $ppp_social_settings['linkedin']->expires_on - current_time( 'timestamp' ) ) / DAY_IN_SECONDS );
	?>
	<div class="update-nag">
		<p><strong>Post Promoter Pro: </strong><?php printf( __( 'Your LinkedIn authentcation expires in within %d days. Please <a href="%s">refresh access.</a>.', 'ppp-txt' ), $days_left, $url ); ?></p>
	</div>
	<?php
}

function ppp_set_li_token_constants( $social_tokens ) {
	if ( !empty( $social_tokens ) && property_exists( $social_tokens, 'linkedin' ) ) {
		define( 'LINKEDIN_KEY', $social_tokens->linkedin->api_key );
		define( 'LINKEDIN_SECRET', $social_tokens->linkedin->secret_key );
	}
}
add_action( 'ppp_set_social_token_constants', 'ppp_set_li_token_constants', 10, 1 );

function ppp_li_share( $title, $description, $link, $media ) {
	global $ppp_linkedin_oauth;
	$args = array (
		'title' => $title,
		'description' => $description,
		'submitted-url' => $link,
		'submitted-image-url' => $media
		);
var_dump($args); exit;
	$ppp_linkedin_oauth->ppp_linkedin_share( $args );
}

function ppp_li_add_admin_tab( $tabs ) {
	$tabs['li'] = array( 'name' => __( 'LinkedIn', 'ppp-txt' ), 'class' => 'icon-ppp-li' );

	return $tabs;
}
add_filter( 'ppp_admin_tabs', 'ppp_li_add_admin_tab', 10, 1 );

function ppp_li_add_meta_tab( $tabs ) {
	global $ppp_social_settings;
	if ( ! ppp_linkedin_enabled() ) {
		return $tabs;
	}

	$tabs['li'] = array( 'name' => __( 'LinkedIn', 'ppp-txt' ), 'class' => 'icon-ppp-li' );

	return $tabs;
}
add_filter( 'ppp_metabox_tabs', 'ppp_li_add_meta_tab', 10, 1 );

function ppp_li_register_metabox_content( $content ) {
	global $ppp_social_settings;
	if ( ! ppp_linkedin_enabled() ) {
		return $content;
	}

	$content[] = 'li';

	return $content;
}
add_filter( 'ppp_metabox_content', 'ppp_li_register_metabox_content', 10, 1 );

function ppp_li_add_metabox_content( $post ) {
	global $ppp_options;
	$default_text = !empty( $ppp_options['default_text'] ) ? $ppp_options['default_text'] : __( 'Social Text', 'ppp-txt' );

	$ppp_li_share_on_publish = get_post_meta( $post->ID, '_ppp_li_share_on_publish', true );
	$ppp_share_on_publish_title = get_post_meta( $post->ID, '_ppp_li_share_on_publish_title', true );
	$ppp_share_on_publish_desc = get_post_meta( $post->ID, '_ppp_li_share_on_publish_desc', true );

	?>
	<p>
	<?php $disabled = ( $post->post_status === 'publish' && time() > strtotime( $post->post_date ) ) ? true : false; ?>
	<input <?php if ( $disabled ): ?>disabled<?php endif; ?> type="checkbox" name="_ppp_li_share_on_publish" id="ppp_li_share_on_publish" value="1" <?php checked( '1', $ppp_li_share_on_publish, true ); ?> />&nbsp;
		<label for="ppp_li_share_on_publish"><?php _e( 'Share this post on LinkedIn at the time of publishing?', 'ppp-txt' ); ?></label>
		<p class="ppp_share_on_publish_text" style="display: <?php echo ( $ppp_li_share_on_publish ) ? '' : 'none'; ?>">
				<span class="left" id="ppp-li-image">
					<?php echo get_the_post_thumbnail( $post->ID, 'ppp-li-share-image', array( 'class' => 'left' ) ); ?>
				</span>
				<?php _e( 'Link Title', 'ppp-txt' ); ?>:<br />
				<input
				<?php if ( $disabled ): ?>disabled readonly<?php endif; ?>
				onkeyup="PPPCountChar(this)"
				class="ppp-share-text"
				type="text"
				placeholder="<?php echo $default_text; ?>"
				name="_ppp_li_share_on_publish_title"
				<?php if ( isset( $ppp_share_on_publish_title ) ) {?>value="<?php echo htmlspecialchars( $ppp_share_on_publish_title ); ?>"<?php ;}?>
			/>
			<br />
			<?php _e( 'Link Description', 'ppp-txt' ); ?>:<br />
			<textarea name="_ppp_li_share_on_publish_desc"><?php echo $ppp_share_on_publish_desc; ?></textarea>
			<br /><?php _e( 'Note: If set, the Featured image will be attached to this share', 'ppp-txt' ); ?>
		</p>
	</p>
	<?php
}
add_action( 'ppp_generate_metabox_content-li', 'ppp_li_add_metabox_content', 10, 1 );

/**
 * Save the items in our meta boxes
 * @param  int $post_id The Post ID being saved
 * @param  object $post    The Post Object being saved
 * @return int          The Post ID
 */
function ppp_li_save_post_meta_boxes( $post_id, $post ) {
	global $ppp_options;

	if ( !isset( $ppp_options['post_types'] ) || !is_array( $ppp_options['post_types'] ) || !array_key_exists( $post->post_type, $ppp_options['post_types'] ) ) {
		return;
	}

	$ppp_li_share_on_publish = ( isset( $_REQUEST['_ppp_li_share_on_publish'] ) ) ? $_REQUEST['_ppp_li_share_on_publish'] : '0';
	$ppp_share_on_publish_title = ( isset( $_REQUEST['_ppp_li_share_on_publish_title'] ) ) ? $_REQUEST['_ppp_li_share_on_publish_title'] : '';
	$ppp_share_on_publish_desc = ( isset( $_REQUEST['_ppp_li_share_on_publish_desc'] ) ) ? $_REQUEST['_ppp_li_share_on_publish_desc'] : '';

	update_post_meta( $post->ID, '_ppp_li_share_on_publish', $ppp_li_share_on_publish );
	update_post_meta( $post->ID, '_ppp_li_share_on_publish_title', $ppp_share_on_publish_title );
	update_post_meta( $post->ID, '_ppp_li_share_on_publish_desc', $ppp_share_on_publish_desc );

	return $post->ID;
}
add_action( 'save_post', 'ppp_li_save_post_meta_boxes', 10, 2 ); // save the custom fields

function ppp_li_share_on_publish( $old_status, $new_status, $post ) {
	$from_meta = get_post_meta( $post->ID, '_ppp_li_share_on_publish', true );
	$from_post = isset( $_POST['_ppp_li_share_on_publish'] );

	if ( empty( $from_meta ) && empty( $from_post ) ) {
		return;
	}

	// Determine if we're seeing the share on publish in meta or $_POST
	if ( $from_meta && !$from_post ) {
		$ppp_share_on_publish_title = get_post_meta( $post->ID, '_ppp_li_share_on_publish_title', true );
		$ppp_share_on_publish_desc = get_post_meta( $post->ID, '_ppp_li_share_on_publish_desc', true );
	} else {
		$ppp_share_on_publish_title = isset( $_POST['_ppp_li_share_on_publish_title'] ) ? $_POST['_ppp_li_share_on_publish_title'] : '';
		$ppp_share_on_publish_desc = isset( $_POST['_ppp_li_share_on_publish_desc'] ) ? $_POST['_ppp_li_share_on_publish_desc'] : false;
	}

	$thumbnail = ppp_post_has_media( $post->ID, 'li', true );

	$name = 'sharedate_0_' . $post->ID . '_li';
	$share_link = ppp_generate_link( $post->ID, $name, true );

	$status['linkedin'] = ppp_li_share( $ppp_share_on_publish_title, $ppp_share_on_publish_desc, $share_link, $thumbnail );

	if ( isset( $ppp_options['enable_debug'] ) && $ppp_options['enable_debug'] == '1' ) {
		update_post_meta( $post->ID, '_ppp-' . $name . '-status', $status );
	}
}
add_action( 'ppp_share_on_publish', 'ppp_li_share_on_publish', 10, 3 );