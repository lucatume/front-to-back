<?php
// This is global bootstrap for autoloading

use tad\FunctionMocker\FunctionMocker;
use Codeception\Util\Autoload;

FunctionMocker::init();
Autoload::addNamespace( 'FTB\Test', codecept_data_dir( 'classes' ) );
