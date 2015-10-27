<?php
/**
 * Plugin Name: Front to Back
 * Plugin URI: http://theAverageDev.com
 * Description: Create post meta fields editing front-end templates.
 * Version: 1.0
 * Author: theAverageDev
 * Author URI: http://theAverageDev.com
 * License: GPL 2.0
 */

include 'src/functions/version_compat.php';

if ( version_compare( phpversion(), '5.3', '<' ) ) {
	add_action( 'admin_notices', 'ftb_php_version_notice' );

	return;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/webdevstudios/cmb2/init.php';
require_once __DIR__ . '/init.php';
