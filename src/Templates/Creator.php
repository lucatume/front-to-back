<?php

namespace tad\FrontToBack\Templates;


class Creator {

	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * @param Filesystem $filesystem
	 */
	public function __construct( Filesystem $filesystem ) {
		$this->filesystem = $filesystem;
	}

	public function hooks() {
		add_action( 'save_post_page', array(
			$this, 'create_template'
		), 10, 3 );
	}

	public function create_template( $id, \WP_Post $post, $upddate ) {
		$bail_stati = $this->get_bail_stati();
		if ( in_array( $post->post_status, $bail_stati ) ) {
			return false;
		}

		$this->filesystem->duplicate_master_template( $post->post_name );

		return true;
	}

	/**
	 * @return array
	 */
	public function get_bail_stati() {
		$bail_stati = array(
			'draft', 'pending', 'auto-draft'
		);

		return $bail_stati;
	}
}