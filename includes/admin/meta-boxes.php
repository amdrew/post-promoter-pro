<?php
function ppp_register_meta_boxes() {
	add_meta_box( 'ppp_tweet_schedule_metabox', 'Post Promoter Pro', 'ppp_tweet_schedule_callback', 'post', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'ppp_register_meta_boxes', 12 );

function ppp_tweet_schedule_callback() {
	global $post, $ppp_options;
	$ppp_post_exclude = get_post_meta( $post->ID, '_ppp_post_exclude', true );
	$ppp_post_override = get_post_meta( $post->ID, '_ppp_post_override', true );
	$ppp_post_override_data = get_post_meta( $post->ID, '_ppp_post_override_data', true );

	$exclude_style = ( !empty( $ppp_post_exclude ) ) ? 'display: none;' : '';
	$override_style = ( empty( $ppp_post_override ) ) ? 'display: none;' : '';
	?>
	<input type="checkbox" name="_ppp_post_exclude" id="_ppp_post_exclude" value="1" <?php checked( '1', $ppp_post_exclude, true ); ?> />&nbsp;<label for="_ppp_post_exclude"><?php _e( 'Do not schedule social media promotion for this post.', PPP_CORE_TEXT_DOMAIN ); ?></label>
	<div style="<?php echo $exclude_style; ?>" id="ppp-post-override-wrap">
		<input type="checkbox" name="_ppp_post_override" id="_ppp_post_override" value="1" <?php checked( '1', $ppp_post_override, true ); ?> />&nbsp;<label for="_ppp_post_override"><?php _e( 'Override Default Text and Times', PPP_CORE_TEXT_DOMAIN ); ?></label>
		<div class="post-override-matrix" style="<?php echo $override_style; ?>">
			<label for="day1"><?php _e( 'Day 1', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day1" size="150" name="_ppp_post_override_data[day1][text]" <?php if ( isset( $ppp_post_override_data['day1']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day1']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day1" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day1][time]" class="share-time-selector" value="<?php echo ( isset( $ppp_post_override_data['day1']['time'] ) ) ? $ppp_post_override_data['day1']['time'] : $ppp_options['times']['day1']; ?>" size="8" /><br />

			<label for="day2"><?php _e( 'Day 2', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day2" size="150" name="_ppp_post_override_data[day2][text]" <?php if ( isset( $ppp_post_override_data['day2']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day2']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day2" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day2][time]" class="share-time-selector"  value="<?php echo ( isset( $ppp_post_override_data['day2']['time'] ) ) ? $ppp_post_override_data['day2']['time'] : $ppp_options['times']['day2']; ?>" size="8" /><br />

			<label for="day3"><?php _e( 'Day 3', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day3" size="150" name="_ppp_post_override_data[day3][text]" <?php if ( isset( $ppp_post_override_data['day3']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day3']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day3" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day3][time]" class="share-time-selector"  value="<?php echo ( isset( $ppp_post_override_data['day3']['time'] ) ) ? $ppp_post_override_data['day3']['time'] : $ppp_options['times']['day3']; ?>" size="8" /><br />

			<label for="day4"><?php _e( 'Day 4', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day4" size="150" name="_ppp_post_override_data[day4][text]" <?php if ( isset( $ppp_post_override_data['day4']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day4']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day4" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day4][time]" class="share-time-selector"  value="<?php echo ( isset( $ppp_post_override_data['day4']['time'] ) ) ? $ppp_post_override_data['day4']['time'] : $ppp_options['times']['day4']; ?>" size="8" /><br />

			<label for="day5"><?php _e( 'Day 5', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day5" size="150" name="_ppp_post_override_data[day5][text]" <?php if ( isset( $ppp_post_override_data['day5']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day5']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day5" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day5][time]" class="share-time-selector"  value="<?php echo ( isset( $ppp_post_override_data['day5']['time'] ) ) ? $ppp_post_override_data['day5']['time'] : $ppp_options['times']['day5']; ?>" size="8" /><br />

			<label for="day6"><?php _e( 'Day 6', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;
			<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day6" size="150" name="_ppp_post_override_data[day6][text]" <?php if ( isset( $ppp_post_override_data['day6']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day6']['text'] ); ?>"<?php ;}?> />&nbsp;
			<input id="day6" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day6][time]" class="share-time-selector"  value="<?php echo ( isset( $ppp_post_override_data['day6']['time'] ) ) ? $ppp_post_override_data['day6']['time'] : $ppp_options['times']['day6']; ?>" size="8" /><br />
			<p><?php _e( 'Do not include links in your text, this will be added automatically.', PPP_CORE_TEXT_DOMAIN ); ?></p>
		</div>
	</div>
	<?php
}

function ppp_save_post_meta_boxes( $post_id, $post ) {

	$ppp_post_exclude = ( isset( $_REQUEST['_ppp_post_exclude'] ) ) ? $_REQUEST['_ppp_post_exclude'] : '0';
	$ppp_post_override = ( isset( $_REQUEST['_ppp_post_override'] ) ) ? $_REQUEST['_ppp_post_override'] : '0';
	$ppp_post_override_data = isset( $_REQUEST['_ppp_post_override_data'] ) ? $_REQUEST['_ppp_post_override_data'] : array();

	update_post_meta( $post->ID, '_ppp_post_exclude', $ppp_post_exclude );
	update_post_meta( $post->ID, '_ppp_post_override', $ppp_post_override );
	if ( !empty( $ppp_post_override_data ) ) {
		update_post_meta( $post->ID, '_ppp_post_override_data', $ppp_post_override_data );
	}

	return $post->ID;
}
add_action( 'save_post', 'ppp_save_post_meta_boxes', 1, 2 ); // save the custom fields
