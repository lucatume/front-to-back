<?php
namespace tad\FrontToBack;

class Plugin {

	/**
	 * @var array
	 */
	protected $register;

	/**
	 * @var array
	 */
	protected $singletons;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
	}

	public function set( $key, $value, $singleton = true ) {
		$this->register[ $key ] = $value;
		if ( $singleton ) {
			$this->singletons[] = $key;
		}
	}

	public function get( $key ) {
		if ( ! array_key_exists( $key, $this->register ) ) {
			return null;
		}
		if ( is_callable( $this->register[ $key ] ) ) {
			$value = call_user_func( $this->register[ $key ] );
			if ( array_key_exists( $key, $this->singletons ) ) {
				$this->register[ $key ] = $value;
			} else {
				return $value;
			}
		}

		return $this->register[ $key ];
	}
}