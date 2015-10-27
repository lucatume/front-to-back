<?php

namespace tad\FrontToBack\Exceptions;


class BaseException extends \Exception {

	/**
	 * MissingMasterTemplate constructor.
	 */
	public function __construct( $class, $message, \Exception $previous = null ) {
		if ( is_object( $class ) ) {
			$class = get_class( $class );
		}
		$class = str_replace( 'tad\FrontToBack\\', '', ltrim( $class, '\\' ) );
		parent::__construct( $message, 0, $previous );
		$intro         = $this->intro();
		$intro         = ': ' . $intro . "\n\n" ?: '';
		$this->message = $class . $intro . $this->message;
	}

	protected function intro() {
		return '';
	}

}