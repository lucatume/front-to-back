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
			$this,
			'create_template'
		), 10, 2 );
		add_action( 'post_updated', array(
			$this,
			'move_template'
		), 10, 3 );
	}

	public function rename_template( $post_ID, \WP_Post $post_after, \WP_Post $post_before ) {

	}

	public function create_template(
		/** @noinspection PhpUnusedParameterInspection */
		$id, \WP_Post $post
	) {
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
			'draft',
			'pending',
			'auto-draft'
		);

		return $bail_stati;
	}

	public function move_template( $post_id, \WP_Post $post_after, \WP_Post $post_before ) {
		$old_name = $post_before->post_name;
		$new_name = $post_after->post_name;
		if ( $old_name === $new_name ) {
			return;
		}
		$this->filesystem->move_template( $old_name, $new_name );
	}
}