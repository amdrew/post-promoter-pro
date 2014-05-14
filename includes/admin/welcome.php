<?php
/**
 * Weclome Page Class
 *
 * @package     PPP
 * @subpackage  Admin/Welcome
 * @copyright   Copyright (c) 2014, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * PPP_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.4
 */
class PPP_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @since 1.4
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function admin_menus() {
		// About Page
		add_dashboard_page(
			__( 'Welcome to Post Promoter Pro', 'ppp-txt' ),
			__( 'Welcome to Post Promoter Pro', 'ppp-txt' ),
			$this->minimum_capability,
			'ppp-about',
			array( $this, 'about_screen' )
		);

		// Getting Started Page
		add_dashboard_page(
			__( 'Getting started with Post Promoter Pro', 'ppp-txt' ),
			__( 'Getting started with Post Promoter Pro', 'ppp-txt' ),
			$this->minimum_capability,
			'ppp-getting-started',
			array( $this, 'getting_started_screen' )
		);

	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'ppp-about' );
		remove_submenu_page( 'index.php', 'ppp-getting-started' );

		// Badge for welcome page
		$badge_url = PPP_URL . 'includes/images/ppp-badge.png';
		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.ppp-badge {
			padding-top: 150px;
			height: 50px;
			width: 300px;
			color: #666;
			font-weight: bold;
			font-size: 14px;
			text-align: center;
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
			margin: 0 -5px;
			background: url('<?php echo $badge_url; ?>') no-repeat;
		}

		.about-wrap .ppp-badge {
			position: absolute;
			top: 0;
			right: 0;
		}

		.ppp-welcome-screenshots {
			float: right;
			margin-left: 10px!important;
		}
		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 1.9
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'edd-about';
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'ppp-about' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ppp-about' ), 'index.php' ) ) ); ?>">
				<?php _e( "What's New", 'ppp-txt' ); ?>
			</a>
			<a class="nav-tab <?php echo $selected == 'ppp-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ppp-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'ppp-txt' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function about_screen() {
		list( $display_version ) = explode( '-', PPP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to Post Promoter Pro %s', 'ppp-txt' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'The simplest way to promote your WordPress content with maximum results.', 'ppp-txt' ), $display_version ); ?></div>
			<div class="ppp-badge"><?php printf( __( 'Version %s', 'ppp-txt' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<div class="changelog">
				<h3><?php _e( 'More actions in the Schedule View', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . '/includes/images/screenshots/schedule-actions.png'; ?>" class="ppp-welcome-screenshots"/>

					<h4><?php _e( 'Share now', 'ppp-txt' );?></h4>
					<p><?php _e( 'New actions allowing you to "Share Now" or "Share Now and Delete" a scheduled item.', 'ppp-txt' );?></p>
					<p><?php _e( 'If a conversation is happening in your social circles right now, why not share what you\'ve got right now.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Keep your data on uninstall', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . '/includes/images/screenshots/delete.png'; ?>" class="ppp-welcome-screenshots"/>

					<h4><?php _e( 'Prevent data loss while debugging.','ppp-txt' );?></h4>
					<p><?php _e( 'With 1.2, deleting all the options and crons when uninstalling the plugin is Opt-In. This allows you to remove the plugin, upgrade, or reinstall without losing your data. ', 'ppp-txt' );?></p>
					<p><?php _e( 'If at any time you wish to fully remove Post Promoter Pro and it\'s options, simply click the checkbox, and then uninstall.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Dramatically Improved Taxes', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . 'assets/images/screenshots/product-tax.png'; ?>" class="edd-welcome-screenshots"/>

					<h4><?php _e( 'Mark Products Exclusive of Tax', 'ppp-txt' );?></h4>
					<p><?php _e( 'Products in your store can now be marked as exclusive of tax, meaning customers will never have to pay tax on these products during checkout.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Re-written Tax API', 'ppp-txt' );?></h4>
					<p><?php _e( 'The tax system in PPP has been plagued with bugs since it was first introduced, so in 1.9 we have completely rewritten the entire system from the ground up to ensure it is reliable and bug free.', 'ppp-txt' );?></p>
					<p><?php _e( 'It can be difficult to completely delete an entire section of old code, but we are confident the rewrite will be worth every minute of the time spent on it.', 'ppp-txt' );?></p>
					<p><?php _e( 'We are determined to continue to provide you a reliable, easy system to sell your digital products. In order to do that, sometimes we just have to swallow our pride and start over.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Better Support for Large Stores', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Live Search Product Drop Downs','ppp-txt' );?></h4>
					<p><?php _e( 'Every product drop down menu used in Easy Digital Downloads has been replaced with a much more performant version that includes a live Ajax search, meaning stores that have a large number of products will see a significant improvement for page load times in the WordPress Dashboard.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Less Memory Intensive Log Pages', 'ppp-txt' ); ?></h4>
					<p><?php _e( 'The File Download log pages have long been memory intensive to load. By putting them through intensive memory load tests and query optimization, we were able to reduce the number of queries and amount of memory used by a huge degree, making these pages much, much faster..', 'ppp-txt' ); ?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Additional Updates', 'ppp-txt' );?></h3>

				<div class="feature-section col three-col">
					<div>
						<h4><?php _e( 'Improved WP Cron Integration', 'ppp-txt' );?></h4>
						<p><?php _e( 'A rare case when a share is tried to be made twice at the same time is prevented.', 'ppp-txt' );?></p>

						<h4><?php _e( 'Customize the role', 'ppp-txt' );?></h4>
						<p><?php _e( 'Using the new <code>ppp_manage_role</code> filter, you can change what roles can use Post Promoter Pro. By default it is administrators', 'ppp-txt' );?></p>
					</div>

					<div class="last-feature">
						<h4><?php _e( 'Better i18n', 'ppp-txt' );?></h4>
						<p><?php _e( 'Thanks to feedback from you, we\'ve improved the i18n of the plugin, makeing it easier to translate and use in your language.', 'ppp-txt' );?></p>

						<h4><?php _e( 'This fancy Welcome Page', 'ppp-txt' );?></h4>
						<p><?php _e( 'A great way to keep you informed of the changes and getting you started with Post Promoter Pro.', 'ppp-txt' );?></p>
					</div>
				</div>
			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ppp-options' ) ); ?>"><?php _e( 'Start Using Post Promoter Pro', 'ppp-txt' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Getting Started Screen
	 *
	 * @access public
	 * @since 1.9
	 * @return void
	 */
	public function getting_started_screen() {
		list( $display_version ) = explode( '-', PPP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to Post Promoter Pro %s', 'ppp-txt' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'The simplest way to promote your WordPress content with maximum results.', 'ppp-txt' ), $display_version ); ?></div>
			<div class="edd-badge"><?php printf( __( 'Version %s', 'ppp-txt' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Use the tips below to get started using Easy Digital Downloads. You will be up and running in no time!', 'ppp-txt' ); ?></p>

			<div class="changelog">
				<h3><?php _e( 'Creating Your First Download Product', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . 'assets/images/screenshots/edit-download.png'; ?>" class="edd-welcome-screenshots"/>

					<h4><?php printf( __( '<a href="%s">%s &rarr; Add New</a>', 'ppp-txt' ), admin_url( 'post-new.php?post_type=download' ), edd_get_label_plural() ); ?></h4>
					<p><?php printf( __( 'The %s menu is your access point for all aspects of your Easy Digital Downloads product creation and setup. To create your first product, simply click Add New and then fill out the product details.', 'ppp-txt' ), edd_get_label_plural() ); ?></p>

					<h4><?php _e( 'Product Price', 'ppp-txt' );?></h4>
					<p><?php _e( 'Products can have simple prices or variable prices if you wish to have more than one price point for a product. For a single price, simply enter the price. For multiple price points, click <em>Enable variable pricing</em> and enter the options.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Download Files', 'ppp-txt' );?></h4>
					<p><?php _e( 'Uploading the downloadable files is simple. Click <em>Upload File</em> in the Download Files section and choose your download file. To add more than one file, simply click the <em>Add New</em> button.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Display a Product Grid', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . 'assets/images/screenshots/grid.png'; ?>" class="edd-welcome-screenshots"/>

					<h4><?php _e( 'Flexible Product Grids','ppp-txt' );?></h4>
					<p><?php _e( 'The [downloads] short code will display a product grid that works with any theme, no matter the size. It is even responsive!', 'ppp-txt' );?></p>

					<h4><?php _e( 'Change the Number of Columns', 'ppp-txt' );?></h4>
					<p><?php _e( 'You can easily change the number of columns by adding the columns="x" parameter:', 'ppp-txt' );?></p>
					<p><pre>[downloads columns="4"]</pre></p>

					<h4><?php _e( 'Additional Display Options', 'ppp-txt' ); ?></h4>
					<p><?php printf( __( 'The product grids can be customized in any way you wish and there is <a href="%s">extensive documentation</a> to assist you.', 'ppp-txt' ), 'http://easydigitaldownloads.com/documentation' ); ?></p>
				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Purchase Buttons Anywhere', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<img src="<?php echo PPP_URL . 'assets/images/screenshots/purchase-link.png'; ?>" class="edd-welcome-screenshots"/>

					<h4><?php _e( 'The <em>[purchase_link]</em> Short Code','ppp-txt' );?></h4>
					<p><?php _e( 'With easily accessible short codes to display purchase buttons, you can add a Buy Now or Add to Cart button for any product anywhere on your site in seconds.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Buy Now Buttons', 'ppp-txt' );?></h4>
					<p><?php _e( 'Purchase buttons can behave as either Add to Cart or Buy Now buttons. With Buy Now buttons customers are taken straight to PayPal, giving them the most frictionless purchasing experience possible.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Need Help?', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Phenomenal Support','ppp-txt' );?></h4>
					<p><?php _e( 'We do our best to provide the best support we can. If you encounter a problem or have a question, post a question in the <a href="https://easydigitaldownloads.com/support">support forums</a>.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Need Even Faster Support?', 'ppp-txt' );?></h4>
					<p><?php _e( 'Our <a href="https://easydigitaldownloads.com/support/pricing/">Priority Support forums</a> are there for customers that need faster and/or more in-depth assistance.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Stay Up to Date', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Get Notified of Extension Releases','ppp-txt' );?></h4>
					<p><?php _e( 'New extensions that make Easy Digital Downloads even more powerful are released nearly every single week. Subscribe to the newsletter to stay up to date with our latest releases. <a href="http://eepurl.com/kaerz" target="_blank">Signup now</a> to ensure you do not miss a release!', 'ppp-txt' );?></p>

					<h4><?php _e( 'Get Alerted About New Tutorials', 'ppp-txt' );?></h4>
					<p><?php _e( '<a href="http://eepurl.com/kaerz" target="_blank">Signup now</a> to hear about the latest tutorial releases that explain how to take Easy Digital Downloads further.', 'ppp-txt' );?></p>

				</div>
			</div>

			<div class="changelog">
				<h3><?php _e( 'Extensions for Everything', 'ppp-txt' );?></h3>

				<div class="feature-section">

					<h4><?php _e( 'Over 190 Extensions','ppp-txt' );?></h4>
					<p><?php _e( 'Add-on plugins are available that greatly extend the default functionality of Easy Digital Downloads. There are extensions for payment processors, such as Stripe and PayPal, extensions for newsletter integrations, and many, many more.', 'ppp-txt' );?></p>

					<h4><?php _e( 'Visit the Extension Store', 'ppp-txt' );?></h4>
					<p><?php _e( '<a href="https://easydigitaldownloads.com/extensions" target="_blank">The Extensions store</a> has a list of all available extensions, including convenient category filters so you can find exactly what you are looking for.', 'ppp-txt' );?></p>

				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Render Credits Screen
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function credits_screen() {
		list( $display_version ) = explode( '-', PPP_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to Easy Digital Downloads %s', 'ppp-txt' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Easy Digital Downloads %s is ready to make your online store faster, safer and better!', 'ppp-txt' ), $display_version ); ?></div>
			<div class="edd-badge"><?php printf( __( 'Version %s', 'ppp-txt' ), $display_version ); ?></div>

			<?php $this->tabs(); ?>

			<p class="about-description"><?php _e( 'Easy Digital Downloads is created by a worldwide team of developers who aim to provide the #1 eCommerce platform for selling digital goods through WordPress.', 'ppp-txt' ); ?></p>

			<?php echo $this->contributors(); ?>
		</div>
		<?php
	}


	/**
	 * Render Contributors List
	 *
	 * @since 1.4
	 * @uses PPP_Welcome::get_contributors()
	 * @return string $contributor_list HTML formatted list of all the contributors for PPP
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) )
			return '';

		$contributor_list = '<ul class="wp-people-group">';

		foreach ( $contributors as $contributor ) {
			$contributor_list .= '<li class="wp-person">';
			$contributor_list .= sprintf( '<a href="%s" title="%s">',
				esc_url( 'https://github.com/' . $contributor->login ),
				esc_html( sprintf( __( 'View %s', 'ppp-txt' ), $contributor->login ) )
			);
			$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= '</li>';
		}

		$contributor_list .= '</ul>';

		return $contributor_list;
	}


	/**
	 * Sends user to the Welcome page on first activation of PPP as well as each
	 * time PPP is upgraded to a new version
	 *
	 * @access public
	 * @since 1.4
	 * @global $edd_options Array of all the PPP Options
	 * @return void
	 */
	public function welcome() {

		// Bail if no activation redirect
		if ( ! get_transient( '_ppp_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_ppp_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		wp_safe_redirect( admin_url( 'index.php?page=ppp-about' ) ); exit;
	}
}
new PPP_Welcome();
