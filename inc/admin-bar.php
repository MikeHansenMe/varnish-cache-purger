<?php

add_action( 'admin_init', 'vp_adminbar_purge_all', 1 );

function vp_adminbar_purge_all() {

	if( ! current_user_can( 'manage_options' ) ) { return; }

	if( isset( $_GET['vp_submit'] ) && $_GET['vp_purge'] == 'all' ) {
		$page = get_option( 'siteurl' ) . '/*';
		$res = curl_init( $page );
		curl_setopt( $res, CURLOPT_RETURNTRANSFER, true );	
		curl_setopt( $res, CURLOPT_CUSTOMREQUEST, 'BAN' );
		$content = curl_exec( $res );
		$info = curl_getinfo( $res );
		curl_close( $res );
		print_r( $info );

		add_action( 'admin_notices', 'vp_200' );
	}
}
add_action( 'admin_bar_menu', 'vp_adminbar', 200.5 );

function vp_adminbar( $wp_admin_bar ) {
	$args = array(
		'id'	=> 'varnish_cache_purger',
		'title' => '' . __( 'Varnish Cache Purger' ) . '<form name="varnish-purge-all" action="" method="GET">
				<input type="hidden" name="vp_purge" value="all" />
				<input type="submit" name="vp_submit" value="true" />
				</form>',
		'href'	=> '#',
	);
	$wp_admin_bar->add_node( $args );
}
