<?php


interface FTB_Output_TemplateTagsInterface {

	public function the_title( $before = '', $after = '' );

	public function the_excerpt(  );

	public function the_content( $before, $after );

	public function the_post_thumbnail( $size, $attr );

	public function the_var( $var );

}