<?php


class FTB_Repositories_Attachment implements FTB_Repositories_AttachmentInterface {

	/**
	 * @var wpdb
	 */
	protected $wpdb;

	/**
	 * FTB_Repositories_Attachment constructor.
	 *
	 * @param wpdb $wpdb
	 */
	public function __construct( wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * @param string $url
	 * @param bool   $refetch
	 *
	 * @return bool
	 */
	public function find_by_url( $url, $refetch = false ) {
		Arg::_( $url, 'URL' )->is_string();
		Arg::_( $refetch, 'Force refetch' )->is_bool();

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
			$posts   = $this->wpdb->get_blog_prefix() . 'posts';
			$results = $this->wpdb->get_results( "SELECT ID, guid FROM $posts WHERE post_type = 'attachment'" );
			$cached  = array_combine( wp_list_pluck( $results, 'guid' ), wp_list_pluck( $results, 'ID' ) );

			wp_cache_set( __FUNCTION__, $cached, __CLASS__ );
		}

		return $cached;
	}
}