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
include 'vendor/aristath/kirki/kirki.php';

/**
 * Utility functions
 */

function ftb_templates( $path ) {
	$path_frags = array( get_stylesheet_directory(), 'ftb-templates', ltrim( $path, DIRECTORY_SEPARATOR ) . '.php' );

	return join( DIRECTORY_SEPARATOR, $path_frags );
}

$config_id = 'front-to-back-example';

Kirki::add_config( $config_id,
	array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	) );

$page_locator  = new FTB_Locators_Page();
$wp            = new FTB_Adapters_WP();
$about_us_page = new FTB_Pages_Filters( $wp, $page_locator );
$about_us_page->set_page_slug( 'about_us' );
$about_us_page->set_page_name( 'about-us' );
	 

$config            = new FTB_Fields_KirkiConfig( 'ftb-page', 'about_us', $config_id, new  FTB_Locators_Page() );
$processor_factory = new FTB_Nodes_ProcessorFactory( new FTB_Output_TemplateTags(), $config );
$template_contents = file_get_contents( ftb_templates( 'about-us' ) );
$template_reader   = new FTB_Templates_Reader( $processor_factory, $template_contents );
$output            = $template_reader->read_and_process();
file_put_contents( get_stylesheet_directory() . '/page-about-us.php', $output );

/**
 * Filter fields for the Theme Customizer preview
 */

add_action( 'customize_preview_init', 'ftb_add_preview_filters' );
function ftb_add_preview_filters() {
	$page_locator  = new FTB_Locators_Page();
	$wp            = new FTB_Adapters_WP();
	$about_us_page = new FTB_Pages_Filters( $wp, $page_locator );
	$about_us_page->set_page_slug( 'about_us' );
	$about_us_page->set_page_name( 'about-us' );
	$about_us_page->set_custom_fields( array(
		'_thumbnail_id'           => 'featured_image',
		'_featured_image_caption' => 'featured_image_caption',
	) );

	add_filter( 'the_title', array( $about_us_page, 'filter_the_title' ), 0, 2 );
	add_filter( 'the_content', array( $about_us_page, 'filter_the_content' ), 0, 2 );
	add_filter( 'get_post_metadata', array( $about_us_page, 'filter_get_post_metadata' ), 0, 3 );
}

add_action( 'customize_save_after', array( $about_us_page, 'on_customize_save_after' ), 10, 1 );
