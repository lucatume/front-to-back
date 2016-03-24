<?php

/**
 * Plugin Name: Front to Back
 * Plugin URI: http://theAverageDev.com
 * Description: Easy page templating for developers.
 * Version: 1.0
 * Author: theAverageDev
 * Author URI: http://theAverageDev.com
 * License: GPL 2.0
 */

include 'vendor/autoload_52.php';

/**
 * Bootstrap Kirki
 */
include 'vendor/aristath/kirki/kirki.php';

//$config_id = 'postMessageConfig';
//
//Kirki::add_config( $config_id,
//	[
//		'option_type' => 'theme_mode'
//	] );
//
//Kirki::add_section( 'example-section',
//	[
//		'title' => 'example-section',
//	] );
//
//Kirki::add_field( $config_id,
//	[
//		'settings'  => 'some-setting',
//		'section'   => 'example-section',
//		'type'      => 'text',
//		'default'   => 'foo',
//		'transport' => 'postMessage',
//		'js_vars'   => [
//			[
//				'element'  => '.some-element',
//				'function' => 'html',
//			]
//		]
//	] );

include 'src/functions.php';

include 'bootstrap.php';
