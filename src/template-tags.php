<?php
function ftb_get_the_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
	$size_query_string = is_string( $size ) || $size === '' ? $size : http_build_query( $size );
	$attr_query_string = is_string( $attr ) || $attr === '' ? $attr : http_build_query( $attr );
	$full_attr         = ftb_merge_query_string_to_array( $attr, array( 'data-ftb-size' => $size_query_string ), true );
	$full_attr         = ftb_merge_query_string_to_array( $full_attr, array( 'data-ftb-attr' => $attr_query_string ), true );

	if ( ! has_post_thumbnail() ) {

		$attr_entry = '';
		foreach ( $full_attr as $name => $value ) {
			$attr_entry .= " $name=" . '"' . $value . '"';
		}

		return sprintf( '<img%s src=""/>', $attr_entry );
	}

	return get_the_post_thumbnail(null, $size, $full_attr );
}

function ftb_the_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
	echo ftb_get_the_post_thumbnail( $size, $attr );
}
