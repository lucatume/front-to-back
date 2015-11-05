<?php

namespace tad\FrontToBack\Templates;

use tad\FrontToBack\Adapters\WP;

class Creator {

	/**
	 * @var Filesystem
	 */
	private $filesystem;
	/**
	 * @var WP
	 */
	private $wp;

	/**
	 * @param Filesystem $filesystem
	 */
	public function __construct( Filesystem $filesystem, WP $wp = null ) {
		$this->filesystem = $filesystem;
		$this->wp         = $wp ? $wp : new WP();
	}

	public function hooks() {
		add_action( 'save_post_page', array( $this, 'create_template' ), 10, 2 );
		add_action( 'post_updated', array( $this, 'move_template' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'delete_template' ) );
		add_action( 'load-page.php', array( $this, 'create_missing_template' ) );
	}

	public function create_missing_template() {
		$post = empty( $_GET['post'] ) ? null : get_post( $_GET['post'] );
		if ( empty( $post ) ) {
			return false;
		}
		$template_path = $this->get_post_template_path( $post );
		if ( $this->filesystem->exists( $template_path ) ) {
			return false;
		}

		$deleted_template_path = $this->get_post_deleted_template_path( $post );
		if ( $this->filesystem->exists( $deleted_template_path ) ) {
			$restored = $this->filesystem->restore_deleted_template( $post->post_name );
		}

		$created = $this->create_template( $post->ID, $post );
		if ( $created || $restored ) {
			$location = $_SERVER['REQUEST_URI'];
			return $this->wp->safe_redirect( $location );
		}
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

	/**
	 * @param $post
	 * @return string
	 */
	public function get_post_template_path( $post ) {
		$template_name = $this->get_post_template_name( $post );
		$template_path = trailingslashit( ftb_get_option( 'templates_folder' ) ) . $template_name;
		return $template_path;
	}

	/**
	 * @param $post
	 * @return string
	 */
	public function get_post_deleted_template_path( $post ) {
		$deleted_template_name = $this->get_deleted_post_template_name( $post );
		$deleted_template_path = trailingslashit( ftb_get_option( 'templates_folder' ) ) . 'deleted/' . $deleted_template_name;
		return $deleted_template_name;
	}

}