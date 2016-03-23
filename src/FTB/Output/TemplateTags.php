<?php


use Codeception\PHPUnit\Constraint\Page;

class FTB_Output_TemplateTags implements FTB_Output_TemplateTagsInterface {

	public function the_title( $before = '', $after = '' ) {
		$args = ftb_args_string( array( $before, $after ) );

		return sprintf( '<?php the_title(%s); ?>', $args );
	}

	public function the_excerpt() {
		return "<?php the_excerpt(); ?>";
	}

	public function the_content( $more_link_text = '', $strip_teaser = '' ) {
		$args = ftb_args_string( array( $more_link_text, $strip_teaser ) );

		return sprintf( '<?php the_content(%s); ?>', $args );
	}

	public function the_post_thumbnail( $size, $attr ) {
		$args = ftb_args_string( array( $size, $attr ) );

		return sprintf( '<?php the_post_thumbnail(%s); ?>', $args );
	}

	public function the_var( $var ) {
		return '<?php $' . $var . ' = get_post_meta( get_the_ID(), \'' . $var . '\', true); ?>';
	}
}