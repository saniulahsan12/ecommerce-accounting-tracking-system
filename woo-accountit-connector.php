<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package Akismet
 */
/*
Plugin Name: Woo AccountIT Connector
Plugin URI: ***************
Description: This plugins sends a mail to the shop admin and the customer a mail of the ordered pdf and also pushes the data to Remote database
Version: 1.0
Author: ******
Author URI: ****************
Text Domain: woo-tracker
*/

/*
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Copyright 2017 Saniul, Inc.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	die( 'No script kiddies please!' );
}

define( 'WOO_TRACKER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MAIL_PDF_DIR', ABSPATH . 'wp-content/uploads/isbn_upload_files/' );

require_once( ISBN_READER_DIR.'/class/bootfile.class.php' );

AddFile::addFiles('/', 'helpers', 'php');

if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))):

	AddFile::addFiles('class', 'api.class', 'php');
	AddFile::addFiles('class', 'trackorder.class', 'php');
	AddFile::addFiles('views', 'settings', 'php');

	add_action( 'admin_menu', 'woo_tracker_settings' );
	function woo_tracker_settings() {
		add_menu_page( 'Woo AccountIT Connector', 'Woo AccountIT Connector', 'manage_options', 'views/settings.php', 'woo_tracker_settings_details', AddFile::addFiles('assets/images', 'icon-small', 'png', true), 100  );
	}

else:
	add_action( 'admin_notices', 'error_message' );
endif;
