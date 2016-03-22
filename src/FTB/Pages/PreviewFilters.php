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

	/**
	 * @var array
	 */
	protected $page_filters = array();

	public function __construct( tad_DI52_Container $container, FTB_Customizer_ControlsConfigInterface $controls_config ) {
		$this->container       = $container;
		$this->controls_config = $controls_config;
	}

	public function add_preview_filters() {
		$page_slugs = $this->controls_config->get_page_slugs();

		array_walk( $page_slugs, array( $this, 'add_page_preview_filters' ) );
	}

	public function add_save_filters() {
		$page_slugs = $this->controls_config->get_page_slugs();

		array_walk( $page_slugs, array( $this, 'add_page_save_filters' ) );
	}

	public function get_page_filters() {
		return $this->page_filters;
	}

	private function add_page_preview_filters( $page_slug ) {
		$filter = $this->get_filter_for_page_slug( $page_slug );

		add_filter( 'the_title', array( $filter, 'filter_the_title' ), 0, 2 );
		add_filter( 'the_excerpt', array( $filter, 'filter_the_excerpt' ), 0, 2 );
		add_filter( 'the_content', array( $filter, 'filter_the_content' ), 0, 2 );
		add_filter( 'get_post_metadata', array( $filter, 'filter_get_post_metadata' ), 0, 3 );

	}

	private function add_page_save_filters( $page_slug ) {
		$filter = $this->get_filter_for_page_slug( $page_slug );

		add_action( 'customize_save_after', array( $filter, 'on_customize_save_after' ), 10, 1 );
	}

	/**
	 * @param $page_slug
	 *
	 * @return mixed|object
	 */
	private function get_filter_for_page_slug( $page_slug ) {
		if ( ! isset( $this->page_filters[ $page_slug ] ) ) {
			$filter = $this->container->make( 'FTB_Pages_FiltersInterface' );
			$filter->set_page_slug( $page_slug );
			$filter->set_page_name( str_replace( '_', '-', $page_slug ) );

			$this->page_filters[ $page_slug ] = $filter;
		}

		return $this->page_filters[ $page_slug ];
	}
}
