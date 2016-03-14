<?php


interface FTB_Pages_FiltersInterface {

	public function set_page_slug( $page_name );

	public function set_page_name( $page_slug );

	public function set_custom_fields( $custom_fields );

	public function filter_the_title( $title, $post_id = null );

	public function filter_the_content( $content, $post_id = null );

	public function filter_get_post_metadata( $value, $object_id, $meta_key );

	public function on_customize_save_after( $wp_customize );
}