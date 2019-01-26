<?php

namespace wus;

/**
 * Class to handle the synchronize process
 */
class WUS_Sender {
	/**
	 * Get an instance of WUS_Sender
	 *
	 * @return void
	 */
	public static function get_instance() {
		new WUS_Sender();
	}

	public function __construct() {
		//add_action( 'wp_head', array( $this, 'send' ) );
	}

	public function send() {

		/* make this dynamic */
		$url = 'https://rooks2.lndo.site/api';

		$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => array( 'username' => 'bob', 'password' => '1234xyz' ),
			'cookies'     => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
		} else {
			echo 'Response:<pre>';
			print_r( $response );
			echo '</pre>';
		}
	}
}
