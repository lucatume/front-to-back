<?php


class FTB_Output_TemplateTags implements FTB_Output_TemplateTagsInterface {

	public function the_title( $before = '', $after = '' ) {
		return sprintf( '<?php the_title( "%s", "%s" ) ?>', $before, $after );
	}
}