<?php


class FTB_Nodes_ContentProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface{

	public function process() {
		$field_args = array(
			'settings' => 'ftb-page-' . $this->page_slug . '-content',
			'section'  => $this->section,
			'label'    => _x( 'Content', 'The label of the field in the Theme Customizer', 'ftb' ),
			'type'     => 'editor',
			'default'  => $this->node->nodeValue(),
		);

		$field_args = $this->transport->add_field_args( 'content', $field_args );

		$this->config->add_field( $this->section . '-post_content', $field_args );

		$more_link_text = $this->node->attr( 'more-link-text', '' );
		$strip_teaser  = $this->node->attr( 'strip-teaser', '' );

		$output =  $this->template_tags->the_content( $more_link_text, $strip_teaser );

		return $this->transport->modify_output('content', $field_args, $output);
	}
}