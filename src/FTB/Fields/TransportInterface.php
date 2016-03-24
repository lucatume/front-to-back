<?php


interface FTB_Fields_TransportInterface {

	public function add_field_args( $tag, array $field_args, FTB_Nodes_DOMNodeInterface $node );

	public function modify_output( $tag, array $field_args, $output, FTB_Nodes_DOMNodeInterface $node );
}