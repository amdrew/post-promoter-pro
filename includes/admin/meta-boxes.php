<?php
function ppp_register_meta_boxes() {
	add_meta_box( 'ppp_tweet_schedule_metabox', 'Post Promoter Pro', 'ppp_tweet_schedule_callback', 'post', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'ppp_register_meta_boxes', 12 );

function ppp_tweet_schedule_callback() {
	global $post;
	$ppp_post_override = get_post_meta( $post->ID, '_ppp_post_override', true );
	$ppp_post_override_data = get_post_meta( $post->ID, '_ppp_post_override_data', true );

	$style = ( empty( $ppp_post_override ) ) ? 'display: none;' : '';
	?>
	<input type="checkbox" name="_ppp_post_override" id="_ppp_post_override" value="1" <?php checked( '1', $ppp_post_override, true ); ?> />&nbsp;<label for="_ppp_post_override"><?php _e( 'Override Default Text and Times', PPP_CORE_TEXT_DOMAIN ); ?></label>
	<div class="post-override-matrix" style="<?php echo $style; ?>">
		<label for="day1"><?php _e( 'Day 1', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day1" size="150" name="_ppp_post_override_data[day1][text]" <?php if ( isset( $ppp_post_override_data['day1']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day1']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day1" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day1][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day1']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day1']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<label for="day2"><?php _e( 'Day 2', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day2" size="150" name="_ppp_post_override_data[day2][text]" <?php if ( isset( $ppp_post_override_data['day2']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day2']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day2" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day2][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day2']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day2']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<label for="day3"><?php _e( 'Day 3', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day3" size="150" name="_ppp_post_override_data[day3][text]" <?php if ( isset( $ppp_post_override_data['day3']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day3']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day3" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day3][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day3']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day3']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<label for="day4"><?php _e( 'Day 4', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day4" size="150" name="_ppp_post_override_data[day4][text]" <?php if ( isset( $ppp_post_override_data['day4']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day4']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day4" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day4][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day4']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day4']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<label for="day5"><?php _e( 'Day 5', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day5" size="150" name="_ppp_post_override_data[day5][text]" <?php if ( isset( $ppp_post_override_data['day5']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day5']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day5" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day5][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day5']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day5']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<label for="day6"><?php _e( 'Day 6', PPP_CORE_TEXT_DOMAIN ); ?></label>&nbsp;<input type="text" placeholder="<?php _e( 'Social Text', PPP_CORE_TEXT_DOMAIN ); ?>" id="day6" size="150" name="_ppp_post_override_data[day6][text]" <?php if ( isset( $ppp_post_override_data['day6']['text'] ) ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day6']['text'] ); ?>"<?php ;}?> />&nbsp;<input id="day6" type="text" placeholder="<?php _e( 'Time', PPP_CORE_TEXT_DOMAIN ); ?>" name="_ppp_post_override_data[day6][time]" class="share-time-selector" <?php if ( $ppp_post_override_data['day6']['time'] != '' ) {?>value="<?php echo htmlspecialchars( $ppp_post_override_data['day6']['time'] ); ?>"<?php ;}?> size="8" /><br />
		<p><?php _e( 'Do not include links in your text, this will be added automatically.', PPP_CORE_TEXT_DOMAIN ); ?></p>
	</div>
	<?php
}

function ppp_save_post_meta_boxes( $post_id, $post ) {
	$current_post_times = get_post_meta( $post->ID, '_ppp_post_override_data', true );
	$current_post_times = is_array( $current_post_times ) ? wp_list_pluck( $current_post_times, 'time' ) : false;

	$ppp_post_override = ( isset( $_REQUEST['_ppp_post_override'] ) ) ? $_REQUEST['_ppp_post_override'] : '0';
	$ppp_post_override_data = isset( $_REQUEST['_ppp_post_override_data'] ) ? $_REQUEST['_ppp_post_override_data'] : array();

	$new_post_times = wp_list_pluck( $ppp_post_override_data, 'time' );

	update_post_meta( $post->ID, '_ppp_post_override', $ppp_post_override );
	update_post_meta( $post->ID, '_ppp_post_override_data', $ppp_post_override_data );

	return $post->ID;
}
add_action( 'save_post', 'ppp_save_post_meta_boxes', 1, 2 ); // save the custom fields