<?php


class FTB_Nodes_ProcessorFactory {

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

		return new $this->supported_types[$type]( $node );
	}
}