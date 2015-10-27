<?php

namespace tad\FrontToBack\Templates;


class MasterChecker {


	/**
	 * @var Filesystem
	 */
	protected $fs;

	public function __construct( Filesystem $fs = null ) {
		$this->fs = $fs ? $fs : new Filesystem();
	}

	public function hooks() {
		add_action( 'admin_notices', array( $this, 'the_admin_notice' ) );
	}

	public function the_admin_notice() {
		echo $this->get_admin_notice();
	}

	public function get_admin_notice() {
		if ( $this->saving_templates_folder_option() ) {
			return false;
		}
		$templates_folder = trailingslashit( ftb_get_option( 'templates_folder', ftb()->get( 'templates/default-folder' ) ) );
		if ( $this->check( $templates_folder ) ) {
			return false;
		}
		$class   = 'error';
		$message = $this->get_notice_message( $templates_folder );

		return "<div class='{$class}'><p>{$message}</p></div>";
	}

	/**
	 * @return bool
	 */
	protected function saving_templates_folder_option() {
		return ! empty( $_POST['object_id'] ) && $_POST['object_id'] === 'ftb_options' && ! empty( $_POST['templates_folder'] ) && $_POST['templates_folder'] !== ftb_get_option( 'templates_folder' );
	}

	public function check( $templates_folder ) {
		$folder = trailingslashit( $templates_folder );
		$this->fs->initialize_wp_filesystem( $folder );

		/** @noinspection PhpUndefinedMethodInspection */
		return $this->fs->exists( $folder . ftb()->get( 'templates/master-template-name' ) );
	}

	/**
	 * @param $templates_folder
	 *
	 * @return string|void
	 */
	public function get_notice_message( $templates_folder ) {
		$name    = ftb()->get( 'templates/master-template-name' );
		$message = __( "The master template is missing from the templates folder.\nCreate the file <code>{$templates_folder}{$name}</code> or specify the right folder." );

		return $message;
	}
}