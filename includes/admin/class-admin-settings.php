<?php
/**
 * Admin settings file.
 *
 * @package wordpress-slideshow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin settings class for registring plugin admin slideshow settings.
 */
class Admin_Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'slideshow_settings' ) );
		add_action( 'admin_menu', array( $this, 'slideshow_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}


	/**
	 * Register slideshow settings.
	 *
	 * @return void
	 */
	public function slideshow_settings() : void {
		register_setting(
			'slideshow',
			'slideshow_settings'
		);

		add_settings_section(
			'slideshow_section',
			__( 'Create your slideshow', 'wordpress-slideshow' ),
			array( $this, 'slideshow_section_callback' ),
			'slideshow'
		);

		add_settings_field(
			'slideshow_images',
			__( 'Slideshow Images', 'wordpress-slideshow' ),
			array( $this, 'slideshow_images_callback' ),
			'slideshow',
			'slideshow_section'
		);
	}

	/**
	 * Slideshow section callback.
	 * .
	 *
	 * @return void
	 */
	public function slideshow_section_callback() : void {
		esc_html_e( 'Add/Upload multiple images and arrange them as per your choice', 'wordpress-slideshow' );
	}

	/**
	 * Add slideshow page in admin menu.
	 *
	 * @return void
	 */
	public function slideshow_settings_page() : void {
		add_menu_page(
			__( 'Slideshow Settings', 'wordpress-slideshow' ),
			__( 'Slideshow', 'wordpress-slideshow' ),
			'manage_options',
			'slideshow',
			array( $this, 'slideshow_page_callback' ),
			'dashicons-images-alt2'
		);
	}

	/**
	 * Slideshow title field callback method.
	 *
	 * @return void
	 */
	public function slideshow_images_callback() : void {
		$image_ids = get_option( 'slideshow_settings' ) ? explode( ',', get_option( 'slideshow_settings' ) ) : array();
		?>
		<ul class="slideshow-gallery">
		<?php
		if ( ! empty( $image_ids ) ) {
			foreach ( $image_ids as $i => &$id ) {
				$url = wp_get_attachment_image_url( $id, 'full' );
				if ( $url ) {
					?>
							<li data-id="<?php echo esc_attr( $id ); ?>">
								<img src="<?php echo esc_url( $url ); ?>" />
								<a href="#" class="slideshow-gallery-remove">&times;</a>
							</li>
						<?php
				} else {
					unset( $image_ids[ $i ] );
				}
			}
		}

		?>
		</ul>
		<input type="hidden" name="slideshow_settings" value="<?php echo esc_attr( join( ',', $image_ids ) ); ?>" />
		<div class="add-images  slideshow-upload-button">
			<a href="#" class="button"><?php esc_html_e( 'Add Images', 'wordpress-slideshow' ); ?></a>
		</div>	 
		<?php
	}

	/**
	 * Slideshow page callback.
	 *
	 * @return void
	 */
	public function slideshow_page_callback() : void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'slideshow_messages', 'slideshow_message', __( 'Settings Saved', 'wordpress-slideshow' ), 'updated' );
		}
		settings_errors( 'slideshow_messages' );
		?>
		<div class="slidehow-container">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post" enctype="multipart/form-data">
				<?php
				settings_fields( 'slideshow' );
				do_settings_sections( 'slideshow' );
				submit_button( __( 'Save Settings', 'wordpress-slideshow' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue Admin scripts and styles.
	 *
	 * @param string $hook - current page.
	 * @return void
	 */
	public function admin_scripts( $hook ) : void {

		// Check if slidehow settings page.
		if ( 'toplevel_page_slideshow' !== $hook ) {
			return;
		}

		// WordPress media uploader scripts.
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		$plugin_name = basename( WORDPRESS_SLIDESHOW_DIR );

		wp_enqueue_script(
			'admin-script',
			plugins_url( $plugin_name . '/src/scripts/admin-script.js' ),
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-sortable' ),
			filemtime( WORDPRESS_SLIDESHOW_DIR . '/src/scripts/admin-script.js' ),
			true
		);

		wp_enqueue_style(
			'admin-style',
			plugins_url( $plugin_name . '/src/styles/admin-style.css' ),
			array(),
			filemtime( WORDPRESS_SLIDESHOW_DIR . '/src/styles/admin-style.css' )
		);
	}
}

$admin_settings = new Admin_Settings();
