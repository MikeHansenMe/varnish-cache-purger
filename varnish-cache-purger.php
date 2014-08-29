<?php
/*

Plugin Name: Varnish Cache Purger
Plugin URI: https://github.com/voldemortensen/varnish-cache-purger
Description: Purges your varnish cache on certain changes and on demand.
Version: 0.1
Author: Garth Mortensen

*/

if( ! defined( 'WPINC' ) ) { die; }

define( 'VP_BASE_DIR', plugin_dir_path( __FILE__ ) );
define( 'VP_BASE_URL', plugin_dir_url( __FILE__ ) );

require_once( VP_BASE_DIR . 'inc/menu.php' );
require_once( VP_BASE_DIR . 'inc/main.php' );
require_once( VP_BASE_DIR . 'inc/admin-bar.php' );
require_once( VP_BASE_DIR . 'inc/errors.php' );
