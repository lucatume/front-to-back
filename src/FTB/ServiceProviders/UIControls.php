<?php


class FTB_ServiceProviders_UIControls extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$this->container->singleton( 'FTB_Templates_GeneratorInterface', 'FTB_Templates_Generator' );
		$this->container->bind( 'FTB_UI_GeneratorControlInterface', 'FTB_UI_GeneratorControl' );

		add_action( 'admin_bar_menu', array( $this->container->make( 'FTB_UI_GeneratorControlInterface' ), 'add_controls' ), 999, 1 );
		add_action( 'init', array( $this->container->make( 'FTB_Templates_GeneratorInterface' ), 'maybe_generate' ) );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}