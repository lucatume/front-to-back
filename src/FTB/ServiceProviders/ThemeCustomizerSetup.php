<?php


class FTB_ServiceProviders_ThemeCustomizerSetup extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$this->container->singleton( 'FTB_Customizer_ControlsConfigInterface', 'FTB_Customizer_ControlsConfig' );
		$this->container->singleton( 'FTB_Customizer_ControlsInterface', 'FTB_Customizer_Controls' );
		$this->container->bind( 'FTB_Pages_FiltersInterface', 'FTB_Pages_Filters' );
		$this->container->bind( 'tad_DI52_Container', $this->container );
		$this->container->singleton( 'FTB_Pages_PreviewFiltersInterface', 'FTB_Pages_PreviewFilters' );

		add_action( 'customize_register', array( $this->container->make( 'FTB_Customizer_Controls' ), 'register_controls' ) );

		$preview_filters = $this->container->make( 'FTB_Pages_PreviewFiltersInterface' );

		add_action( 'customize_preview_init', array( $preview_filters, 'add_preview_filters' ) );
		add_action( 'wp_ajax_customize_save', array( $preview_filters, 'add_save_filters' ) );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}