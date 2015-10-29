<?php

namespace tad\FrontToBack\Templates;


class Filesystem {

	/**
	 * @var string The file extension of the template files.
	 */
	protected $templates_extension;

	/**
	 * @var string The absolute path to the templates folder.
	 */
	private   $templates_root_folder;

	/**
	 * @var string The absolute path to the master template file.
	 */
	protected $master_template_path;

	/**
	 * @var \WP_Filesystem_Base
	 */
	protected $wpfs;


	public function initialize_wp_filesystem( $templates_root_folder = null, $url = null ) {
		if ( empty( $this->wpfs ) ) {
			$templates_root_folder = $templates_root_folder ? $templates_root_folder : $this->templates_root_folder;
			$url                   = $url ?: trailingslashit( site_url() ) . $_SERVER['REQUEST_URI'];
			if ( false === ( $creds = request_filesystem_credentials( $url, '', false, $templates_root_folder, null ) ) ) {
				return false;
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( $url, '', true, $templates_root_folder, null );

				return false;
			}
			global $wp_filesystem;
			$this->wpfs = $wp_filesystem;

			return true;
		}

		return ! empty( $this->wpfs );
	}

	public function __construct( $templates_root_folder = null, \WP_Filesystem_Base $wpfs = null ) {
		require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . '/wp-admin/includes/file.php';
		$this->templates_root_folder = $templates_root_folder ? trailingslashit( $templates_root_folder ) : ftb_get_option( 'templates_folder' );
		if ( empty( $wpfs ) ) {
			$this->initialize_wp_filesystem();
		} else {
			$this->wpfs = $wpfs;
		}
		$this->templates_extension  = ftb()->get( 'templates/extension' );
		$this->master_template_path = $this->templates_root_folder . ftb()->get( 'templates/master-template-name' );
	}

	/** Forwards calls to \WP_Filesystem_Base
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		return call_user_func_array( array(
			$this->wpfs,
			$name
		), $arguments );
	}

	public function duplicate_master_template( $post_name ) {
		$filesystem_ok = $this->initialize_wp_filesystem();
		if ( ! $filesystem_ok ) {
			return;
		}
		$master_template_ok = $this->ensure_master_template();
		if ( ! $master_template_ok ) {
			return;
		}
		$template_path = $this->templates_root_folder . "{$post_name}.{$this->templates_extension}";
		if ( $this->wpfs->exists( $template_path ) ) {
			return;
		}
		$contents = $this->wpfs->get_contents( $this->master_template_path );
		$append   = false;
		$this->wpfs->put_contents( $template_path, $contents, $append );
	}

	public function ensure_master_template() {
		return $this->wpfs->exists( $this->master_template_path );
	}

	public function move_template( $old_name, $new_name ) {
		$extension = ftb()->get( 'templates/extension' );
		$from      = $this->templates_root_folder . "{$old_name}.{$extension}";
		$to        = $this->templates_root_folder . "{$new_name}.{$extension}";
		$this->wpfs->move( $from, $to, true );
	}

	public function get_wpfs() {
		return $this->wpfs;
	}

	public function get_templates_root_folder() {
		return $this->templates_root_folder;
	}

	public function has_access() {
		return $this->initialize_wp_filesystem();
	}
}