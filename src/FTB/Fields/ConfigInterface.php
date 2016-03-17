<?php


interface FTB_Fields_ConfigInterface {

	public function add_field( array $field_config );

	/**
	 * @param string $page_slug
	 */
	public function add_content_section( $page_slug );
}