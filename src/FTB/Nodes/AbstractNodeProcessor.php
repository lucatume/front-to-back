<?php


abstract class FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	/**
	 * @var FTB_Nodes_DOMNodeInterface
	 */
	protected $node;

	/**
	 * @var FTB_Output_TemplateTagsInterface
	 */
	protected $template_tags;
	/**
	 * @var FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $section;

	public function __construct( FTB_Nodes_DOMNodeInterface $node, FTB_Output_TemplateTagsInterface $template_tags, FTB_Fields_ConfigDumperInterface $config ) {
		$this->node          = $node;
		$this->template_tags = $template_tags;
		$this->config        = $config;
	}

	public function get_node() {
		return $this->node;
	}

	public function get_template_tags() {
		return $this->template_tags;
	}

	public function get_config() {
		return $this->config;
	}

	abstract public function process();

	public function set_section( $section ) {
		$this->section = $section;
	}
}