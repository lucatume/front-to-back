<?php

namespace tad\FrontToBack\MetaBoxes;


use tad\FrontToBack\Templates\TemplateFactory;
use tad\FrontToBack\Templates\TemplateInterface;

class Page {

	/**
	 * @var TemplateInterface|void
	 */
	protected $template;

	/**
	 * Page constructor.
	 *
	 * @param TemplateInterface $template
	 */
	public function __construct( TemplateInterface $template = null ) {
		$this->template = empty( $template ) ? TemplateFactory::make() : $template;
	}

	public function hooks() {
		add_action( 'cmb2_admin_init', array( $this, 'add_page_meta_boxes' ) );
	}

	public function add_page_meta_boxes() {
		if ( ! ( $this->template->exists() && $this->template->has_fields() ) || empty( $_GET['id'] ) ) {
			return;
		}
		$id  = "ftb_{$this->template->get_name()}_fields_metabox";
		$cmb = new_cmb2_box( array(
			'id'         => $id, 'title' => __( 'Fields', 'ftb' ), 'object_types' => array( 'page', ),
			'show_on'    => array( 'key' => 'id', 'value' => $_GET['id'] ), 'context' => 'normal', 'priority' => 'high',
			'show_names' => true, // Show field names on the left
		) );

		{
			/** @var FieldInterface $field */
			foreach ( $this->template->get_fields() as $field ) {
				$args = array(
					'name' => $field->get_name(), 'desc' => $field->get_description(), 'id' => $field->get_id(),
					'type' => $field->get_type(),
				);
				$cmb->add_field( $args );
			}
		}
	}
}