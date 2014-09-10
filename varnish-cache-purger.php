<?php
/*

Plugin Name: Varnish Cache Purger
Plugin URI: https://github.com/voldemortensen/varnish-cache-purger
Description: Purges your varnish cache on demand.
Version: 0.3
Author: Garth Mortensen
GitHub Plugin URI: https://github.com/voldemortensen/varnish-cache-purger
GitHub Branch: master
*/

if( ! defined( 'WPINC' ) ) { die; }

define( 'VP_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'VP_BASE_URL', plugin_dir_url( __FILE__ ) );

require_once( VP_BASE_DIR . 'inc/menu.php' );
require_once( VP_BASE_DIR . 'inc/main.php' );
require_once( VP_BASE_DIR . 'inc/admin-bar.php' );
require_once( VP_BASE_DIR . 'inc/errors.php' );

function vp_load_updater() {
	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

		$github = array(
			'updater'	=> VP_BASE_DIR . 'updater/class-github-updater.php',
			'api'		=> VP_BASE_DIR . 'updater/class-github-api.php',
			'plugin'	=> VP_BASE_DIR . 'updater/class-plugin-updater.php'
		);

		if( ! class_exists( 'GitHub_Updater' ) && file_exists( $github['updater'] ) ) {
			require_once( $github['updater'] );
		}

		if( ! class_exists( 'GitHub_Updater_GitHub_API' ) && file_exists( $github['api'] ) ) {
			require_once( $github['api'] );
		}

		if( ! class_exists( 'GitHub_Plugin_Updater' ) && file_exists( $github['plugin'] ) ) {
			require_once( $github['plugin'] );
		}

		new GitHub_Plugin_Updater;
	}
}
add_action( 'admin_init', 'vp_load_updater' );
