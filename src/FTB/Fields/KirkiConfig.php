<?php


class FTB_Fields_KirkiConfig implements FTB_Fields_ConfigInterface {

	/**
	 * @var string
	 */
	protected $page_slug;

	/**
	 * @var string
	 */
	protected $config_id;
	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $section;
	/**
	 * @var FTB_Locators_PageInterface
	 */
	protected $page_locator;

	public function __construct( $prefix = 'ftb-page-', $config_id = 'front-to-back', FTB_Locators_PageInterface $page_locator ) {
		$this->config_id    = $config_id;
		$this->prefix       = $prefix;
		$this->page_locator = $page_locator;
	}

	public function add_field( array $field_config ) {
		array_walk( $field_config, array( $this, 'normalize_config_entry' ) );
		$field_config = array_merge( $this->defaults(), $field_config );
		Kirki::add_field( $this->config_id, $field_config );
	}

	protected function normalize_config_entry( &$value, $key ) {
		$method = 'normalize_' . $key;
		if ( ! method_exists( $this, $method ) ) {
			return;
		}
		$value = $this->{$method}( $value );
	}

	protected function normalize_settings( $value ) {
		return $this->prefix() . '-' . $value;
	}

	protected function defaults() {
		return array(
			'section' => $this->section_id(),
		);
	}

	/**
	 * @return string
	 */
	protected function section_id() {
		return $this->prefix() . '-section-content';
	}

	/**
	 * @return string
	 */
	protected function prefix() {
		return $this->prefix . '-' . $this->page_slug;
	}

	/**
	 * @param string $page_slug
	 */
	public function add_content_section( $page_slug ) {

		$this->page_slug = $page_slug;

		$section_args = array(
			'title'           => _x( 'Content', 'The section title in the Theme Customizer', 'ftb' ),
			'active_callback' => array( $this->page_locator, 'is_' . $this->page_slug ),
			'priority'        => 150,
		);
		Kirki::add_section( $this->section_id(), $section_args );
	}
}