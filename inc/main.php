<?php

add_action( 'admin_init', 'vp_purge_now', 1 );
function vp_purge_now() {

	if( ! current_user_can( 'manage_options' ) ) { return; }

	if( isset( $_POST['vp_submit'] ) && $_POST['vp_purge'] == 'page' ) {
	
		$url = $_POST['vp_page'];
		if( is_ssl() == 'true' ) {
			$page = 'https://' . preg_replace('#^.*://#', '', $url);
		} else {
			$page = 'http://' . preg_replace('#^.*://#', '', $url);
		}

		if( empty( $_POST['vp_page'] ) ) {
			add_action( 'admin_notices', 'vp_url_req' );
			return;
		} else {

//			Here is the curl session for reference.
//			$res = curl_init( $page );
//			curl_setopt( $res, CURLOPT_RETURNTRANSFER, true );
//			curl_setopt( $res, CURLOPT_CUSTOMREQUEST, 'PURGE' );
//			$content = curl_exec( $res );
//			$info = curl_getinfo( $res );
//			curl_close( $res );

			$info = wp_remote_request( $page, array( 'method' => 'PURGE', 'host' => home_url() ) );

			if ( ! empty( $info ) ) {
				if( $info['response']['code'] == '404' ) {
					add_action( 'admin_notices', 'vp_404' );
					return;
				} else if ( $info['response']['code'] == '405' ) {
					add_action( 'admin_notices', 'vp_405' );
					return;
				} else if ( $info['response']['code'] == '200' ) {
					add_action( 'admin_notices', 'vp_200' );
					return;
				} else {
					add_action( 'admin_notices', 'vp_general_error' );
				}
			} else {
				add_action( 'admin_notices', 'vp_url_req' );
			}
		}
	} else if( isset( $_POST['vp_submit'] ) && $_POST['vp_purge'] == 'all' ) {

		$page = get_option( 'siteurl' );
		$info = wp_remote_request( $page, array( 'method' => 'BAN', 'host' => home_url() ) );
		print_r($info['response']['code']);
//		$res = curl_init( $page );
//		curl_setopt( $res, CURLOPT_RETURNTRANSFER, true );
//		curl_setopt( $res, CURLOPT_CUSTOMREQUEST, 'BAN' );
//		$content = curl_exec( $res );
//		$info = curl_getinfo( $res );
//		curl_close( $res );

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
	}
}


function vp_main_menu() {

	echo '<form name="varnish-purge" action="admin.php?page=varnish-cache-purger" method="POST">
		<input type="text" name="vp_page" placeholder="Enter URL" />
		<input type="hidden" name="vp_purge" value="page" />
		<input type="submit" name="vp_submit" value="Purge Now" />
		</form>';	

	echo '<form name="varnish-purge-all" action="admin.php?page=varnish-cache-purger" method="POST">
		<input type="hidden" name="vp_purge" value="all" />
		<input type="submit" name="vp_submit" value="Purge All" />
		</form>';
}
