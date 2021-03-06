<?php


class FTB_Nodes_ProcessorFactory implements FTB_Nodes_ProcessorFactoryInterface {

	/**
	 * @var FTB_Output_TemplateTagsInterface
	 */
	protected $template_tags;

	/**
	 * @var FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var FTB_Fields_TransportInterface
	 */
	protected $transport;

	public function __construct( FTB_Output_TemplateTagsInterface $template_tags, FTB_Fields_ConfigDumperInterface $config, FTB_Fields_TransportInterface $transport ) {
		$this->template_tags = $template_tags;
		$this->config        = $config;
		$this->transport     = $transport;
	}

	/**
	 * @var array
	 */
	protected $supported_types = array(
		'title'          => 'FTB_Nodes_TitleProcessor',
		'excerpt'        => 'FTB_Nodes_ExcerptProcessor',
		'content'        => 'FTB_Nodes_ContentProcessor',
		'featured-image' => 'FTB_Nodes_FeaturedImageProcessor',
		'meta'           => 'FTB_Nodes_MetaProcessor',
	);

	public function make_for_type( $type, DOMNode $node ) {
		if ( ! isset( $this->supported_types[ $type ] ) ) {
			return false;
		}

		$instance = null;
		if ( is_string( $this->supported_types[ $type ] ) ) {
			$instance = new $this->supported_types[$type]( new FTB_Nodes_DOMNode( $node ), $this->template_tags, $this->config, $this->transport );
		} else {
			$instance = $this->supported_types[ $type ];
		}

		return $instance;
	}

	public function set_class_for_type( $class, $type ) {
		if ( ! in_array( 'FTB_Nodes_ProcessorInterface', class_implements( $class ) ) ) {
			throw new InvalidArgumentException( "Class or instance for type [{$type}] does not implement interface [FTB_Nodes_ProcessorInterface]" );
		}
		$this->supported_types[ $type ] = $class;
	}
}