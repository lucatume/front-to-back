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
		
		$this->config->add_field( $this->section . '-post_title', $field_args );

		return $this->template_tags->the_title();
	}
}
