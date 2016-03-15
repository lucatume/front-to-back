<?php


interface FTB_Templates_RepositoryInterface {

	/**
	 * @return bool
	 */
	public function has_templates();

	/**
	 * @return FTB_Templates_TemplateInterface[]
	 */
	public function get_templates();

	/**
	 * @param string $name
	 * @param string $output
	 *
	 * @return bool
	 */
	public function write_template( $name, $output );

	/**
	 * @return string
	 */
	public function get_templates_folder_path();
}