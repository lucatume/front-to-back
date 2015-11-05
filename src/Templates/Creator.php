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
		add_action( 'save_post_page', array( $this, 'create_template' ), 10, 2 );
		add_action( 'post_updated', array( $this, 'move_template' ), 10, 3 );
//		add_action( 'current_screen', array( $this, 'create_missing_template' ) );
		add_action( 'delete_post', array( $this, 'delete_template' ) );
		add_action( 'load-page.php', array( $this, 'create_missing_template' ) );
	}

	public function create_missing_template() {
		$post = empty( $_GET['post'] ) ? null : get_post( $_GET['post'] );
		if ( empty( $post ) ) {
			return false;
		}
		$template_name = $this->get_post_template_name( $post );
		if ( $this->filesystem->exists( $template_name ) ) {
			return false;
		}

		$deleted_template_name = $this->get_deleted_post_template_name( $post );
		if ( $this->filesystem->exists( $deleted_template_name ) ) {
			return $this->filesystem->restore_deleted_template( $post->post_name );
		}

		return $this->create_template( $post->ID, $post );
	}

	public function create_template( /** @noinspection PhpUnusedParameterInspection */
		$id, \WP_Post $post ) {
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
		$bail_stati = array( 'draft', 'pending', 'auto-draft' );

		return $bail_stati;
	}

	public function move_template( $post_id, \WP_Post $post_after, \WP_Post $post_before ) {
		$old_name = $post_before->post_name;
		$new_name = $post_after->post_name;
		if ( empty( $old_name ) || empty( $new_name ) ) {
			return false;
		}
		if ( $old_name === $new_name ) {
			return false;
		}

		return $this->filesystem->move_template( $old_name, $new_name );
	}

	/**
	 * @param $post
	 *
	 * @return string
	 */
	public function get_post_template_name( $post ) {
		$extension     = ftb()->get( 'templates/extension' );
		$template_name = "{$post->post_name}.{$extension}";

		return $template_name;
	}

	public function delete_template( $post_id ) {
		$post = get_post( $post_id );
		if ( 'page' !== $post->post_type ) {
			return;
		}
		return $this->filesystem->delete_template( $post->post_name );
	}

	public function get_deleted_post_template_name( $post ) {
		$extension     = ftb()->get( 'templates/extension' );
		$template_name = "deleted/{$post->post_name}.{$extension}";

		return $template_name;
	}

}