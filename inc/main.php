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

function vp_scripts() {
	wp_enqueue_style( 'vp-styles', VP_BASE_URL . 'inc/style.css' );
}
add_action( 'admin_init', 'vp_scripts' );

function vp_main_menu() {

	echo '<div class="vp-purge-page"><form name="varnish-purge" action="admin.php?page=varnish-cache-purger" method="POST">
		<input type="text" name="vp_page" placeholder="Enter URL" />
		<input type="hidden" name="vp_purge" value="page" />
		<input type="submit" name="vp_submit" value="Purge Now" />
		</form></div>';

	echo '<div class="vp-purge-all"><form name="varnish-purge-all" action="admin.php?page=varnish-cache-purger" method="POST">
		<input type="hidden" name="vp_purge" value="all" />
		<input type="submit" name="vp_submit" value="Purge All" />
		</form></div>';

	echo '<div class="vp-purge-info"><p>Varnish is an advanced caching system that increases your site performance. Sometimes changes to your site may not show immediately because you are looking at a cached version. You can either wait for the object to be re-cached or you can use this plugin to clear your cache.</p><p>To use the "Purge Now" function, please use the full url to the file you want to clear. For example, if you need to clear this plugins stylesheet, you would enter: <br /> <code>' . VP_BASE_URL . 'inc/style.css</code></p><p>It is recommended that you use the "Purge All" function with caution, because it can take some time to rebuild your cache.</div>';

}
