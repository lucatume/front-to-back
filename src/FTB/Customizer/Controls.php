<?php


class FTB_Customizer_Controls implements FTB_Customizer_ControlsInterface {

	/**
	 * @var FTB_Customizer_ControlsConfigInterface
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $config_id;

	/**
	 * @var FTB_Locators_PageInterface
	 */
	protected $page_locator;

	public function __construct( FTB_Customizer_ControlsConfigInterface $config, FTB_Locators_PageInterface $page_locator, $config_id = 'front-to-back' ) {
		$this->config       = $config;
		$this->page_locator = $page_locator;
		$this->config_id    = $config_id;
	}

	public function register_controls() {

		$panels   = $this->config->get_panels();
		$sections = $this->config->get_sections();
		$fields   = $this->config->get_fields();

		if ( empty( $fields ) ) {
			return;
		}
	
		Kirki::add_config( $this->config_id,
			array(
				'capability'  => 'edit_theme_options',
				'option_type' => 'theme_mod',
			) );
		array_walk( $panels, array( $this, 'register_panels' ) );
		array_walk( $sections, array( $this, 'register_sections' ) );
		array_walk( $fields, array( $this, 'register_fields' ) );
	}

	private function register_panels( $value, $index ) {
		if ( isset( $value['active_callback'] ) ) {
			$value['active_callback'] = array( $this->page_locator, $value['active_callback'] );
		}
		Kirki::add_panel( $index, $value );
	}

	private function register_sections( $value, $index ) {
		if ( isset( $value['active_callback'] ) ) {
			$value['active_callback'] = array( $this->page_locator, $value['active_callback'] );
		}
		Kirki::add_section( $index, $value );
	}

	private function register_fields( $value ) {
		if ( isset( $value['active_callback'] ) ) {
			$value['active_callback'] = array( $this->page_locator, $value['active_callback'] );
		}
		Kirki::add_field( $this->config_id, $value );
	}
}