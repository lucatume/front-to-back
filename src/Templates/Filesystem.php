<?php

namespace tad\FrontToBack\Templates;


class Filesystem {

	protected $templates_extension;
	protected $master_template_path;

	/**
	 * @var \WP_Filesystem_Base
	 */
	protected $wpfs;
	private   $templates_root_folder;

	public function initialize_wp_filesystem( $templates_root_folder = null ) {
		if ( empty( $this->wpfs ) ) {
			$templates_root_folder = $templates_root_folder ? $templates_root_folder : $this->templates_root_folder;
			require_once ABSPATH . '/wp-admin/includes/file.php';
			$url = admin_url( 'admin.php?page=ftb_options' );
			if ( false === ( $creds = request_filesystem_credentials( $url, 'direct', false, $templates_root_folder, null ) ) ) {
				return false;
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( $url, 'direct', true, $templates_root_folder, null );

				return false;
			}
			global $wp_filesystem;
			$this->wpfs = $wp_filesystem;

			return true;
		}

		return ! empty( $this->wpfs );
	}

	public function __construct( $templates_root_folder = null, \WP_Filesystem_Base $wpfs = null ) {
		$this->templates_extension   = ftb()->get( 'templates/extension' );
		$this->templates_root_folder = $templates_root_folder ? trailingslashit( $templates_root_folder ) : ftb_get_option( 'templates_folder' );
		$this->master_template_path  = $this->templates_root_folder . ftb()->get( 'templates/master-template-name' );
		$this->wpfs                  = $wpfs;
		$this->initialize_wp_filesystem();
	}

	/** Forwards calls to \WP_Filesystem_Base
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		return call_user_func_array( array( $this->wpfs, $name ), $arguments );
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
}