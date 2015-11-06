<?php

namespace tad\FrontToBack\Templates;


use tad\FrontToBack\MetaBoxes\Field;
use tad\FrontToBack\MetaBoxes\FieldInterface;

interface TemplateInterface {

	/**
	 * Whether the template for the page exists at all.
	 *
	 * @return bool
	 */
	public function exists();

	/**
	 * Whether the template for the page defines meta fields.
	 *
	 * @return bool
	 */
	public function has_fields();

	/**
	 * Returns the field definitions found on the page.
	 *
	 * @return FieldInterface[]
	 */
	public function get_fields();

	/**
	 * Return the template name.
	 *
	 * E.g.: "foo-bar.php" will return "foo-bar"
	 *
	 * @return string
	 */
	public function get_name();
}