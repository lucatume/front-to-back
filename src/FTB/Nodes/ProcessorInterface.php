<?php


interface FTB_Nodes_ProcessorInterface {

	public function process();

	public function set_node( DOMNode $node );
}