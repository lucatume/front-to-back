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


	public function __construct( FTB_Adapters_WPInterface $wp, FTB_Fields_ConfigDumperInterface $config_dumper, $option_name = 'ftb-configuration' ) {
		$this->wp            = $wp;
		$this->option_name   = $option_name;
		$this->config_dumper = $config_dumper;
		$this->option        = $this->config_dumper->get_empty_config();
	}

	public function get_panels() {
		$this->init_option();

		return $this->option['panels'];
	}

	public function get_sections() {
		$this->init_option();

		return $this->option['sections'];
	}

	public function get_fields() {
		$this->init_option();

		return $this->option['fields'];
	}

	private function init_option() {
		$this->option = $this->wp->get_json_decoded_option( $this->option_name, $this->config_dumper->get_empty_config() );
	}
}