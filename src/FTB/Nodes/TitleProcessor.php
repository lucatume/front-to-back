<?php


class FTB_Nodes_TitleProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	public function process() {
		$field_args = array(
			'settings' => 'ftb-page-' . $this->page_slug . '-title',
			'section'  => $this->section,
			'label'    => _x( 'Title', 'The label of the field in the Theme Customizer', 'ftb' ),
			'type'     => 'text',
			'default'  => $this->node->nodeValue(),
		);

		$field_args = $this->transport->add_field_args( 'title', $field_args );

		$this->config->add_field( $this->section . '-post_title', $field_args );

		$before = $this->node->attr( 'before', '' );
		$after  = $this->node->attr( 'after', '' );

		$output =  $this->template_tags->the_title( $before, $after );

		return $this->transport->modify_output('title', $field_args, $output);
	}
}
