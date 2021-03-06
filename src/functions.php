<?php
use Patchwork\Exceptions\Exception;

/**
 * Returns the absolute path to an FTB template file.
 *
 * @param string $path A path to a template relative to the currently active theme (or child theme).
 *                     E.g: `page-name`.
 *
 * @return string
 */
function ftb_template_path( $path ) {
	Arg::_( $path, "Template path frag" )->is_string();

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

function ftb_template( $template, array $data ) {
	Arg::_( $template, 'Template' )->is_string();

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

function ftb_args_string( $args = '' ) {
	if ( empty( $args ) ) {
		return '';
	}

	Arg::_( $args, 'Arguments' )->is_array()->_or()->is_string();

	$args = is_array( $args ) ? $args : array( $args );

	$args = array_slice( $args, 0, key( array_reverse( array_diff( $args, array( "" ) ), 1 ) ) + 1 );
	if ( count( array_filter( $args ) ) > 0 ) {
		$args = array_map( 'ftb_quote_wrap', $args );
	}
	$args = join( ', ', $args );

	return empty( $args ) ? $args : ' ' . $args . ' ';
}

function ftb_textualize_var( $var ) {
	if ( empty( $var ) ) {
		return '';
	}

	Arg::_( $var, 'Variable to textualize' )->is_string()->_or()->is_array()->_or()->is_numeric();


	if ( is_array( $var ) ) {
		if ( is_associative_array( $var ) ) {
			array_walk( $var, 'ftb_key_value_text' );

			return sprintf( 'array( %s )', join( ', ', $var ) );
		} else {
			$pieces = array_map( 'ftb_quote_wrap', $var );

			return sprintf( 'array( %s )', join( ', ', $pieces ) );
		}
	}

	return is_numeric( $var ) ? $var : ftb_quote_wrap( $var );
}

function ftb_quote_wrap( $value_to_wrap ) {
	Arg::_( $value_to_wrap, 'Value to wrap' )->is_string()->_or()->is_numeric();


	return preg_match( '/\\s*array\\s*\\(/', $value_to_wrap ) ? $value_to_wrap : str_replace( '"', '', "'$value_to_wrap'" );
}

function ftb_key_value_text( &$value, $key ) {
	$value = is_numeric( $value ) ? $value : "'{$value}'";

	$value = "'{$key}' => {$value}";
}

function ftb_parse_text_var( $text_var ) {
	Arg::_( $text_var, 'Text array' )->is_string()->_or()->is_array();

	$vars = array();
	parse_str( $text_var, $vars );
	$complex_vars = array_filter( $vars );

	$vars = count( $complex_vars ) == count( $vars ) ? $vars : array_keys( $vars );

	return count( $vars ) == 1 && ! is_associative_array( $vars ) ? reset( $vars ) : $vars;
}

function ftb_merge_query_strings( $original, $add, $merge = true ) {
	if ( empty( $original ) && empty( $add ) ) {
		return '';
	}

	$original_vars = array();
	if ( is_array( $original ) ) {
		$original_vars = $original;
	} else {
		parse_str( $original, $original_vars );
		$original_vars = (array) $original_vars;
	}

	$add_vars = array();
	if ( is_array( $add ) ) {
		$add_vars = $add;
	} else {
		parse_str( $add, $add_vars );
		$add_vars = (array) $add_vars;
	}

	$common_vars = array_intersect( array_keys( $original_vars ), array_keys( $add_vars ) );
	if ( $merge && $common_vars ) {
		foreach ( $common_vars as $key ) {
			$merged                = array_unique( array( $original_vars[ $key ], $add_vars[ $key ] ) );
			$original_vars[ $key ] = count( $merged ) === 1 ? reset( $merged ) : $merged;
			unset( $add_vars[ $key ] );
		}
	}

	return http_build_query( array_merge( $original_vars, $add_vars ) );

}

function ftb_merge_query_string_to_array( $original, $add, $merge = true ) {
	$vars = array();
	parse_str( ftb_merge_query_strings( $original, $add, $merge ), $vars );

	return $vars;
}

function ftb_to_array( $value ) {
	try {
		return (array) $value;
	} catch ( Exception $e ) {
		return array();
	}
}
