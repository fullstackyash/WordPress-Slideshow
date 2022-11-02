<?php
/**
 * WordPress Slideshow Shotcode.
 *
 * @package wordpress-slideshow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class for adding the "WordPress slideshow" shortcode.
 */
class  WORDPRESS_SLIDESHOW_SHORTCODE {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'wordpress_slideshow', array( $this, 'wordpress_slideshow' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wordpress_slideshow_enqueue_assets' ) );
	}

	/**
	 * WordPress Slideshow Shortcode.
	 *
	 * @return mixed
	 */
	public function wordpress_slideshow() {
		ob_start();
		$image_ids = get_option( 'slideshow_settings' ) ? explode( ',', get_option( 'slideshow_settings' ) ) : array();
		if ( ! empty( $image_ids ) ) {
			?>
			<div class="wordpress-slideshow">
			<?php
			foreach ( $image_ids as $i => &$id ) {
				$url = wp_get_attachment_image_url( $id, 'full' );
				if ( $url ) {
					?>
				<img src="<?php echo esc_url( $url ); ?>" />
					<?php
				}
			}
			?>
			</div>
			<?php

			// Include assets only if shortcode is added.
			wp_enqueue_style( 'slick-style' );
			wp_enqueue_style( 'wordpress-slideshow-style' );
			wp_enqueue_script( 'slick-script' );
			wp_enqueue_script( 'wordpress-slideshow-script' );
		}
		return ob_get_clean();
	}

	/**
	 * WordPress Slideshow enqueue scripts.
	 *
	 * @return void
	 */
	public function wordpress_slideshow_enqueue_assets() : void {

		$plugin_name = basename( WORDPRESS_SLIDESHOW_DIR );

		// Enqueue slick slideshow style.
		wp_register_style(
			'slick-style',
			'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
			array(),
			'1.8.1'
		);

		// Enqueue style for WordPress slideshow.
		wp_register_style(
			'wordpress-slideshow-style',
			plugins_url( $plugin_name . '/src/styles/wordpress-slideshow.css' ),
			array(),
			filemtime( WORDPRESS_SLIDESHOW_DIR . '/src/styles/wordpress-slideshow.css' )
		);

		// Enqueue slick slideshow script.
		wp_register_script(
			'slick-script',
			'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
			array(),
			'1.8.1',
			true
		);

		// Enqueue script for WordPress slideshow.
		wp_register_script(
			'wordpress-slideshow-script',
			plugins_url( $plugin_name . '/src/scripts/wordpress-slideshow.js' ),
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-sortable' ),
			filemtime( WORDPRESS_SLIDESHOW_DIR . '/src/scripts/wordpress-slideshow.js' ),
			true
		);

	}
}

$wordpress_slideshow_shortcode = new WORDPRESS_SLIDESHOW_SHORTCODE();
