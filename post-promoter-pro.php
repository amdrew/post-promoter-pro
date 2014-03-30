<?php
/*
Plugin Name: Post Promoter Pro
Plugin URI: http://filament-studios.com/plugins/post-promoter-pro
Description: Schedule the promotion of blog posts for the next 6 days, with no further work.
Version: 1.0b03302014
Author: Filament Studios
Author URI: http://filament-studios.com
License: GPLv2
*/

define( 'PPP_PATH', plugin_dir_path( __FILE__ ) );
define( 'PPP_VERSION', '1.0b03302014' );
define( 'PPP_FILE', plugin_basename( __FILE__ ) );
define( 'PPP_URL', plugins_url( 'post-promoter-pro', 'post-promoter-pro.php' ) );

class PostPromoterPro {
	private static $ppp_instance;

	private function __construct() {
		global $ppp_options, $ppp_social_settings;
		$ppp_options = get_option( 'ppp_options' );
		$ppp_social_settings = get_option( 'ppp_social_settings' );

		include PPP_PATH . '/includes/share-functions.php';
		include PPP_PATH . '/includes/libs/social-loader.php';

		if ( is_admin() ) {
			include PPP_PATH . '/includes/admin/admin-pages.php';
			include PPP_PATH . '/includes/admin/meta-boxes.php';
			add_action( 'admin_menu', array( $this, 'ppp_setup_admin_menu' ), 1000, 0 );
			add_filter( 'plugin_action_links', array( $this, 'plugin_settings_links' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'load_admin_hooks' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_scripts' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_styes' ) );
			add_action( 'trash_post', 'ppp_remove_scheduled_shares', 10, 1 );
		}

		add_action( 'save_post', 'ppp_schedule_share', 10, 2);
	}

	/**
	 * Get the singleton instance of our plugin
	 * @return class The Instance
	 * @access public
	 */
	public static function getInstance() {
		if ( !self::$ppp_instance ) {
			self::$ppp_instance = new PostPromoterPro();
		}

		return self::$ppp_instance;
	}

	public function load_admin_hooks() {
		$this->ppp_register_settings();
	}

	/**
	 * Queue up the JavaScript file for the admin page, only on our admin page
	 * @param  string $hook The current page in the admin
	 * @return void
	 * @access public
	 */
	public function load_custom_scripts( $hook ) {
		if ( 'toplevel_page_ppp-options' != $hook && 'post-new.php' != $hook && 'post.php' != $hook )
			return;

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'ppp_timepicker_js', PPP_URL . '/includes/scripts/libs/jquery-ui-timepicker-addon.js', array( 'jquery', 'jquery-ui-core' ), PPP_VERSION, true );
		wp_enqueue_script( 'ppp_core_custom_js', PPP_URL.'/includes/scripts/js/ppp_custom.js', 'jquery', PPP_VERSION, true );
	}

	public function load_styes() {
		wp_register_style( 'ppp_admin_css', PPP_URL . '/includes/scripts/css/admin-style.css', false, PPP_VERSION );
		wp_enqueue_style( 'ppp_admin_css' );
	}

	/**
	 * Adds the Settings and Post Promoter Pro Link to the Settings page list
	 * @param  array $links The current list of links
	 * @param  string $file The plugin file
	 * @return array        The new list of links, with our additional ones added
	 * @access public
	 */
	public function plugin_settings_links( $links, $file ) {
		if ( $file != PPP_FILE ) {
			return $links;
		}

		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=post-promoter-pro' ), __( 'Settings', 'ppp-txt' ) );

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Add the Pushover Notifications item to the Settings menu
	 * @return void
	 * @access public
	 */
	public function ppp_setup_admin_menu() {
		add_menu_page( __( 'Post Promoter', 'ppp-txt' ),
		               __( 'Post Promoter', 'ppp-txt' ),
		               'manage_options',
		               'ppp-options',
		               'ppp_admin_page'
		             );

		add_submenu_page( 'ppp-options', __( 'Social Settings', 'ppp-txt' ), __( 'Social Settings', 'ppp-txt' ), 'manage_options', 'ppp-social-settings', 'ppp_display_social' );
		add_submenu_page( 'ppp-options', __( 'System Info', 'ppp-txt' ), __( 'System Info', 'ppp-txt' ), 'manage_options', 'ppp-system-info', 'ppp_display_sysinfo' );
	}

	/**
	 * Register/Whitelist our settings on the settings page, allow extensions and other plugins to hook into this
	 * @return void
	 * @access public
	 */
	public function ppp_register_settings() {
		register_setting( 'ppp-options', 'ppp_options' );
		register_setting( 'ppp-social-settings', 'ppp_social_settings' );
		do_action( 'ppp_register_additional_settings' );

		global $ppp_options;
		if ( !isset( $ppp_options['times'] ) ) {
			$i = 1;
			while( $i <= 6 ) {
				$ppp_options['times']['day' . $i] = '12:00';
				$i++;
			}
		} elseif ( count( $ppp_options['times'] ) < 6 || in_array( '', $ppp_options['times'], true ) ) {
			$i = 1;
			while( $i <= 6 ) {
				if ( !isset( $ppp_options['times']['day' . $i] ) || empty( $ppp_options['times']['day' . $i] ) ) {
					$ppp_options['times']['day' . $i] = '12:00';
				}
				$i++;
			}
		}

	}

	/**
	 * Load the Text Domain for i18n
	 * @return void
	 * @access public
	 */
	public function ppp_loaddomain() {
		load_plugin_textdomain( 'ppp-txt', false, '/post-promoter-pro/languages/' );
	}
}

$ppp_loaded = PostPromoterPro::getInstance();
