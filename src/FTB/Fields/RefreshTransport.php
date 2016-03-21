<?php


class FTB_Fields_RefreshTransport implements FTB_Fields_TransportInterface {

	public function should_add_args( array $field_args ) {
		return false;
	}

	public function add_field_args( array $field_args ) {
		return $field_args;
	}
}