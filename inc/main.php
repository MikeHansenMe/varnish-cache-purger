<?php

add_action( 'admin_init', 'vp_purge_now', 1 );
function vp_purge_now() {

	if( ! current_user_can( 'manage_options' ) ) { return; }

	if( isset( $_POST['vp_submit'] ) && $_POST['vp_purge'] == 'true' ) {
	
		$vpip = $_SERVER['SERVER_ADDR'];
		$page = $_POST['vp_page'];
		$args = array(
			'method'	=> 'PURGE',
			'headers'	=> array(
						'host'			=> $vpip,
						'X-Purge-Method'	=> 'default',
						)
			);

		if( empty( $_POST['vp_page'] ) ) {
			add_action( 'admin_notices', 'vp_url_req' );
			return;
		} else {
//			$res = curl_init( $page );
//			curl_setopt( $res, CURLOPT_CUSTOMREQUEST, 'PURGE' );
//			curl_exec( $res );
//			curl_close( $res );


			$res = wp_remote_request( $page, array( 'method' => 'PURGE', 'headers' => array( 'host' => $vpip ) ) );
			print_r( $res );
			if ( ! is_wp_error( $res ) ) {
				if( $res['response']['code'] == '404' ) {
					add_action( 'admin_notices', 'vp_404' );
					return;
				} else if ( $res['response']['code'] == '405' ) {
					add_action( 'admin_notices', 'vp_405' );
					return;
				} else if ( $res['response']['code'] == '200' ) {
					add_action( 'admin_notices', 'vp_200' );
					return;
				} else {
					add_action( 'admin_notices', 'vp_general_error' );
				}
			} else {
				add_action( 'admin_notices', 'vp_url_req' );
			}
		}
	}
}


function vp_main_menu() {

	echo '<form action="admin.php?page=varnish-purger" method="POST">
		<input type="text" name="vp_page" placeholder="Enter URL" />
		<input type="hidden" name="vp_purge" value="true" />
		<input type="submit" name="vp_submit" value="Purge Now" />
		</form>';	

}
