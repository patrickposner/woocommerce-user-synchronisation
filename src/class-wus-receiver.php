<?php

namespace wus;

/**
 * Class to handle the synchronize process
 */
class WUS_Receiver {
	/**
	 * Get an instance of WUS_Receiver
	 *
	 * @return void
	 */
	public static function get_instance() {
		new WUS_Receiver();
	}

	/**
	 * Constructor for WUS_Receiver
	 */
	public function __construct() {
		\WP_Route::post( 'api', 'ut\WUS_Receiver::receive_request' );

	}

	/**
	 * Listen to $_POST requests on /api/
	 *
	 * @return void
	 */
	public static function receive_request() {
		// extract data
	}

	/**
	 * Handle the user import with extracted data
	 *
	 * @param array $user_data array with the user data.
	 * @return void
	 */
	public function import_user( $user_data ) {

	}

}
