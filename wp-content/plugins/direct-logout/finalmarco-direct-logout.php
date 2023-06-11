<?php
/**
 * Plugin Name:       Direct Logout
 * Plugin URI:        https://www.finalmarco.com/plugin-woocommerce-direct-logout/
 * Description:       Woocommerce Logout without confirimation 
 * Version:           1.1.0
 * Requires PHP:      7.0
 * Author:            Finalmarco
 * Author URI:        https://finalmarco.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// for settings
if ( !class_exists( 'RationalOptionPages' ) ) {
	require_once('settings/RationalOptionPages.php');
}

require_once plugin_dir_path(__FILE__) . 'includes/logout-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/logout-functions-adm.php';



