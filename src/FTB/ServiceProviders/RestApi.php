<?php


class FTB_ServiceProviders_RestApi extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		$this->container->bind( 'FTB_Repositories_AttachmentInterface', 'FTB_Repositories_Attachment' );
		$this->container->bind( 'FTB_RestAPI_Markup_AttachmentHandlerInterface', 'FTB_RestAPI_Markup_AttachmentHandler' );

		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
	}

	public function register_rest_routes() {
		register_rest_route( 'ftb/v1',
			'/markup/attachment',
			array(
				'methods'  => 'GET',
				'callback' => array( $this->container->make( 'FTB_RestAPI_Markup_AttachmentHandlerInterface' ), 'get_attachment_markup' ),
			) );
	}
}