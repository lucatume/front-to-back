<?php


class FTB_Nodes_TitleProcessor implements FTB_Nodes_ProcessorInterface {

	/**
	 * @var DOMNode
	 */
	private $node;

	public function __construct( DOMNode $node ) {

		$this->node = $node;
	}

	public function process() {

	}
}
