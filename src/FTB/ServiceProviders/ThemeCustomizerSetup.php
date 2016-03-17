<?php


class FTB_ServiceProviders_ThemeCustomizerSetup extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$this->container->singleton( 'FTB_Customizer_ControlsConfigInterface', 'FTB_Customizer_ControlsConfig' );
		$this->container->singleton( 'FTB_Customizer_ControlsInterface', 'FTB_Customizer_Controls' );
		add_action( 'customize_register', array( $this->container->make( 'FTB_Customizer_Controls' ), 'register_controls' ) );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}