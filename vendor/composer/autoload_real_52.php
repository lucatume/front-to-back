<?php

// autoload_real_52.php generated by xrstf/composer-php52

class ComposerAutoloaderInitee77c720d65f06f539f8f048b5d6290a {
	private static $loader;

	public static function loadClassLoader($class) {
		if ('xrstf_Composer52_ClassLoader' === $class) {
			require dirname(__FILE__).'/ClassLoader52.php';
		}
	}

	/**
	 * @return xrstf_Composer52_ClassLoader
	 */
	public static function getLoader() {
		if (null !== self::$loader) {
			return self::$loader;
		}

		spl_autoload_register(array('ComposerAutoloaderInitee77c720d65f06f539f8f048b5d6290a', 'loadClassLoader'), true /*, true */);
		self::$loader = $loader = new xrstf_Composer52_ClassLoader();
		spl_autoload_unregister(array('ComposerAutoloaderInitee77c720d65f06f539f8f048b5d6290a', 'loadClassLoader'));

		$vendorDir = dirname(dirname(__FILE__));
		$baseDir   = dirname($vendorDir);
		$dir       = dirname(__FILE__);

		$map = require $dir.'/autoload_namespaces.php';
		foreach ($map as $namespace => $path) {
			$loader->add($namespace, $path);
		}

		$classMap = require $dir.'/autoload_classmap.php';
		if ($classMap) {
			$loader->addClassMap($classMap);
		}

		$loader->register(true);

//		require $vendorDir . '/symfony/polyfill-mbstring/bootstrap.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/guzzlehttp/psr7/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/guzzlehttp/promises/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/guzzlehttp/guzzle/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/illuminate/support/helpers.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/aristath/kirki/autoloader.php';

		return $loader;
	}
}
