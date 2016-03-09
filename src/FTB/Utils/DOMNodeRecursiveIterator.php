<?php


/**
 * PHP's DOM classes are recursive but don't provide an implementation of
 * RecursiveIterator. This class provides a RecursiveIterator for looping over DOMNodeList
 */
class FTB_Utils_DOMNodeRecursiveIterator extends ArrayIterator implements RecursiveIterator {

	public function __construct( DOMNodeList $node_list ) {

		$nodes = array();
		foreach ( $node_list as $node ) {
			$nodes[] = $node;
		}

		parent::__construct( $nodes );

	}

	public function getRecursiveIterator() {
		return new RecursiveIteratorIterator( $this, RecursiveIteratorIterator::SELF_FIRST );
	}

	public function hasChildren() {
		return $this->current()->hasChildNodes();
	}


	public function getChildren() {
		return new self( $this->current()->childNodes );
	}

}
