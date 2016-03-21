<?php


class FTB_ServiceProviders_TemplateOperations extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$config_id = 'front-to-back';

		Kirki::add_config( $config_id,
			array(
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			) );

		$this->container->singleton( 'FTB_Fields_ConfigDumperInterface', 'FTB_Fields_KirkiConfigDumper' );
		$this->container->singleton( 'FTB_Filesystem_FilesystemInterface', 'FTB_Filesystem_DirectFilesystem' );
		$this->container->singleton( 'FTB_Fields_ConfigInterface', 'FTB_Fields_KirkiConfig' );
		$this->container->singleton( 'FTB_Templates_RepositoryInterface', 'FTB_Templates_Repository' );
		$this->container->singleton( 'FTB_Output_TemplateTagsInterface', 'FTB_Output_TemplateTags' );
		$this->container->singleton( 'FTB_Templates_ReaderInterface', 'FTB_Templates_Reader' );
		$this->container->singleton( 'FTB_Fields_TransportInterface', 'FTB_Fields_RefreshTransport' );
		$this->container->singleton( 'FTB_Nodes_ProcessorFactoryInterface', 'FTB_Nodes_ProcessorFactory' );
		$this->container->singleton( 'FTB_Locators_PageInterface', 'FTB_Locators_Page' );
		$this->container->singleton( 'FTB_Adapters_WPInterface', 'FTB_Adapters_WP' );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}