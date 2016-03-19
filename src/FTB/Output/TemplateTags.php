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
}