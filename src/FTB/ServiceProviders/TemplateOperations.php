<?php


class FTB_ServiceProviders_TemplateOperations extends tad_DI52_ServiceProvider{

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$this->container->bind('FTB_Locators_PageInterface',  new FTB_LocatorsPage());
		$this->container->singleton('FTB_Adapters_WP', new FTB_Adapters_WP() );
		$this->container->bind('FTB_PagesFiltersInterface', 'FTB_PagesFiltersInterface');
		
		$about_us_page = new FTB_Pages_Filters( $wp, $page_locator );
//		$about_us_page->set_page_slug( 'about_us' );
//		$about_us_page->set_page_name( 'about-us' );


		$config            = new FTB_Fields_KirkiConfig( 'ftb-page', 'about_us', $config_id, new  FTB_Locators_Page() );
		$processor_factory = new FTB_Nodes_ProcessorFactory( new FTB_Output_TemplateTags(), $config );
		$template_contents = file_get_contents( ftb_template_path( 'about-us' ) );
		$template_reader   = new FTB_Templates_Reader( $processor_factory, $template_contents );
		$output            = $template_reader->read_and_process();
		file_put_contents( get_stylesheet_directory() . '/page-about-us.php', $output );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}