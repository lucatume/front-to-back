<?php
function ftb_the_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
	if ( ! has_post_thumbnail() ) {

		$attr = array_map( 'esc_attr', wp_parse_args( $attr ) );

		$attr_entry = '';
		foreach ( $attr as $name => $value ) {
			$attr_entry .= " $name=" . '"' . $value . '"';
		}

		echo "<img " . $attr_entry . " src=''/>";

		return;
	}

	the_post_thumbnail( $size, $attr );
}
