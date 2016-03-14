<?php


class FTB_Nodes_DOMNode implements FTB_Nodes_DOMNodeInterface {

	/**
	 * @var DOMNode
	 */
	private $node;

	/**
	 * FTB_Nodes_DOMNode constructor.
	 *
	 * @param DOMNode $node
	 */
	public function __construct( $node ) {
		$this->node = $node;
	}

	public function nodeValue() {
		return $this->node->nodeValue;
	}
}