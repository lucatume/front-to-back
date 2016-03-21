<?php


class FTB_Nodes_ExcerptProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	public function process() {
		$field_args = array(
			'settings' => 'ftb-page-' . $this->page_slug . '-excerpt',
			'section'  => $this->section,
			'label'    => _x( 'Excerpt', 'The label of the field in the Theme Customizer', 'ftb' ),
			'type'     => 'editor',
			'default'  => $this->node->nodeValue(),
		);

		$field_args = $this->transport->add_field_args( 'excerpt', $field_args );

		$this->config->add_field( $this->section . '-post_excerpt', $field_args );

		$output = $this->template_tags->the_excerpt();

		return $this->transport->modify_output( 'excerpt', $field_args, $output );
	}
}
