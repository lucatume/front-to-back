<?php
$container = new tad_DI52_Container();

$container->register( 'FTB_ServiceProviders_WPGlobals' );
$container->register( 'FTB_ServiceProviders_Handlebars' );
$container->register( 'FTB_ServiceProviders_TemplateOperations' );
$container->register( 'FTB_ServiceProviders_UIControls' );
$container->register( 'FTB_ServiceProviders_ThemeCustomizerSetup' );
$container->register( 'FTB_ServiceProviders_RestApi' );

add_action( 'plugins_loaded', array( $container, 'boot' ) );

return $container;
