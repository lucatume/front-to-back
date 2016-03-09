<?php


class FTB_Nodes_ProcessorFactory implements FTB_Nodes_ProcessorFactoryInterface {

	/**
	 * @var array
	 */
	protected $supported_types = array(
		'title' => 'FTB_Nodes_TitleProcessor',
	);

	public function make_for_type( $type, DOMNode $node ) {
		if ( ! isset( $this->supported_types[ $type ] ) ) {
			return false;
		}

		$instance = null;
		if ( is_string( $this->supported_types[ $type ] ) ) {
			$instance = new $this->supported_types[$type]();
		} else {
			$instance = $this->supported_types[ $type ];
		}

		/** @var FTB_Nodes_ProcessorInterface $instance */
		$instance->set_node( $node );

		return $instance;
	}

	public function set_class_for_type( $class, $type ) {
		if ( ! in_array( 'FTB_Nodes_ProcessorInterface', class_implements( $class ) ) ) {
			throw new InvalidArgumentException( "Class or instance for type [{$type}] does not implement interface [FTB_Nodes_ProcessorInterface]" );
		}
		$this->supported_types[ $type ] = $class;
	}
}