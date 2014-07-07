<?php

function ppp_disconnect_social() {
	if ( isset( $_GET['ppp_social_disconnect'] ) && isset( $_GET['ppp_network'] ) ) {
		$network = $_GET['ppp_network'];
		do_action( 'ppp_disconnect-' . $network );
	}
}
add_action( 'admin_init', 'ppp_disconnect_social', 10 );

function ppp_generate_metabox_tabs() {
	global $visibleKey;

	$tabs = apply_filters( 'ppp_metabox_tabs', array() );
	$i = 0;
	foreach ( $tabs as $key => $values ) {
		if ( $i === 0 ) {
			$visibleKey = $key;
			$class = 'tabs';
		} else {
			$class = '';
		}

		?><li class="<?php echo $class; ?>"><a href="#<?php echo $key; ?>"><?php
		if ( $values['class'] !== false ) {
			?>
			<span class="dashicons <?php echo $values['class']; ?>"></span>&nbsp;
			<?php
		}
		echo $values['name']; ?></a></li><?php
		$i++;
	}
}
add_action( 'ppp_metabox_tabs_display', 'ppp_generate_metabox_tabs', 10 );

function ppp_generate_social_account_tabs() {
	global $visibleSettingTab;

	$tabs = apply_filters( 'ppp_metabox_tabs', array() );
	$i = 0;
	?><h2 id="ppp-social-connect-tabs" class="nav-tab-wrapper"><?php
	foreach ( $tabs as $key => $values ) {
		if ( $i === 0 ) {
			$visibleSettingTab = $key;
			$class = ' nav-tab-active';
		} else {
			$class = '';
		}
		?><a class="nav-tab<?php echo $class; ?>" href='#<?php echo $key; ?>'><?php
		if ( $values['class'] !== false ) {
			?>
			<span class="dashicons <?php echo $values['class']; ?>"></span>&nbsp;
			<?php
		}
		echo $values['name']; ?></a></li><?php
		?></a><?php
		$i++;
	}
	?></h2><?php
}
add_action( 'ppp_social_media_tabs_display', 'ppp_generate_social_account_tabs', 10 );

function ppp_generate_metabox_content( $post ) {
	global $visibleKey;
	$tab_content = apply_filters( 'ppp_metabox_content', array() );
	if ( empty( $tab_content ) ) {
		printf( __( 'No social media accounts active. <a href="%s">Connect with your accounts now</a>.', 'ppp-txt' ), admin_url( 'admin.php?page=ppp-social-settings' ) );
	} else {
		foreach ( $tab_content as $service ) {
			$hidden = ( $visibleKey == $service ) ? '' : ' hidden';
			?>
			<div class="wp-tab-panel tabs-panel<?php echo $hidden; ?>" id="<?php echo $service; ?>">
				<?php do_action( 'ppp_generate_metabox_content-' . $service, $post ); ?>
			</div>
			<?php
		}
	}
}
add_action( 'ppp_metabox_content_display', 'ppp_generate_metabox_content', 10, 1 );

function ppp_generate_social_account_content() {
	global $visibleSettingTab;
	$tab_content = apply_filters( 'ppp_metabox_content', array() );
	if ( empty( $tab_content ) ) {
		printf( __( 'No social media accounts active. <a href="%s">Connect with your accounts now</a>.', 'ppp-txt' ), admin_url( 'admin.php?page=ppp-social-settings' ) );
	} else {
		foreach ( $tab_content as $service ) {
			$hidden = ( $visibleSettingTab == $service ) ? '' : ' hidden';
			?>
			<div class="ppp-social-connect<?php echo $hidden; ?>" id="<?php echo $service; ?>">
				<?php do_action( 'ppp_connect_display-' . $service ); ?>
			</div>
			<?php
		}
	}
}
add_action( 'ppp_social_media_content_display', 'ppp_generate_social_account_content', 10, 1 );