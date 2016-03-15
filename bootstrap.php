<?php
$container = new tad_DI52_Container();

$container->register( 'FTB_ServiceProviders_TemplateOperations' );
//$container->register('FTB_ServiceProviders_PreviewFilters');
$container->register( 'FTB_ServiceProviders_UIControls' );

return $container;
