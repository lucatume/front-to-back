<?php

/**
 * Returns the absolute path to an FTB template file.
 *
 * @param string $path A path to a template relative to the currently active theme (or child theme).
 *                     E.g: `page-name`.
 *
 * @return string
 */
function ftb_template_path( $path ) {
	$cached = wp_cache_get( $path, 'ftb_template_path' );
	if ( ! $cached ) {
		$root = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'ftb-templates';

		if ( empty( $path ) ) {
			return $root;
		}

		$has_extension = preg_match( '~\\.[a-zA-Z0-9]+$~', $path );
		$extension     = $has_extension ? '' : '.php';
		$path_frags    = array( $root, ltrim( $path, DIRECTORY_SEPARATOR ) . $extension );

		$cached = join( DIRECTORY_SEPARATOR, $path_frags );
		wp_cache_set( $path, $cached, 'ftb_template_path' );
	}

	return $cached;
}

function ftb_template( $template, $data ) {
	$cache_key = $template . serialize( $data );
	$cached    = wp_cache_get( $cache_key, 'ftb_template' );
	if ( $cached === false ) {
		$out = $template;

		foreach ( $data as $key => $value ) {
			if ( ! is_string( $value ) ) {
				continue;
			}

			$out = str_replace( '{{' . $key . '}}', $value, $out );
		}
		$cached = $out;
		wp_cache_set( $cache_key, $out, 'ftb_template' );
	}

	return $cached;
}

