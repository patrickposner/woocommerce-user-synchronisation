<?php

namespace ut;

/**
 * Class to handle the admin area of user transfer
 */
class UT_Admin {
	/**
	 * Get an instance of UT_Admin
	 *
	 * @return void
	 */
	public static function get_instance() {
		new RAF_Admin();
	}
}
