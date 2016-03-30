<?php


class FTB_RestAPI_Markup_AttachmentHandler implements FTB_RestAPI_Markup_AttachmentHandlerInterface {

	/**
	 * @var FTB_Repositories_AttachmentInterface
	 */
	protected $attachment_repository;

	public function __construct( FTB_Repositories_AttachmentInterface $attachment_repository ) {
		$this->attachment_repository = $attachment_repository;
	}

	public function get_attachment_markup( WP_REST_Request $request ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return new WP_REST_Response( array( 'status' => 403, 'message' => 'Current user can\'t edit theme options' ), 403 );
		}

		$request_size = $request->get_param( 'size' );

		$size = $this->get_size( $request_size );

		$request_attr = $request->get_param( 'attr' );

		$attr = $this->get_attr( $request_attr, $request_size );

		$new_src = $request->get_param( 'newSrc' );

		$html = $this->get_attachment_html( $new_src, $size, $attr, $request_size, $request_attr );

		return $html;
	}

	/**
	 * @param $request_size
	 *
	 * @return array|mixed|string
	 */
	protected function get_size( $request_size ) {
		if ( empty( $request_size ) ) {
			$size = '';

			return $size;
		} else {
			$size = array();
			parse_str( $request_size, $size );
			$size = count( $size ) === 2 ? $size : reset( $size );

			return $size;
		}
	}

	/**
	 * @param $request_attr
	 * @param $request_size
	 *
	 * @return array
	 */
	protected function get_attr( $request_attr, $request_size ) {
		if ( empty( $request_attr ) ) {
			$attr = array();
		} else {
			$attr = array();
			parse_str( $request_attr, $attr );
		}

		$attr = ftb_merge_query_string_to_array( $attr, array( 'data-ftb-attr' => $request_attr, 'data-ftb-size' => $request_size ) );

		return $attr;
	}

	/**
	 * @param $new_src
	 * @param $size
	 * @param $attr
	 *
	 * @return string
	 */
	protected function get_attachment_html( $new_src, $size, $attr, $request_size, $request_attr ) {
		if ( empty( $new_src ) ) {
			return ftb_get_the_post_thumbnail( $request_size, $request_attr );
		} else {
			$attachment_id = $this->attachment_repository->find_by_url( $new_src );

			if ( empty( $attachment_id ) ) {
				// try again re-fetching
				$attachment_id = $this->attachment_repository->find_by_url( $new_src, true );
			}

			if ( empty( $attachment_id ) ) {
				return ftb_get_the_post_thumbnail( $request_size, $request_attr );
			}


			return wp_get_attachment_image( $attachment_id, $size, false, $attr );
		}
	}
}