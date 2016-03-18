<?php


class FTB_Nodes_DOMNode implements FTB_Nodes_DOMNodeInterface {

	/**
	 * @var DOMNamedNodeMap
	 */
	protected $attributes;

	/**
	 * @var DOMNode
	 */
	protected $node;

	/**
	 * @var bool
	 */
	protected $did_fetch_attributes = false;

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

	public function attr( $key, $default = '' ) {
		$this->fetch_attributes( $this->node );

		if ( empty( $this->attributes ) ) {
			return $default;
		}

		$attr = $this->attributes->getNamedItem( $key );

		if ( empty( $attr ) ) {
			return $default;
		}

		$value = $attr->nodeValue;

		return $value ? $value : $default;
	}

	/**
	 * @param $node
	 */
	private function fetch_attributes( $node ) {
		if ( ! $this->did_fetch_attributes ) {
			$this->attributes           = $node->attributes;
			$this->did_fetch_attributes = true;
		}
	}
}