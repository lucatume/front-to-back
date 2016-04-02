<?php


class FTB_Templates_Preprocessor implements FTB_Templates_PreprocessorInterface {

	/**
	 * @var bool
	 */
	protected $in_attr;

	/**
	 * @var bool
	 */
	protected $context_locked = false;

	/**
	 * @var bool
	 */
	protected $in_tag = false;

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function preprocess( $template_contents ) {
		$tokens = token_get_all( $template_contents );

		$output_frags = array_map( array( $this, 'replace_php_tag' ), $tokens );

		return join( '', $output_frags );
	}

	protected function replace_php_tag( $token ) {
		if ( is_array( $token ) ) {
			list( $index, $code, $line ) = $token;
			if ( ! ( $this->context_locked || in_array( $index, $this->replaced_php_tags() ) ) ) {
				$this->in_attr = preg_match( '/=\\s*("|\')\\s*$/', $code );
				$this->in_tag  = ! $this->in_attr && ! preg_match( '/>\\s*$/', $code );
			}
			switch ( $index ) {
				case T_OPEN_TAG:
					return $this->replace_open_tag();
				case T_CLOSE_TAG:
					return $this->replace_close_tag();
				default:
					return $code;
			}
		}

		return $token;
	}

	/**
	 * @return string
	 */
	protected function replace_open_tag() {
		$this->context_locked = true;

		$out = $this->in_tag ? 'data-ftb-php=" ' : '<!--?php ';

		return $out;

	}

	/**
	 * @return string
	 */
	protected function replace_close_tag() {
		$out = $this->in_tag ? '"' : '?-->';

		$this->reset_context();

		return $out;
	}

	private function replaced_php_tags() {
		return array( T_OPEN_TAG, T_CLOSE_TAG );
	}

	protected function reset_context() {
		$this->context_locked = false;
		$this->in_attr        = false;
		$this->in_tag         = false;
	}
}
