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
		wp_enqueue_script( 'ftb-customizer', $src, array( 'jquery' ), md5( time() ), true );
	}
}