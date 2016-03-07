<?php


class FTB_Adapters_WP implements FTB_Adapters_WPInterface {

	public function get_attachment_id_from_url( $attachment_url ) {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid = %s", $attachment_url ) );

		return empty( $id ) ? false : $id;
	}
}