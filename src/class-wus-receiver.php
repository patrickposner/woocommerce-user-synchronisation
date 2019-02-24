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
		\WP_Route::post( 'wc-user-transfer', 'wus\WUS_Receiver::receive_request' );

	}

	/**
	 * Listen to $_POST requests on /wc-user-transfer/
	 *
	 * @return void
	 */
	public static function receive_request() {
		if ( isset( $_POST['wc-tranfered-users'] ) && ! empty( $_POST['wc-tranfered-users'] ) ) {
			self::import_user( $_POST['wc-tranfered-users'] );
		}
	}

	/**
	 * Handle the user import with extracted data
	 *
	 * @param array $user_data array with the user data.
	 * @return void
	 */
	public static function import_user( $users ) {

		$number_of_users = intval( get_option( 'wc_settings_tab_user_synchronisation_batch_users' ) );

		for ( $count = 0; $count < $number_of_users; $count++ ) {

			foreach ( $users as $user ) {

				$old_user = get_user_by( 'email', $user->user_email );

				if ( false === $old_user ) {
					/*create user and update meta */
					$userdata = array(
						'user_login'    => $user['user_login'],
						'user_pass'     => null,
						'user_nicename' => $user['nice_name'],
						'user_email'    => $user['user_mail'],
						'display_name'  => $user['display_name'],
					);

					$user_id  = wp_insert_user( $userdata );
					$user_obj = new \WP_User( $user_id );
					$user_obj->set_role( 'customer' );

					update_user_meta( $user_id, 'billing_first_name', $user['billing_first_name'] );
					update_user_meta( $user_id, 'billing_last_name', $user['billing_last_name'] );
					update_user_meta( $user_id, 'billing_company', $user['billing_company'] );
					update_user_meta( $user_id, 'billing_address_1', $user['billing_address_1'] );
					update_user_meta( $user_id, 'billing_address_2', $user['billing_address_2'] );
					update_user_meta( $user_id, 'billing_city', $user['billing_city'] );
					update_user_meta( $user_id, 'billing_postcode', $user['billing_postcode'] );
					update_user_meta( $user_id, 'billing_country', $user['billing_country'] );
					update_user_meta( $user_id, 'billing_state', $user['billing_state'] );
					update_user_meta( $user_id, 'billing_phone', $user['billing_phone'] );
					update_user_meta( $user_id, 'billing_email', $user['billing_email'] );
					update_user_meta( $user_id, 'shipping_first_name', $user['shipping_first_name'] );
					update_user_meta( $user_id, 'shipping_last_name', $user['shipping_last_name'] );
					update_user_meta( $user_id, 'shipping_company', $user['shipping_company'] );
					update_user_meta( $user_id, 'shipping_address_1', $user['shipping_address_1'] );
					update_user_meta( $user_id, 'shipping_address_2', $user['shipping_address_2'] );
					update_user_meta( $user_id, 'shipping_city', $user['shipping_city'] );
					update_user_meta( $user_id, 'shipping_postcode', $user['shipping_postcode'] );
					update_user_meta( $user_id, 'shipping_country', $user['shipping_country'] );
					update_user_meta( $user_id, 'shipping_state', $user['shipping_state'] );
					update_user_meta( $user_id, 'wus_transfer_user', 'yes' );

				} else {
					/* update user meta only */
					$user_id = $old_user->ID;

					update_user_meta( $user_id, 'billing_first_name', $user['billing_first_name'] );
					update_user_meta( $user_id, 'billing_last_name', $user['billing_last_name'] );
					update_user_meta( $user_id, 'billing_company', $user['billing_company'] );
					update_user_meta( $user_id, 'billing_address_1', $user['billing_address_1'] );
					update_user_meta( $user_id, 'billing_address_2', $user['billing_address_2'] );
					update_user_meta( $user_id, 'billing_city', $user['billing_city'] );
					update_user_meta( $user_id, 'billing_postcode', $user['billing_postcode'] );
					update_user_meta( $user_id, 'billing_country', $user['billing_country'] );
					update_user_meta( $user_id, 'billing_state', $user['billing_state'] );
					update_user_meta( $user_id, 'billing_phone', $user['billing_phone'] );
					update_user_meta( $user_id, 'billing_email', $user['billing_email'] );
					update_user_meta( $user_id, 'shipping_first_name', $user['shipping_first_name'] );
					update_user_meta( $user_id, 'shipping_last_name', $user['shipping_last_name'] );
					update_user_meta( $user_id, 'shipping_company', $user['shipping_company'] );
					update_user_meta( $user_id, 'shipping_address_1', $user['shipping_address_1'] );
					update_user_meta( $user_id, 'shipping_address_2', $user['shipping_address_2'] );
					update_user_meta( $user_id, 'shipping_city', $user['shipping_city'] );
					update_user_meta( $user_id, 'shipping_postcode', $user['shipping_postcode'] );
					update_user_meta( $user_id, 'shipping_country', $user['shipping_country'] );
					update_user_meta( $user_id, 'shipping_state', $user['shipping_state'] );
					update_user_meta( $user_id, 'wus_transfer_user', 'yes' );
				}
				global $wpdb;

				$wpdb->query( 
					$wpdb->prepare( "UPDATE {$wpdb->prefix}users SET user_pass = %s WHERE user_email LIKE %s", $user['password'], $user['user_mail'] )
				);
			}
		}
	}
}
