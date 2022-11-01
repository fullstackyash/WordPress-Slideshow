<?php
/**
 * Plugin Name:     WordPress Slideshow
 * Plugin URI:      https://github.com/fullstackyash/WordPress-Slideshow
 * Description:     WordPress plugin for creating slideshow.
 * Author:          Yash Chopra
 * Author URI:      https://github.com/fullstackyash
 * Text Domain:     wordpress-slideshow
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wordpress_Slideshow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'WORDPRESS_SLIDESHOW_DIR' ) ) {
	define( 'WORDPRESS_SLIDESHOW_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
}

require WORDPRESS_SLIDESHOW_DIR . '/includes/admin/class-admin-settings.php';
