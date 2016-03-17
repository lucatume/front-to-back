<?php


class FTB_Nodes_TitleProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	public function process() {
		$this->config->add_field( array(
			'settings' => 'title',
			'section'  => $this->section,
			'label'    => _x( 'Title', 'The label of the field in the Theme Customizer', 'ftb' ),
			'type'     => 'text',
			'default'  => $this->node->nodeValue(),
		) );

		return $this->template_tags->the_title();
	}
}
