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
 * Utility functions
 */

function ftb_templates( $path ) {
	$path_frags = array( plugin_dir_path( __FILE__ ), 'templates', ltrim( $path, DIRECTORY_SEPARATOR ) . '.php' );

	return join( DIRECTORY_SEPARATOR, $path_frags );
}

/**
 * Read the template
 */
$page_locator  = new FTB_Locators_Page();
$wp            = new FTB_Adapters_WP();
$about_us_page = new FTB_Pages_Filters( $wp, $page_locator );
$about_us_page->set_page_slug( 'about_us' );
$about_us_page->set_page_name( 'about-us' );

$config_id = 'front-to-back-example';

Kirki::add_config( $config_id,
	array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	) );

$config            = new FTB_Fields_KirkiConfig( 'ftb-page', 'about_us', $config_id, new  FTB_Locators_Page() );
$processor_factory = new FTB_Nodes_ProcessorFactory( new FTB_Output_TemplateTags(), $config );
$template_contents = file_get_contents( ftb_templates( 'about-us' ) );
$template_reader   = new FTB_Templates_Reader( $processor_factory, $template_contents );
$template_reader->read_and_process();

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

//Kirki::add_panel( 'ftb-page-about_us-panel-customizations',
//	array(
//		'title'           => 'Page customization',
//		'active_callback' => array( $page_locator, 'is_about_us' ),
//		'priority'        => 150,
//	) );
//
//Kirki::add_section( 'ftb-page-about_us-section-content',
//	array(
//		'title' => 'Content',
//		'panel' => 'ftb-page-about_us-panel-customizations',
//	) );
//
//Kirki::add_field( $config_id,
//	array(
//		'settings' => 'ftb-page-about_us-featured_image',
//		'label'    => 'Featured image',
//		'section'  => 'ftb-page-about_us-section-content',
//		'type'     => 'image',
//	) );
//
//Kirki::add_field( $config_id,
//	array(
//		'settings' => 'ftb-page-about_us-featured_image_caption',
//		'label'    => 'Featured image caption',
//		'section'  => 'ftb-page-about_us-section-content',
//		'type'     => 'text',
//	) );
//
//Kirki::add_field( $config_id,
//	array(
//		'settings' => 'ftb-page-about_us-title',
//		'label'    => 'Title',
//		'section'  => 'ftb-page-about_us-section-content',
//		'type'     => 'text',
//		'default'  => 'About us',
//	) );
//
//Kirki::add_field( $config_id,
//	array(
//		'settings' => 'ftb-page-about_us-content',
//		'label'    => 'Content',
//		'section'  => 'ftb-page-about_us-section-content',
//		'type'     => 'textarea',
//		'default'  => 'We are skilled',
//	) );

