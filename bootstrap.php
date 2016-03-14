<?php
$container = new tad_DI52_Container();

$container['kirki-config::id'] = 'front-to-back';

Kirki::add_config( $container['kirki-config::id'],
	array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	) );

$container->register('FTB_ServiceProviders_TemplateOperations');
$container->register('FTB_ServiceProviders_PreviewFilters');

return $container;
