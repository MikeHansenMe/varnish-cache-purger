<?php

function add_vp_menu() {

	if( ! current_user_can( 'manage_options' ) ) { return; }
	add_menu_page( 'admin.php', 'Varnish Purger', 'administrator', 'varnish-purger', 'vp_main_menu' );
}
add_action( 'admin_menu', 'add_vp_menu' );
