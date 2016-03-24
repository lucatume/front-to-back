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

	public function __construct( FTB_Nodes_ProcessorFactory $nodes_processor_factory, FTB_Fields_ConfigDumperInterface $config, $template_contents = '' ) {
		$this->nodes_processor_factory = $nodes_processor_factory;
		$this->config                  = $config;
		$this->template_contents       = $template_contents;
	}

	public function set_template_contents( $template_contents ) {
		$this->template_contents = $template_contents;
	}

	public function read_and_process( $template_name ) {
		$this->template_name = $template_name;
		$this->page_slug     = str_replace( '-', '_', $this->template_name );
		$this->doc           = new DOMDocument();
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

			$exit_markup = str_replace( '&lt;?php', '<?php', $exit_markup );
			$exit_markup = str_replace( '?&gt;', '?>', $exit_markup );
			$exit_markup = preg_replace( "/(\\<\\?php)([^\\>]*)((?<!\\?)\\>)/um", "<?php$2?>", $exit_markup );
			$exit_markup = str_replace( '=&gt;', '=>', $exit_markup );
			$exit_markup = str_replace( '&lt;ftb-open-tag', '<', $exit_markup );
			$exit_markup = str_replace( 'ftb-close-tag&gt;', '>', $exit_markup );
		}

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