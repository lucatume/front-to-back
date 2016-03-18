<?php


class FTB_Pages_PreviewFilters implements FTB_Pages_PreviewFiltersInterface {

	/**
	 * @var FTB_Customizer_ControlsConfigInterface
	 */
	protected $controls_config;

	/**
	 * @var tad_DI52_Container
	 */
	protected $container;

	public function __construct( tad_DI52_Container $container, FTB_Customizer_ControlsConfigInterface $controls_config ) {
		$this->container       = $container;
		$this->controls_config = $controls_config;
	}

	public function add_preview_filters() {
		$page_slugs = $this->controls_config->get_page_slugs();

		array_walk( $page_slugs, array( $this, 'add_page_filters' ) );
	}

	private function add_page_filters( $page_slug ) {
		$filter = $this->container->make( 'FTB_Pages_FiltersInterface' );
		$filter->set_page_slug( $page_slug );
		$filter->set_page_name( str_replace( '_', '-', $page_slug ) );
		$filter->set_custom_fields( array(
			'_thumbnail_id' => 'featured_image',
		) );

		add_filter( 'the_title', array( $filter, 'filter_the_title' ), 0, 2 );
		add_filter( 'the_content', array( $filter, 'filter_the_content' ), 0, 2 );
		add_filter( 'get_post_metadata', array( $filter, 'filter_get_post_metadata' ), 0, 3 );

		add_action( 'customize_save_after', array( $filter, 'on_customize_save_after' ), 10, 1 );
	}
}
