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
		add_action( 'woocommerce_admin_field_button', array( $this, 'add_transfer_button' ) );

		/* ajax scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'add_user_transfer_scripts' ) );

		/* make it hookable */
		do_action( 'wus_admin', $this );
	}
	/**
	 * Add ajax scripts
	 *
	 * @return void
	 */
	public function add_user_transfer_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'user-transfer-css', WUS_URL . '/assets/user-transfer' . $suffix . '.css', '1.0', true );
		wp_enqueue_script( 'user-transfer-js', WUS_URL . '/assets/user-transfer' . $suffix . '.js', array( 'jquery' ), '1.0', true );
		wp_localize_script( 'user-transfer-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
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
			'run_transfer' => array(
				'name'     => __( 'Send User Transfer', 'woocommerce-user-synchronisation' ),
				'type'     => 'button',
				'desc'     => __( 'Run the transfer with the selected settings.', 'woocommerce-user-synchronisation' ),
				'desc_tip' => true,
				'class'    => 'button-primary',
				'id'       => 'wc_settings_tab_user_synchronisation_run_transfer',
			),
			'section_transfer_end' => array(
				'type' => 'sectionend',
				'id'   => 'wc_settings_tab_user_synchronisation_transfer_end',
			),
		);
		return apply_filters( 'wc_settings_tab_user_synchronisation_settings', $settings );
	}

	/**
	 * Add custom button to woocommerce settings
	 *
	 * @param string $value the value of the button.
	 * @return void
	 */
	public function add_transfer_button( $value ) {
		$option_value = \WC_Admin_Settings::get_option( $value['id'] );
		$description  = \WC_Admin_Settings::get_field_description( $value );
		?>	
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $description['tooltip_html']; ?>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ); ?>">
				<input
						name ="<?php echo esc_attr( $value['name'] ); ?>"
						id   ="<?php echo esc_attr( $value['id'] ); ?>"
						type ="submit"
						style="<?php echo esc_attr( $value['css'] ); ?>"
						value="<?php echo esc_attr( $value['name'] ); ?>"
						class="<?php echo esc_attr( $value['class'] ); ?>"
				/>
				<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
				<?php echo $description['description']; ?>
			</td>
		</tr>
		<?php
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
