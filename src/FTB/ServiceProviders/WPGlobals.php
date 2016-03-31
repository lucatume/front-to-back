<?php


class FTB_ServiceProviders_WPGlobals extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		/** @var \wpdb $wpdb */
		global $wpdb;
		$this->container->bind( 'wpdb', $wpdb );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}