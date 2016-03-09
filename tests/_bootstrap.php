<?php
// This is global bootstrap for autoloading

use tad\FunctionMocker\FunctionMocker;
use Codeception\Util\Autoload;

FunctionMocker::init();
Autoload::addNamespace( 'FTB\Test', codecept_data_dir( 'classes' ) );

// utility functions
function xml_strcasecmp( $xmlStringOne, $xmlStringTwo ) {
	$pattern      = '/(?<=\\>)[\\s\\t\\n\\r]+(?=\\<)/';
	$xmlStringOne = trim( preg_replace( $pattern, '', $xmlStringOne ) );
	$xmlStringTwo = trim( preg_replace( $pattern, '', $xmlStringTwo ) );

	$pattern      = '/[\\s\\t\\n\\r]+/';
	$xmlStringOne = trim( preg_replace( $pattern, ' ', $xmlStringOne ) );
	$xmlStringTwo = trim( preg_replace( $pattern, ' ', $xmlStringTwo ) );

	return strcasecmp( $xmlStringOne, $xmlStringTwo ) === 0 ? true : false;
}
