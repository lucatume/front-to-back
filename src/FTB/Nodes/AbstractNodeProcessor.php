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
	/**
	 * @var string
	 */
	protected $page_slug;
	/**
	 * @var FTB_Fields_TransportInterface
	 */
	protected $transport;

	public function __construct( FTB_Nodes_DOMNodeInterface $node, FTB_Output_TemplateTagsInterface $template_tags, FTB_Fields_ConfigDumperInterface $config, FTB_Fields_TransportInterface $transport ) {
		$this->node          = $node;
		$this->template_tags = $template_tags;
		$this->config        = $config;
		$this->transport     = $transport;
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

	public function set_page_slug( $page_slug ) {
		$this->page_slug = $page_slug;
	}
}