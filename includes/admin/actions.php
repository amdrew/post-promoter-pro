<?php

/**
 * Adds the Bit.ly Shortener to the list of available shorteners
 * @param  string $selected_shortener The currently selected url shortener
 * @return void
 */
function ppp_add_bitly_shortener( $selected_shortener ) {
	?><option value="bitly" <?php selected( $selected_shortener, 'bitly', true ); ?>>Bit.ly</option><?php
}
add_action( 'ppp_url_shorteners', 'ppp_add_bitly_shortener', 10, 1 );

/**
 * Displays the bitly settings area when bitly is selected as the URL shortener
 * @return void
 */
function ppp_display_bitly_settings() {
	global $ppp_bitly_oauth;
	?>
	<p>
		<?php $ppp_bitly_oauth->ppp_initialize_bitly(); ?>
		<?php if ( !ppp_bitly_enabled() ) : ?>
		<a href="<?php echo $ppp_bitly_oauth->ppp_get_bitly_auth_url(); ?>" class="button-primary">Connect To Bit.ly</a>
		<?php endif; ?>
	</p>
	<?php
}
add_action( 'ppp_shortener_settings-bitly', 'ppp_display_bitly_settings', 10 );