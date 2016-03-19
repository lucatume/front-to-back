<?php


class FTB_Customizer_ControlsConfig implements FTB_Customizer_ControlsConfigInterface {

	/**
	 * @var FTB_Adapters_WPInterface
	 */
	protected $wp;

	/**
	 * @var string
	 */
	protected $option_name;

	/**
	 * @var FTB_Fields_ConfigDumperInterface
	 */
	protected $config_dumper;

	/**
	 * @var array
	 */
	protected $option;

	/**
	 * @var bool
	 */
	protected $option_fetched = false;


	public function __construct( FTB_Adapters_WPInterface $wp, FTB_Fields_ConfigDumperInterface $config_dumper, $option_name = 'ftb-configuration' ) {
		$this->wp            = $wp;
		$this->option_name   = $option_name;
		$this->config_dumper = $config_dumper;
		$this->option        = $this->config_dumper->empty_config();
	}

	public function get_panels() {
		$this->init_option();

		return isset( $this->option['panels'] ) ? $this->option['panels'] : array();
	}

	public function get_sections() {
		$this->init_option();

		return isset( $this->option['sections'] ) ? $this->option['sections'] : array();
	}

	public function get_fields() {
		$this->init_option();

		return isset( $this->option['fields'] ) ? $this->option['fields'] : array();
	}

	private function init_option() {
		if ( $this->option_fetched === false ) {
			$this->option         = $this->wp->get_json_decoded_option( $this->option_name, $this->config_dumper->empty_config() );
			$this->option_fetched = true;
		}
	}

	public function get_page_slugs() {
		$this->init_option();

		return isset( $this->option['page_slugs'] ) ? $this->option['page_slugs'] : array();
	}
}