<?php


class FTB_Scripts_Customizer implements FTB_Scripts_CustomizerInterface {

	/**
	 * @var FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	public function __construct( FTB_Filesystem_FilesystemInterface $filesystem ) {

		$this->filesystem = $filesystem;
	}

	public function enqueue() {
		$src = $this->filesystem->root_url( 'assets/js/dist/ftb-customizer.js' );
		wp_enqueue_script( 'ftb-customizer', $src, array( 'backbone' ), md5( time() ), true );
		wp_localize_script( 'ftb-customizer', 'ftbData', array( 'nonce' => wp_create_nonce( 'wp_rest' ), 'rest_url_prefix' => rest_get_url_prefix() ) );
	}
}
