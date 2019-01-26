<?php
/*
Plugin Name: WooCommerce User Synchronisation
Text Domain: woocommerce-user-synchronisation
Description: Plugin to synchronize users between woocommerce stores
Author: patrickposner
Version: 1.0
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}
/* localize */
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'woocommerce-user-synchronisation', false, $textdomain_dir );


wus\WUS_Admin::get_instance();
wus\WUS_Receiver::get_instance();
wus\WUS_Sender::get_instance();
