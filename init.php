<?php
use tad\FrontToBack\OptionsPage;
use tad\FrontToBack\Templates\Creator;
use tad\FrontToBack\Templates\Filesystem;
use tad\FrontToBack\Templates\MasterChecker;

require_once __DIR__ . '/src/functions/commons.php';

$plugin = ftb();

/**
 *  Variables
 */
$plugin->set( 'path', __DIR__ );
$plugin->set( 'url', plugins_url( '', __FILE__ ) );

$templates_extension = 'php';
$plugin->set( 'templates/extension', $templates_extension );
$plugin->set( 'templates/master-template-name', "master.{$templates_extension}" );

/**
 * Initializers
 */
$plugin->set( 'templates/default-folder', function () {
	return WP_CONTENT_DIR . '/ftb-templates';
} );

$plugin->set( 'options-page', function () {
	return new OptionsPage();
} );

$plugin->set( 'master-template-checker', function () {
	return new MasterChecker();
} );

$plugin->set( 'templates-filesystem', function () {
	$templates_folder = ftb_get_option( 'templates_folder' );
	$templates_folder = $templates_folder ?: ftb()->get( 'templates/default-folder' );

	return new Filesystem( $templates_folder );
} );

$plugin->set( 'templates-creator', function () {
	return new Creator( ftb()->get( 'templates-filesystem' ) );
} );

/**
 * Kickstart
 */
/** @var OptionsPage $optionsPage */
$optionsPage = $plugin->get( 'options-page' );
$optionsPage->hooks();

/** @var MasterChecker $masterTemplateChecker */
$masterTemplateChecker = $plugin->get( 'master-template-checker' );
$masterTemplateChecker->hooks();

/** @var Creator $templatesCreator */
$templatesCreator = $plugin->get( 'templates-creator' );
$templatesCreator->hooks();
