<?php


class FTB_Scripts_Customizer implements FTB_Scripts_CustomizerInterface {

	/**
	 * @var FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	/**
	 * @var FTB_Output_CustomizerMarkupProviderInterface
	 */
	protected $markup_provider;

	public function __construct( FTB_Filesystem_FilesystemInterface $filesystem, FTB_Output_CustomizerMarkupProviderInterface $markup_provider) {
		$this->filesystem = $filesystem;
		$this->markup_provider = $markup_provider;
	}

	public function enqueue_iframe_scripts() {
		$src = $this->filesystem->root_url( 'assets/js/dist/ftb-customizer-iframe.js' );
		wp_enqueue_script( 'ftb-customizer-iframe', $src, array( 'backbone' ), md5( time() ), true );
		wp_localize_script( 'ftb-customizer-iframe', 'ftbData', array( 'nonce' => wp_create_nonce( 'wp_rest' ), 'rest_url_prefix' => rest_get_url_prefix() ) );
	}

	public function enqueue_scripts() {
		$src = $this->filesystem->root_url( 'assets/js/dist/ftb-customizer.js' );
		wp_enqueue_script( 'ftb-customizer', $src, array(), md5( time() ), true );
		wp_localize_script( 'ftb-customizer', 'ftbData', array( 'customizer' => array( 'page_nav' => array( 'html' => $this->markup_provider->get_page_nav_markup() ) ) ) );
	}
}
