<?php


class FTB_Fields_KirkiConfigDumper implements FTB_Fields_ConfigDumperInterface {

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var FTB_Adapters_WPInterface
	 */
	private $wp;

	public static function get_empty_config() {
		return [ 'panels' => [ ], 'sections' => [ ], 'fields' => [ ] ];
	}

	public function __construct( FTB_Adapters_WPInterface $wp ) {
		$this->config = self::get_empty_config();
		$this->wp     = $wp;
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
}