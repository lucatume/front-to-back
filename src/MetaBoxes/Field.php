<?php

namespace tad\FrontToBack\MetaBoxes;


class Field implements FieldInterface {

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var string
	 */
	private $name;

	/**
	 * Field constructor.
	 *
	 * @param string $type
	 * @param string $id
	 * @param string $name
	 */
	public function __construct( $type, $id, $name, array $args = array() ) {
		$this->type        = $type;
		$this->id          = $id;
		$this->name        = $name;
		$this->description = empty( $args['description'] ) ? '' : $args['description'];
	}

	public function get_name() {
		return $this->name;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return $this->type;
	}
}