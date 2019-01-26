<?php
/*
Plugin Name: User Transfer
Text Domain: user-transfer
Description: Plugin to maybe transfer users to another site
Author: patrickposner
Version: 1.0
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}
/* localize */
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'user-transfer', false, $textdomain_dir );


ut\UT_Admin::get_instance();
