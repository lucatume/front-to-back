<?php

/**
 * @return \tad\FrontToBack\Plugin
 */
function ftb() {
	global $ftb;
	if ( empty( $ftb ) ) {
		$ftb = new \tad\FrontToBack\Plugin();
	}

	return $ftb;
}

/**
 * Adds an admin notice in the WordPress admin area.
 *
 * @param        $message
 * @param string $class
 */
function ftb_notice( $message, $class = 'error' ) {
	add_action( 'admin_notices', function () use ( $message, $class ) {
		echo "<div class='{$class}'><p>{$message}</p></div>";
	} );
}

/**
 * @param      $key
 * @param null $default
 *
 * @return null
 */
function ftb_get_option( $key, $default = null ) {
	$options = get_option( 'ftb_options', array() );
	if ( empty( $options ) ) {
		return $default;
	}
	if ( array_key_exists( $key, $options ) ) {
		return $options[ $key ];
	}

	return null;
}
