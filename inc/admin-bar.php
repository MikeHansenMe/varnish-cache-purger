<?php

add_action( 'admin_init', 'vp_adminbar_purge_all', 1 );

function vp_adminbar_purge_all() {

	if( ! current_user_can( 'manage_options' ) ) { return; }
	$url = home_url();
	if( is_ssl() == 'true' ) {
		$page = 'https://' . preg_replace('#^.*://#', '', $url);
	} else {
		$page = 'http://' . preg_replace('#^.*://#', '', $url);
	}

	if( isset( $_GET['vp_submit'] ) && $_GET['vp_purge'] == 'all' ) {
		$info = wp_remote_request( $page, array( 'method' => 'BAN', 'host' => home_url() ) );

/*
		Here is the curl session for reference.
		$page = get_option( 'siteurl' );
		$res = curl_init( $page );
		curl_setopt( $res, CURLOPT_RETURNTRANSFER, true );	
		curl_setopt( $res, CURLOPT_CUSTOMREQUEST, 'BAN' );
		$content = curl_exec( $res );
		$info = curl_getinfo( $res );
		curl_close( $res );
*/

		if( ! empty( $info ) ) {
			if( $info['response']['code'] == '200' ) {
				add_action( 'admin_notices', 'vp_200' );
				return;
			} else if( $info['response']['code'] == '404' ) {
				add_action( 'admin_notices', 'vp_404' );
				return;
			} else if( $info['response']['code'] == '405' ) {
				add_action( 'admin_notices', 'vp_405' );
				return;
			} else {
				add_action( 'admin_notices', 'vp_general_error' );
				return;
			}
		} else {
			add_action( 'admin_notices', 'vp_general_error' );
			return;
		}
	}
}

add_action( 'admin_bar_menu', 'vp_adminbar', 200.5 );

function vp_adminbar( $wp_admin_bar ) {
	$args = array(
		'id'	=> 'varnish_cache_purger',
		'title' => 'Varnish Cache Purger',
		'href'	=> add_query_arg( array( 'vp_purge' => 'all', 'vp_submit' => 'true' ) ),
	);
	$wp_admin_bar->add_node( $args );
}
