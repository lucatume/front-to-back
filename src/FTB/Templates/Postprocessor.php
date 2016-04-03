<?php


class FTB_Templates_Postprocessor implements FTB_Templates_PostprocessorInterface {

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function postprocess( $template_contents ) {
		$open_tag  = $this->open_tag();
		$close_tag = $this->close_tag();

		$template_contents = $this->replace_commented_php_open_tag( $template_contents, $open_tag );
		$template_contents = $this->replace_commented_php_close_tag( $template_contents, $close_tag );
		$template_contents = $this->replace_attribute_cast_php_tag( $template_contents, $open_tag, $close_tag );
		$template_contents = $this->replace_php_double_arrow( $template_contents );
		$template_contents = $this->replace_ftb_open_tag( $template_contents );
		$template_contents = $this->replace_ftb_close_tag( $template_contents );
		$template_contents = $this->replace_encoded_php_open_tag( $template_contents );
		$template_contents = $this->replace_encoded_php_close_tag( $template_contents );

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

	/**
	 * @param $template_contents
	 * @param $open_tag
	 *
	 * @return mixed
	 */
	protected function replace_commented_php_open_tag( $template_contents, $open_tag ) {
		$template_contents = str_replace( '<!--?php', $open_tag, $template_contents );

		return $template_contents;
	}

	/**
	 * @param $template_contents
	 * @param $close_tag
	 *
	 * @return mixed
	 */
	protected function replace_commented_php_close_tag( $template_contents, $close_tag ) {
		return str_replace( '?-->', $close_tag, $template_contents );
	}

	/**
	 * @param $template_contents
	 * @param $open_tag
	 * @param $close_tag
	 *
	 * @return mixed
	 */
	protected function replace_attribute_cast_php_tag( $template_contents, $open_tag, $close_tag ) {
		return preg_replace( '/data-ftb-php="(.*)"/', $open_tag . '$1' . $close_tag, $template_contents );
	}

	/**
	 * @param $template_contents
	 *
	 * @return mixed
	 */
	protected function replace_php_double_arrow( $template_contents ) {
		return str_replace( '=&gt;', '=>', $template_contents );
	}

	/**
	 * @param $template_contents
	 *
	 * @return mixed
	 */
	protected function replace_ftb_open_tag( $template_contents ) {
		return str_replace( '&lt;ftb-open-tag', '<', $template_contents );
	}

	/**
	 * @param $template_contents
	 *
	 * @return mixed
	 */
	protected function replace_ftb_close_tag( $template_contents ) {
		return str_replace( 'ftb-close-tag&gt;', '>', $template_contents );
	}

	protected function replace_encoded_php_open_tag( $template_contents ) {
		return str_replace( '&lt;?php', '<?php', $template_contents );
	}

	protected function replace_encoded_php_close_tag( $template_contents ) {
		return str_replace( '?&gt;', '?>', $template_contents );
	}
}
