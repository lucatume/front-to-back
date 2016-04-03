<?php
// This is global bootstrap for autoloading

use tad\FunctionMocker\FunctionMocker;
use Codeception\Util\Autoload;

FunctionMocker::init();
Autoload::addNamespace( 'FTB\Test', codecept_data_dir( 'classes' ) );

// utility functions for tests
function html_strcasecmp( $xmlStringOne, $xmlStringTwo ) {
	$pattern      = '/(\\s+)/';
	$xmlStringOne = preg_replace( $pattern, ' ', $xmlStringOne );
	$xmlStringTwo = preg_replace( $pattern, ' ', $xmlStringTwo );

	$pattern      = '/(>\\s*<)/';
	$xmlStringOne = preg_replace( $pattern, '> <', $xmlStringOne );
	$xmlStringTwo = preg_replace( $pattern, '> <', $xmlStringTwo );

	return strcasecmp( trim( $xmlStringOne ), trim( $xmlStringTwo ) ) === 0 ? true : false;
}
