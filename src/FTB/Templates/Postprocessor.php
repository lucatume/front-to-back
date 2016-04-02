<?php


class FTB_Templates_Postprocessor implements FTB_Templates_PostprocessorInterface {

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function postprocess( $template_contents ) {
		$open_tag          = $this->open_tag();
		$close_tag         = $this->close_tag();
		$template_contents = str_replace( '<!--?php', $open_tag, $template_contents );
		$template_contents = str_replace( '?-->', $close_tag, $template_contents );
		$template_contents = preg_replace( '/data-ftb-php="(.*)"/', $open_tag . '$1' . $close_tag, $template_contents );
		$template_contents = str_replace( '=&gt;', '=>', $template_contents );
		$template_contents = str_replace( '&lt;ftb-open-tag', '<', $template_contents );
		$template_contents = str_replace( 'ftb-close-tag&gt;', '>', $template_contents );

		return $template_contents;
	}

	/**
	 * @return string
	 */
	protected function open_tag() {
		return '<?php';
	}

	/**
	 * @return string
	 */
	protected function close_tag() {
		return '?>';
	}
}