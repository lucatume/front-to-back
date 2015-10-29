<?php
// This is global bootstrap for autoloading
require_once __DIR__ . '/../vendor/autoload.php';
use tad\FunctionMocker\FunctionMocker;

FunctionMocker::init();
