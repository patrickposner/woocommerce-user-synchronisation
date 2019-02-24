<?php

namespace wus;

/**
 * Class to handle the user meta
 */
class WUS_User {
	/**
	 * Get an instance of WUS_User
	 *
	 * @return void
	 */
	public static function get_instance() {
		new WUS_User();
	}

	/**
	 * Constructor for WUS_User
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'add_user_transfer_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_user_transfer_fields' ) );
		add_action( 'user_profile_update_errors', array( $this, 'validate_user_transfer_fields' ), 10, 3 );
		add_action( 'personal_options_update', array( $this, 'update_user_transfer_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'update_user_transfer_fields' ) );

		/* add user meta column in admin area */
		add_filter( 'manage_users_columns', array( $this, 'add_transfer_table' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'add_transfer_table_row' ), 10, 3 );

		/* add bulk action to user meta */
		add_filter( 'bulk_actions-users', array( $this, 'register_transfer_action' ) );
		add_filter( 'handle_bulk_actions-users', array( $this, 'transfer_action_handler' ), 10, 3 );

		/* make it hookable */
		do_action( 'wus_user', $this );
	}

	/**
	 * Add user meta fields
	 *
	 * @param object $user the current user object.
	 * @return void
	 */
	public function add_user_transfer_fields( $user ) {
		$transfer = get_user_meta( $user->ID, 'wus_transfer_user', true );

		if ( ! isset( $transfer ) || empty( $transfer ) ) {
			$transfer = 'no';
		}

		?>
		<h3><?php esc_html_e( 'WooCommerce User Synchronisation', 'woocommerce-user-synchronisation' ); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="transfer_user"><?php esc_html_e( 'Transfer this user', 'woocommerce-user-synchronisation' ); ?></label></th>
				<td>
					<select id="transfer_user" name="transfer_user">
						<option value="no" <?php if ( 'no' === $transfer ) { echo 'selected'; } ?>><?php _e( 'No', 'woocommerce-user-synchronisation' ); ?></option>
						<option value="yes" <?php if ( 'yes' === $transfer ) { echo 'selected'; } ?>><?php _e( 'Yes', 'woocommerce-user-synchronisation' ); ?></option>
					</select> 
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Validate user meta fields
	 *
	 * @param array  $errors potenial errors.
	 * @param array  $update the data to update.
	 * @param object $user the current user object.
	 * @return void
	 */
	public function validate_user_transfer_fields( $errors, $update, $user ) {
		if ( ! $update ) {
			return;
		}

		if ( empty( $_POST['transfer_user'] ) ) {
			$errors->add( 'transfer_user_error', __( '<strong>ERROR</strong>: Please decide wether or not the current user is transferable', 'woocommerce-user-synchronisation' ) );
		}
	}

	/**
	 * Udate user meta fields
	 *
	 * @param int $user_id
	 * @return void
	 */
	public function update_user_transfer_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['transfer_user'] ) ) {
			update_user_meta( $user_id, 'wus_transfer_user', $_POST['transfer_user'] );
		}
	}
	/**
	 * Add transfer meta to table
	 *
	 * @param array $column admin columns for users.
	 * @return array
	 */
	public function add_transfer_table( $column ) {
		$column['user_transfer'] = __( 'User Transfer', 'woocommerce-user-synchronisation' );
		return $column;
	}
	/**
	 * Add transfer meta as row in user meta
	 *
	 * @param  string $val current value.
	 * @param  string $column_name current column name.
	 * @param  int    $user_id current user id.
	 * @return string
	 */
	public function add_transfer_table_row( $val, $column_name, $user_id ) {
		if ( 'user_transfer' === $column_name ) {
			$transfer = get_user_meta( $user_id, 'wus_transfer_user', true );

			switch ( $transfer ) {
				case '':
					$transfer = __( 'No', 'woocommerce-user-synchronisation' );
					break;
				case 'no':
					$transfer = __( 'No', 'woocommerce-user-synchronisation' );
					break;
				case 'yes':
					$transfer = __( 'Yes', 'woocommerce-user-synchronisation' );
					break;
			}
			return $transfer;
		}
		return $val;
	}

	/**
	 * Add user transfer bulk action
	 *
	 * @param array $bulk_actions the array of bulk actions.
	 * @return array
	 */
	public function register_transfer_action( $bulk_actions ) {
		$bulk_actions['user_transfer'] = __( 'Set / Unset Transfer', 'woocommerce-user-synchronisation' );
		return $bulk_actions;
	}

	/**
	 * Handling user transfer action
	 *
	 * @param string $redirect_to redirect url.
	 * @param array  $doaction all actions.
	 * @param array  $user_ids array of user ids.
	 * @return string
	 */
	public function transfer_action_handler( $redirect_to, $doaction, $user_ids ) {
		if ( 'user_transfer' === $doaction ) {
			foreach ( $user_ids as $user_id ) {
				$transfer = get_user_meta( $user_id, 'wus_transfer_user', true );

				switch ( $transfer ) {
					case '':
						update_user_meta( $user_id, 'wus_transfer_user', 'yes' );
						break;
					case 'no':
						update_user_meta( $user_id, 'wus_transfer_user', 'yes' );
						break;
					case 'yes':
						update_user_meta( $user_id, 'wus_transfer_user', 'no' );
						break;
				}
			}
		}
		return $redirect_to;
	}


}
