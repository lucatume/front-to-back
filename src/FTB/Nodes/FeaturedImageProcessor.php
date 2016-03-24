<?php


class FTB_Nodes_FeaturedImageProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	/**
	 * @return mixed
	 */
	public function process() {
		$field_args = array(
			'settings' => 'ftb-page-' . $this->page_slug . '-meta-featured_image',
			'section'  => $this->section,
			'label'    => _x( 'Featured Image', 'The label of the field in the Theme Customizer', 'ftb' ),
			'type'     => 'image',
			'default'  => $this->node->nodeValue(),
		);

		$field_args = $this->transport->add_field_args( 'featured_image', $field_args, $this->node );

		$this->config->add_field( $this->section . '-meta-featured_image', $field_args );

		$size = $this->node->attr( 'size', '' );
		$attr = $this->node->attr( 'attr', '' );

		$output = $this->template_tags->the_post_thumbnail( $size, $attr );

		return $this->transport->modify_output( 'featured_image', $field_args, $output,$this->node );
	}
}
