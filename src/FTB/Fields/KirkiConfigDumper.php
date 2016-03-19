<?php


class FTB_Fields_KirkiConfigDumper implements FTB_Fields_ConfigDumperInterface {

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var FTB_Locators_PageInterface
	 */
	protected $page_locator;

	/**
	 * @var array
	 */
	protected $page_slugs = array();

	/**
	 * @var FTB_Adapters_WPInterface
	 */
	private $wp;

	public static function get_empty_config() {
		return array( 'panels' => array(), 'sections' => array(), 'fields' => array(), 'page_slugs' => array() );
	}

	public function __construct( FTB_Adapters_WPInterface $wp, FTB_Locators_PageInterface $page_locator ) {
		$this->config       = self::get_empty_config();
		$this->wp           = $wp;
		$this->page_locator = $page_locator;
	}

	public function save_configuration() {
		$this->wp->save_configuration( $this->config );
	}

	public function add_panel( $panel_id, array $panel_config ) {
		Arg::_( $panel_id, 'Panel ID' )->is_string();
		Arg::_( $panel_config, 'Panel configuration' )->is_associative_array()->assert( isset( $panel_config['title'] ), 'must contain [title]' );

		$this->config['panels'][ $panel_id ] = $panel_config;
	}

	public function has_panel( $panel_id ) {
		Arg::_( $panel_id, 'Panel ID' )->is_string();

		return isset( $this->config['panels'][ $panel_id ] );
	}

	public function remove_panel( $panel_id ) {
		Arg::_( $panel_id, 'Panel ID' )->is_string();

		if ( $this->has_panel( $panel_id ) ) {
			unset( $this->config['panels'][ $panel_id ] );
			$this->config['panels'] = $this->config['panels'];
		}
	}

	public function add_section( $section_id, array $section_config ) {
		Arg::_( $section_id, 'Section ID' )->is_string();
		Arg::_( $section_config, 'Section configuration' )->is_associative_array()->assert( isset( $section_config['title'] ), 'must contain [title]' );

		$this->config['sections'][ $section_id ] = $section_config;
	}

	public function has_section( $section_id ) {
		Arg::_( $section_id, 'Section ID' )->is_string();

		return isset( $this->config['sections'][ $section_id ] );
	}

	public function remove_section( $section_id ) {
		Arg::_( $section_id, 'Section ID' )->is_string();

		if ( $this->has_section( $section_id ) ) {
			unset( $this->config['sections'][ $section_id ] );
			$this->config['sections'] = $this->config['sections'];
		}
	}

	public function add_field( $field_id, array $field_config ) {
		Arg::_( $field_id, 'Field ID' )->is_string();
		Arg::_( $field_config, 'Field configuration' )
		   ->is_associative_array()
		   ->assert( isset( $field_config['settings'] ), 'must contain [settings]' )
		   ->assert( isset( $field_config['section'] ), 'must contain [section]' );

		$this->config['fields'][ $field_id ] = $field_config;
	}

	public function has_field( $field_id ) {
		Arg::_( $field_id, 'Field ID' )->is_string();

		return isset( $this->config['fields'][ $field_id ] );
	}

	public function remove_field( $field_id ) {
		Arg::_( $field_id, 'Field ID' )->is_string();

		if ( $this->has_field( $field_id ) ) {
			unset( $this->config['fields'][ $field_id ] );
			$this->config['fields'] = $this->config['fields'];
		}
	}

	public function add_content_section( $page_slug ) {
		Arg::_( $page_slug, 'Page slug' )->is_string()->assert( preg_match( '/^[A-Za-z0-9_]+$/', $page_slug ), 'should be a snake_case string' );

		$this->add_section( $this->get_section_id( $page_slug ),
			array(
				'title'           => __( 'Page Content', 'ftb' ),
				'active_callback' => 'is_' . $page_slug,
			) );
	}

	/**
	 * @param string $page_slug
	 *
	 * @param string $section_slug
	 *
	 * @return string
	 */
	public function get_section_id( $page_slug, $section_slug = 'content' ) {
		Arg::_( $page_slug, 'Page slug' )->is_string();
		Arg::_( $section_slug, 'Section slug' )->is_string();

		return 'ftb-page-' . $page_slug . '-section-' . $section_slug;
	}

	public function add_page_slug( $page_slug ) {
		$this->config['page_slugs'][] = $page_slug;
	}

	public function empty_config() {
		return self::get_empty_config();
	}
}