<?php


use SebastianBergmann\Comparator\DOMNodeComparatorTest;

class FTB_Templates_Reader implements FTB_Templates_ReaderInterface {

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

	public function __construct( FTB_Nodes_ProcessorFactory $nodes_processor_factory, $template_contents = '' ) {
		$this->nodes_processor_factory = $nodes_processor_factory;
		$this->template_contents       = $template_contents;
	}

	public function set_template_contents( $template_contents ) {
		$this->template_contents = $template_contents;
	}

	public function read_and_process() {
		$this->doc = new DOMDocument();
		$this->doc->loadXML( $this->template_contents );

		$this->ftb_elements = array();

		array_map( array( $this, 'parse_supported_elements' ), $this->supported_element_tags() );

		$exit_markup = $this->template_contents;

		if ( ! empty( $this->found_supported_elements ) ) {
			array_walk( $this->ftb_elements, array( $this, 'replace_supported_elements' ) );

			$exit_markup = $this->doc->saveHTML();

			$exit_markup = str_replace( '&lt;?php', '<?php', $exit_markup );
			$exit_markup = str_replace( '?&gt;', '?>', $exit_markup );

			$exit_markup = preg_replace( "/(\\<\\?php)([^\\>]*)((?<!\\?)\\>)/um", "<?php$2?>", $exit_markup );
		}

		return $exit_markup;
	}

	private function supported_element_tags() {
		return array(
			'ftb-title',
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
			$node_processor   = $this->nodes_processor_factory->make_for_type( $type, $node );
			$processed_string = $node_processor->process();

			$parent = $node->parentNode;

			if ( ! $parent ) {
				continue;
			}

			$new = $this->doc->createTextNode( $processed_string );
			$parent->insertBefore( $new, $node );
			$parent->removeChild( $node );
		}
	}
}