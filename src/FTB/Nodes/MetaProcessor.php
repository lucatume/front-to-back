<?php


class FTB_Nodes_MetaProcessor extends FTB_Nodes_AbstractNodeProcessor implements FTB_Nodes_ProcessorInterface {

	public function process() {
		$var  = sanitize_title( $this->node->attr( 'var' ) );
		$type = $this->node->attr( 'type' );

		$field_args = array(
			'settings' => 'ftb-page-' . $this->page_slug . '-meta-' . $var,
			'section'  => $this->section,
			'label'    => sprintf( _x( 'Meta - %1$s', 'The label of the field in the Theme Customizer', 'ftb' ), str_replace( '_', ' ', ucfirst( $var ) ) ),
			'type'     => $type ? $type : 'text',
			'default'  => $this->node->nodeValue(),
		);

		$field_args = $this->transport->add_field_args( 'meta', $field_args );

		$this->config->add_field( $this->section . '-meta- ' . $var, $field_args );

		$output = $this->template_tags->the_var( $var );

		return $this->transport->modify_output( 'meta', $field_args, $output );
	}
}