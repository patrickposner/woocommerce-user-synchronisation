<?php

namespace wus;

/**
 * Class to handle the admin area of user transfer
 */
class WUS_Admin {
	/**
	 * Get an instance of WUS_Admin
	 *
	 * @return void
	 */
	public static function get_instance() {
		new WUS_Admin();
	}

	/**
	 * Constructor for WUS_Admin
	 */
	public function __construct() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_settings_tab_user_synchronisation', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_settings_tab_user_synchronisation', array( $this, 'update_settings' ) );
		do_action( 'wus_admin', $this );
	}
	/**
	 * Add a settings tab to woocommerce admin page
	 *
	 * @param array $settings_tabs the settings array.
	 * @return array
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs['settings_tab_user_synchronisation'] = __( 'User Synchronisation', 'woocommerce-user-synchronisation' );
		return $settings_tabs;
	}
	/**
	 * Add settings to the registered settings tab
	 *
	 * @return void
	 */
	public function settings_tab() {
		woocommerce_admin_fields( $this->get_settings() );
	}
	/**
	 * Getter for settings
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = array(
			'section_transfer' => array(
				'name' => __( 'Transfer Settings', 'woocommerce-user-synchronisation' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_tab_user_synchronisation_transfer_title',
			),
			'tranfer_url' => array(
				'name'     => __( 'Transfer URL', 'woocommerce-user-synchronisation' ),
				'type'     => 'url',
				'desc_tip' => __( 'The URL where you want to transfer the users', 'woocommerce-user-synchronisation' ),
				'id'       => 'wc_settings_tab_user_synchronisation_transfer_url',
			),
			'batch_users' => array(
				'name'     => __( 'Number of users per batch', 'woocommerce-user-synchronisation' ),
				'type'     => 'number',
				'desc_tip' => __( 'Number of users prepared for the transfer', 'woocommerce-user-synchronisation' ),
				'id'       => 'wc_settings_tab_user_synchronisation_batch_users',
			),
			'transfer_type' => array(
				'type'     => 'select',
				'id'       => 'wc_settings_tab_user_synchronisation_transfer_type',
				'name'     => __( 'Transfer Type', 'woocommerce-user-synchronisation' ),
				'options'  => array(
					'sender'   => __( 'Sender', 'woocommerce-user-synchronisation' ),
					'receiver' => __( 'Receiver', 'woocommerce-user-synchronisation' ),
				),
				'class'    => 'wc-enhanced-select',
				'desc_tip' => __( 'Is the current page the sender or the receiver of the user transfer?', 'woocommerce-user-synchronisation' ),
				'default'  => 'sender',
			),
			'section_transfer_end' => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_user_synchronisation_transfer_end',
			),
		);
		return apply_filters( 'wc_settings_tab_user_synchronisation_settings', $settings );
	}
	/**
	 * Handles the save process for all settings
	 *
	 * @return void
	 */
	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}

}
