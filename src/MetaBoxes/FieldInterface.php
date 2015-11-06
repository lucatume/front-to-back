<?php

namespace tad\FrontToBack\MetaBoxes;

interface FieldInterface {

	/**
	 * Field constructor.
	 *
	 * @param string $type
	 * @param string $id
	 * @param string $name
	 */
	public function __construct( $type, $id, $name, array $args = array() );

	public function get_name();

	public function get_description();

	public function get_id();

	public function get_type();
}