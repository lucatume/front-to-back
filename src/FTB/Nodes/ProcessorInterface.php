<?php


interface FTB_Nodes_ProcessorInterface {

	public function process();

	public function get_node();

	public function get_template_tags();

	public function get_config();

	public function set_section( $section );
}