<?php


interface FTB_Nodes_DOMNodeInterface {

	public function nodeValue();

	public function attr( $key, $default = '' );
}