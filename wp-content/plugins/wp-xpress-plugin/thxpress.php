<?php
/*
* Plugin Name: XPress
* Version: 1.1.3 Patch Level 4
* Author: ThemeHouse
* Author URI: https://www.themehouse.com/
* Text Domain: thxpress
* Description: An integration to bring the best blogging/content management system to XenForo and the best forum software to WordPress.
*/

define('THXPRESS_PLUGIN_DIR', dirname(__FILE__));

require_once THXPRESS_PLUGIN_DIR . '/src/XPress.php';
/** @noinspection PhpUnhandledExceptionInspection */
\XPress::start(THXPRESS_PLUGIN_DIR);