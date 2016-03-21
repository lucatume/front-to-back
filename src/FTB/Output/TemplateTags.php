<?php


use Codeception\PHPUnit\Constraint\Page;

class FTB_Output_TemplateTags implements FTB_Output_TemplateTagsInterface {

	public function the_title( $before = '', $after = '' ) {
		if ( empty( $before ) && empty( $after ) ) {
			return '<?php the_title(); ?>';
		} elseif ( empty( $after ) ) {
			return "<?php the_title( '$before' ); ?>";
		}

		return "<?php the_title( '$before', '$after' ); ?>";
	}

	public function the_excerpt() {
		return "<?php the_excerpt(); ?>";
	}

	public function the_content( $more_link_text = '', $strip_teaser = '' ) {
		if ( empty( $more_link_text ) && empty( $strip_teaser ) ) {
			return '<?php the_content(); ?>';
		} elseif ( empty( $after ) ) {
			return "<?php the_content( '$more_link_text' ); ?>";
		}

		return "<?php the_content( '$more_link_text', '$strip_teaser' ); ?>";
	}

	public function the_post_thumbnail( $size, $attr ) {
		if ( empty( $size ) && empty( $attr ) ) {
			return '<?php the_post_thumbnail(); ?>';
		} elseif ( empty( $after ) ) {
			return "<?php the_post_thumbnail( '$size' ); ?>";
		}

		return "<?php the_post_thumbnail( '$size', '$attr' ); ?>";
	}

	public function the_var( $var ) {
		return '<?php $' . $var . ' = get_post_meta( get_the_ID(), \'' . $var . '\', true); ?>';
	}
}