<?php


interface FTB_Nodes_ProcessorFactoryInterface {

	public function make_for_type( $type, DOMNode $node );
}