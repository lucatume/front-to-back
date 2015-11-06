<?php

namespace tad\FrontToBack\Templates;


use tad\FrontToBack\MetaBoxes\Field;
use tad\FrontToBack\MetaBoxes\FieldInterface;

class PageTemplate implements TemplateInterface {

	/**
	 * PageTemplate constructor.
	 */
	public function __construct() {
	}

	/**
	 * Whether the template for the page exists at all.
	 *
	 * @return bool
	 */
	public function exists() {
		return false;
	}

	/**
	 * Whether the template for the page defines meta fields.
	 *
	 * @return bool
	 */
	public function has_fields() {
		return false;
	}

	/**
	 * Return the template name.
	 *
	 * E.g.: "foo-bar.php" will return "foo-bar"
	 *
	 * @return string
	 */
	public function get_name() {
		return '';
	}

	/**
	 * Returns the field definitions found on the page.
	 *
	 * @return FieldInterface[]
	 */
	public function get_fields() {
		return array();
	}
}