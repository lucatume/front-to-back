<?php


class FTB_Repositories_Attachment implements FTB_Repositories_AttachmentInterface {

	public function find_by_url( $url, $refetch = false ) {
		$guids = $this->get_all_guids( $refetch );
		if ( ! isset( $guids[ $url ] ) ) {
			return false;
		}

		return $guids[ $url ];
	}

	protected function get_all_guids( $refetch = false ) {
		$found  = false;
		$cached = wp_cache_get( __FUNCTION__, __CLASS__, null, $found );

		if ( $refetch || empty( $found ) ) {
			/** @var \wpdb $wpdb */
			global $wpdb;

			$results = $wpdb->get_results( "SELECT ID, guid FROM $wpdb->posts WHERE post_type = 'attachment'" );
			$cached  = array_combine( wp_list_pluck( $results, 'guid' ), wp_list_pluck( $results, 'ID' ) );

			wp_cache_set( __FUNCTION__, $cached, __CLASS__ );
		}

		return $cached;
	}
}