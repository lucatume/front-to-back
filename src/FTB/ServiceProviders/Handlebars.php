<?php


class FTB_ServiceProviders_Handlebars extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$templates_base_dir = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/templates/';
		$handlebars         = new Handlebars_Engine( array(
			'loader' => new Handlebars_Loader_FilesystemLoader( $templates_base_dir ),
		) );

		$this->container->bind( 'Handlebars_Engine', $handlebars );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}