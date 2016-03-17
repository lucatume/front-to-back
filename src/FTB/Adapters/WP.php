<?php


class FTB_Adapters_WP implements FTB_Adapters_WPInterface {

	public function get_attachment_id_from_url( $attachment_url ) {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid = %s", $attachment_url ) );

		return empty( $id ) ? false : $id;
	}

	public function save_configuration( $config ) {
		return update_option( 'ftb-configuration', json_encode( $config ) );
	}

	public function get_json_decoded_option( $name, array $default = array() ) {
		return json_decode( get_option( $name, $default ), true );
	}
}