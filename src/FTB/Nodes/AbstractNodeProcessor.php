<?php


class FTB_Nodes_AbstractNodeProcessor {

	/**
	 * @var DOMNode
	 */
	protected $node;

	public function __construct( DOMNode $node = null ) {
		$this->node = $node;
	}

	public function set_node( DOMNode $node ) {
		$this->node = $node;
	}
}