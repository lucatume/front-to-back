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
	$path_frags = array( get_stylesheet_directory(), 'ftb-templates', ltrim( $path, DIRECTORY_SEPARATOR ) . '.php' );

	return join( DIRECTORY_SEPARATOR, $path_frags );
}

