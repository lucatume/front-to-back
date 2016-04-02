<?php


use SebastianBergmann\Comparator\DOMNodeComparatorTest;

class FTB_Templates_Reader implements FTB_Templates_ReaderInterface {

	/**
	 * @var string
	 */
	protected $page_slug;

	/**
	 * @var string
	 */
	protected $template_contents;

	/**
	 * @var DOMDocument
	 */
	protected $doc;
	/**
	 * @var DOMNodeList[]
	 */
	protected $ftb_elements = array();

	/**
	 * @var FTB_Nodes_ProcessorFactory
	 */
	protected $nodes_processor_factory;

	/**
	 * @var bool
	 */
	protected $found_supported_elements;

	/**
	 * @var string[]
	 */
	protected $template_lines;

	/**
	 * @var string
	 */
	protected $template_name;

	/**
	 * @var FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $current_section;

	/**
	 * @var FTB_Templates_PreprocessorInterface
	 */
	protected $preprocessor;
	/**
	 * @var FTB_Templates_PostprocessorInterface
	 */
	private $postprocessor;

	public function __construct( FTB_Nodes_ProcessorFactory $nodes_processor_factory,
		FTB_Fields_ConfigDumperInterface $config,
		FTB_Templates_PreprocessorInterface $preprocessor,
		FTB_Templates_PostprocessorInterface $postprocessor,
		$template_contents = '' ) {
		$this->nodes_processor_factory = $nodes_processor_factory;
		$this->config                  = $config;
		$this->preprocessor            = $preprocessor;
		$this->postprocessor = $postprocessor;
		$this->template_contents       = $template_contents;
	}

	public function set_template_contents( $template_contents ) {
		$this->template_contents = $template_contents;
	}

	/**
	 * @param $template_name
	 *
	 * @return mixed|string
	 */
	public function read_and_process( $template_name ) {
		$this->template_name = $template_name;
		$this->page_slug     = str_replace( '-', '_', $this->template_name );
		$this->doc           = new DOMDocument();

		$this->preprocessor->preprocess($this->template_contents);

		$this->doc->loadXML( $this->template_contents );

		$this->ftb_elements = array();

		array_map( array( $this, 'parse_supported_elements' ), $this->supported_element_tags() );

		$exit_markup = $this->template_contents;

		if ( ! empty( $this->found_supported_elements ) ) {
			$this->config->add_page_slug( $this->page_slug );
			$this->config->add_content_section( $this->page_slug );
			$this->current_section = $this->config->get_section_id( $this->page_slug );
			array_walk( $this->ftb_elements, array( $this, 'replace_supported_elements' ) );

			$exit_markup = $this->doc->saveHTML();
		}

		$this->postprocessor->postprocess($exit_markup);

		return $exit_markup;
	}

	private function supported_element_tags() {
		return array(
			'ftb-title',
			'ftb-excerpt',
			'ftb-content',
			'ftb-featured-image',
			'ftb-meta',
		);
	}

	private function parse_supported_elements( $supported_element_tag ) {
		$index                          = str_replace( 'ftb-', '', $supported_element_tag );
		$found                          = $this->doc->getElementsByTagName( $supported_element_tag );
		$this->ftb_elements[ $index ]   = $found;
		$this->found_supported_elements = $this->found_supported_elements || $found->length > 0;
	}

	/**
	 * @param DOMNodeList $value
	 * @param string      $type
	 */
	private function replace_supported_elements( $value, $type ) {
		$nodes = new FTB_Utils_DOMNodeRecursiveIterator( $value );


		/** @var DOMNode $node */
		foreach ( $nodes as $node ) {
			/** @var FTB_Nodes_ProcessorInterface $node_processor */
			$node_processor = $this->nodes_processor_factory->make_for_type( $type, $node );
			$node_processor->set_page_slug( $this->page_slug );
			$node_processor->set_section( $this->current_section );
			$processed_string = $node_processor->process();

			$parent = $node->parentNode;

			$new = $this->doc->createTextNode( $processed_string );
			$parent->replaceChild( $new, $node );
		}
	}
}