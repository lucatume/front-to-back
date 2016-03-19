<?php


interface FTB_Fields_ConfigDumperInterface {

	public static function get_empty_config();

	public function save_configuration();

	public function add_panel( $panel_id, array $panel_config );

	public function has_panel( $panel_id );

	public function remove_panel( $panel_id );

	public function add_section( $section_id, array $section_config );

	public function has_section( $section_id );

	public function remove_section( $section_id );

	public function add_field( $field_id, array $field_config );

	public function has_field( $field_id );

	public function remove_field( $field_id );

	public function add_content_section( $page_slug );

	/**
	 * @param $page_slug
	 *
	 * @return string
	 */
	public function get_section_id( $page_slug, $section_slug = 'content' );

	public function add_page_slug( $page_slug );

	public function empty_config();
}