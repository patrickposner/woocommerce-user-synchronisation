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
	/**
	 * The Constructor of WUS_Sender
	 */
	public function __construct() {
		add_action( 'wp_ajax_send_users', array( $this, 'send_users' ) );
		add_action( 'wp_ajax_nopriv_send_users', array( $this, 'send_users' ) );
	}

	/**
	 * Sende transfer request to /wc-user-transfer/ endpoint of other page
	 *
	 * @return void
	 */
	public function send_users() {

		$url_option = get_option( 'wc_settings_tab_user_synchronisation_transfer_url' );
		$url        = $url_option . '/wc-user-transfer';

		$users = $this->get_users();

		$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => array( 'wc-tranfered-users' => $users ),
			'cookies'     => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . $error_message;
		} else {
			echo 'Response:<pre>';
			print_r( $response );
			echo '</pre>';
		}
	}

	/**
	 * Get transferable users
	 *
	 * @return array
	 */
	public function get_users() {

		$number_of_users   = intval( get_option( 'wc_settings_tab_user_synchronisation_batch_users' ) );
		$tranferable_users = array();

		for ( $count = 0; $count < $number_of_users; $count++ ) {

			$args = array(
				'role'    => 'customer',
				'orderby' => 'login',
				'order'   => 'ASC',
				'number'  => $number_of_users,
				'offset'  => $count * $number_of_users,
			);

			$users = get_users( $args );

			foreach ( $users as $user ) {

				$tranferable_user = array(
					'user_login'          => $user->user_login,
					'password'            => $user->user_pass,
					'nice_name'           => $user->user_nicename,
					'user_mail'           => $user->user_email,
					'display_name'        => $user->display_name,
					'billing_first_name'  => get_user_meta( $user->ID, 'billing_first_name', true ),
					'billing_last_name'   => get_user_meta( $user->ID, 'billing_last_name', true ),
					'billing_company'     => get_user_meta( $user->ID, 'billing_company', true ),
					'billing_address_1'   => get_user_meta( $user->ID, 'billing_address_1', true ),
					'billing_address_2'   => get_user_meta( $user->ID, 'billing_address_2', true ),
					'billing_city'        => get_user_meta( $user->ID, 'billing_city', true ),
					'billing_postcode'    => get_user_meta( $user->ID, 'billing_postcode', true ),
					'billing_country'     => get_user_meta( $user->ID, 'billing_country', true ),
					'billing_state'       => get_user_meta( $user->ID, 'billing_state', true ),
					'billing_phone'       => get_user_meta( $user->ID, 'billing_phone', true ),
					'billing_email'       => get_user_meta( $user->ID, 'billing_email', true ),
					'shipping_first_name' => get_user_meta( $user->ID, 'shipping_first_name', true ),
					'shipping_last_name'  => get_user_meta( $user->ID, 'shipping_last_name', true ),
					'shipping_company'    => get_user_meta( $user->ID, 'shipping_company', true ),
					'shipping_address_1'  => get_user_meta( $user->ID, 'shipping_address_1', true ),
					'shipping_address_2'  => get_user_meta( $user->ID, 'shipping_address_2', true ),
					'shipping_city'       => get_user_meta( $user->ID, 'shipping_city', true ),
					'shipping_postcode'   => get_user_meta( $user->ID, 'shipping_postcode', true ),
					'shipping_country'    => get_user_meta( $user->ID, 'shipping_country', true ),
					'shipping_state'      => get_user_meta( $user->ID, 'shipping_state', true ),
				);

				array_push( $tranferable_users, $tranferable_user );
			}
		}

		return $tranferable_users;

	}
}
