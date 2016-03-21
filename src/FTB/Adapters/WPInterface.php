<?php


interface FTB_Adapters_WPInterface {

	public function get_attachment_id_from_url( $attachment_url );

	public function save_configuration( $config );

	public function get_json_decoded_option($name,array $default = array());

	/**
	 * @return WP_Customize_Manager
	 */
	public function get_wp_customize(  );
}