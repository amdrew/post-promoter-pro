<?php
/**
 * Display the General settings tab
 * @return void
 */
function ppp_admin_page() {
	global $ppp_options;
	?>
	<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Post Promoter Pro', 'ppp-txt' ); ?></h2>
	<div class="wrap">
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'ppp-options' ); ?>
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e( 'Default Share Times', 'ppp-txt' ); ?><br /><span style="font-size: x-small;"><?php _e( 'When would you like your posts to be shared? You can changes this on a per post basis as well', 'ppp-txt' ); ?></span></th>
					<td>
						<strong>Days After Publish</strong>
						<table id="ppp-days-table">
							<tr>
								<td><label for="ppp_options[times][day1]">1</label></td>
								<td><label for="ppp_options[times][day2]">2</label></td>
								<td><label for="ppp_options[times][day3]">3</label></td>
								<td><label for="ppp_options[times][day4]">4</label></td>
								<td><label for="ppp_options[times][day5]">5</label></td>
								<td><label for="ppp_options[times][day6]">6</label></td>
							</tr>
							<tr>
								<td><input id="day1" type="text" name="ppp_options[times][day1]" class="share-time-selector" <?php if ( $ppp_options['times']['day1'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day1'] ); ?>"<?php ;}?> size="8" /></td>
								<td><input id="day2" type="text" name="ppp_options[times][day2]" class="share-time-selector" <?php if ( $ppp_options['times']['day2'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day2'] ); ?>"<?php ;}?> size="8" /></td>
								<td><input id="day3" type="text" name="ppp_options[times][day3]" class="share-time-selector" <?php if ( $ppp_options['times']['day3'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day3'] ); ?>"<?php ;}?> size="8" /></td>
								<td><input id="day4" type="text" name="ppp_options[times][day4]" class="share-time-selector" <?php if ( $ppp_options['times']['day4'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day4'] ); ?>"<?php ;}?> size="8" /></td>
								<td><input id="day5" type="text" name="ppp_options[times][day5]" class="share-time-selector" <?php if ( $ppp_options['times']['day5'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day5'] ); ?>"<?php ;}?> size="8" /></td>
								<td><input id="day6" type="text" name="ppp_options[times][day6]" class="share-time-selector" <?php if ( $ppp_options['times']['day6'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_options['times']['day6'] ); ?>"<?php ;}?> size="8" /></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Post Types', 'ppp-txt' ); ?><br /><span style="font-size: x-small;"><?php _e( 'What post types do you want to schedule for?', 'ppp-txt' ); ?></span></th>
					<td>
						<?php $post_types = get_post_types( array( 'public' => true, 'publicly_queryable' => true ), NULL, 'and' ); ?>
						<?php if ( array_key_exists( 'attachment', $post_types ) ) { unset( $post_types['attachment'] ); } ?>
						<?php foreach ( $post_types as $post_type => $type_data ): ?>
							<input type="checkbox" name="ppp_options[post_types][<?php echo $post_type; ?>]" value="1" id="<?php echo $post_type; ?>" <?php checked( isset( $ppp_options['post_types'][$post_type] ), true, true ); ?> />&nbsp;<label for="<?php echo $post_type; ?>"><?php echo $type_data->labels->name; ?></label></br />
						<?php endforeach; ?>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Advanced', 'ppp-txt' ); ?><br /><span style="font-size: x-small;"><?php _e( 'Tools for troubleshooting and advanced usage', 'ppp-txt' ); ?></span></th>
					<td>
						<input type="checkbox" name="ppp_options[enable_debug]" "<?php checked( true, isset( $ppp_options['enable_debug'] ), true ); ?>" value="1" /> Enable Debug
					</td>
				</tr>

				<input type="hidden" name="action" value="update" />
				<?php $page_options = apply_filters( 'ppp_settings_page_options', array( 'ppp_options' ) ); ?>
				<input type="hidden" name="page_options" value="<?php echo implode( ',', $page_options ); ?>" />
				<?php settings_fields( 'ppp-options' ); ?>
			</table>
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'ppp-txt' ) ?>" />
		</form>
	</div>
	<?php
}


/**
* Display the Social tab
* @return void
*/
function ppp_display_social() {
	global $ppp_social_settings, $ppp_twitter_oauth;
	?>
	<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Post Promoter Pro', 'ppp-txt' ); ?></h2>
		<div class="wrap">
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'ppp-social-settings' ); ?>
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php _e( 'Twitter', 'ppp-txt' ); ?><br /><span style="font-size: x-small;"><?php _e( '<a href="https://twitter.com/settings/applications" target="blank">Remove Access</a>', 'ppp-txt' ); ?></span></th>
					<td>
						<?php
						$results = $ppp_twitter_oauth->ppp_initialize_twitter();
						if ( is_array( $results ) ) {
							$ppp_social_settings['twitter'] = $results;
							update_option( 'ppp_social_settings', $ppp_social_settings );
						}
						?>
						<?php if ( !isset( $ppp_social_settings['twitter']['user'] ) ) { ?>
						<?php $tw_authurl = $ppp_twitter_oauth->ppp_get_twitter_auth_url(); ?>
						<a href="<?php echo $tw_authurl; ?>" class="button-primary">Sign in with Twitter</a>	
						<?php } else { ?>
							<img src="<?php echo $ppp_social_settings['twitter']['user']->profile_image_url_https; ?>" /> Signed in as <?php echo $ppp_social_settings['twitter']['user']->name; ?> 
						<?php } ?>
					</td>
				</tr>

				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="ppp_social_settings" />

				<?php settings_fields( 'ppp-social-settings' ); ?>
			</table>
			<!-- <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'ppp-txt' ) ?>" /> -->
		</form>
	</div>
	<?php
}

/**
 * Display the System Info Tab
 * @return void
 */
function ppp_display_sysinfo() {
	global $wpdb;
	global $ppp_options;
	?>
	<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Post Promoter Pro', 'ppp-txt' ); ?></h2>
		<div class="wrap">
		<textarea style="font-family: Menlo, Monaco, monospace; white-space: pre" onclick="this.focus();this.select()" readonly cols="150" rows="35">
	SITE_URL:                 <?php echo site_url() . "\n"; ?>
	HOME_URL:                 <?php echo home_url() . "\n"; ?>

	PPP Version:             <?php echo PPP_VERSION . "\n"; ?>
	WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>

	PPP SETTINGS:
	<?php
	foreach ( $ppp_options as $name => $value ) {
	if ( $value == false )
		$value = 'false';

	if ( $value == '1' )
		$value = 'true';

	echo $name . ': ' . maybe_serialize( $value ) . "\n";
	}
	?>

	ACTIVE PLUGINS:
	<?php
	$plugins = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );

	foreach ( $plugins as $plugin_path => $plugin ) {
		// If the plugin isn't active, don't show it.
		if ( ! in_array( $plugin_path, $active_plugins ) )
			continue;

	echo $plugin['Name']; ?>: <?php echo $plugin['Version'] ."\n";

	}
	?>

	CURRENT THEME:
	<?php
	if ( get_bloginfo( 'version' ) < '3.4' ) {
		$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
		echo $theme_data['Name'] . ': ' . $theme_data['Version'];
	} else {
		$theme_data = wp_get_theme();
		echo $theme_data->Name . ': ' . $theme_data->Version;
	}
	?>


	Multi-site:               <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

	ADVANCED INFO:
	PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
	MySQL Version:            <?php echo mysql_get_server_info() . "\n"; ?>
	Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

	PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
	PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
	PHP Time Limit:           <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>

	WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

	WP Table Prefix:          <?php echo "Length: ". strlen( $wpdb->prefix ); echo " Status:"; if ( strlen( $wpdb->prefix )>16 ) {echo " ERROR: Too Long";} else {echo " Acceptable";} echo "\n"; ?>

	Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n" ?>
	Page On Front:            <?php $id = get_option( 'page_on_front' ); echo get_the_title( $id ) . ' #' . $id . "\n" ?>
	Page For Posts:           <?php $id = get_option( 'page_on_front' ); echo get_the_title( $id ) . ' #' . $id . "\n" ?>

	Session:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; ?><?php echo "\n"; ?>
	Session Name:             <?php echo esc_html( ini_get( 'session.name' ) ); ?><?php echo "\n"; ?>
	Cookie Path:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); ?><?php echo "\n"; ?>
	Save Path:                <?php echo esc_html( ini_get( 'session.save_path' ) ); ?><?php echo "\n"; ?>
	Use Cookies:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>
	Use Only Cookies:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>

	UPLOAD_MAX_FILESIZE:      <?php if ( function_exists( 'phpversion' ) ) echo ini_get( 'upload_max_filesize' ); ?><?php echo "\n"; ?>
	POST_MAX_SIZE:            <?php if ( function_exists( 'phpversion' ) ) echo ini_get( 'post_max_size' ); ?><?php echo "\n"; ?>
	WordPress Memory Limit:   <?php echo WP_MEMORY_LIMIT; ?><?php echo "\n"; ?>
	DISPLAY ERRORS:           <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
	FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? __( 'Your server supports fsockopen.', 'edd' ) : __( 'Your server does not support fsockopen.', 'edd' ); ?><?php echo "\n"; ?>
		</textarea>
	</div>
	<?php
}