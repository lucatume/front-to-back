<?php


class FTB_UI_GeneratorControl implements FTB_UI_GeneratorControlInterface {

	public function add_controls( WP_Admin_Bar $admin_bar ) {
		$root_id = 'front-to-back';

		$admin_bar->add_node( array(
			'id'    => $root_id,
			'title' => __( 'Front to Back', 'ftb' ),
		) );

		$admin_bar->add_node( array(
			'parent' => $root_id,
			'id'     => 'front-to-back-commands',
			'group'  => true,
		) );

		$admin_bar->add_node( array(
			'parent' => 'front-to-back-commands',
			'id'     => 'front-to-back-generate-templates',
			'title'  => __( 'Generate Templates', 'ftb' ),
			'href'  => add_query_arg( array( 'ftb-generate-templates' => 1 ) ),
		) );
	}
}