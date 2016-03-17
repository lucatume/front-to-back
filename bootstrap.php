<?php
$container = new tad_DI52_Container();

$container->register( 'FTB_ServiceProviders_TemplateOperations' );
$container->register( 'FTB_ServiceProviders_UIControls' );
$container->register( 'FTB_ServiceProviders_ThemeCustomizerSetup' );

return $container;
