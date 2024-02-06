<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ariesmeralles.website
 * @since      1.0.0
 *
 * @package    Wp_Nextplate
 * @subpackage Wp_Nextplate/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Nextplate
 * @subpackage Wp_Nextplate/includes
 * @author     Aries Meralles <arsrymeralles@gmail.com>
 */
class Wp_Nextplate_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-nextplate',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
