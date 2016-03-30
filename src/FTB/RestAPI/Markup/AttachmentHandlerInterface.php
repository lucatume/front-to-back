<?php


interface FTB_RestAPI_Markup_AttachmentHandlerInterface {

	public function get_attachment_markup( WP_REST_Request $request );
}