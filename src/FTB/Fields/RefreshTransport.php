<?php


class FTB_Fields_RefreshTransport implements FTB_Fields_TransportInterface {

	public function add_field_args( $tag, array $field_args, FTB_Nodes_DOMNodeInterface $node ) {
		return $field_args;
	}

	public function modify_output( $tag, array $field_args, $output, FTB_Nodes_DOMNodeInterface $node ) {
		return $output;
	}
}