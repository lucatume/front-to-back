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

		$this->singleton( 'FTB_Fields_ConfigDumperInterface', 'FTB_Fields_KirkiConfigDumper' );
		$this->container->singleton( 'FTB_Filesystem_FilesystemInterface', 'FTB_Filesystem_DirectFilesystem' );
		$this->container->bind( 'FTB_Fields_ConfigInterface', 'FTB_Fields_KirkiConfig' );
		$this->container->bind( 'FTB_Templates_RepositoryInterface', 'FTB_Templates_Repository' );
		$this->container->bind( 'FTB_Output_TemplateTagsInterface', new FTB_Output_TemplateTags() );
		$this->container->singleton( 'FTB_Templates_ReaderInterface', 'FTB_Templates_Reader' );
		$this->container->singleton( 'FTB_Nodes_ProcessorFactoryInterface', 'FTB_Nodes_ProcessorFactory' );

		$this->container->bind( 'FTB_Locators_PageInterface', new FTB_Locators_Page() );
		$this->container->singleton( 'FTB_Adapters_WPInterface', new FTB_Adapters_WP() );
		$this->container->bind( 'FTB_Pages_FiltersInterface', 'FTB_Pages_Filters' );

	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}