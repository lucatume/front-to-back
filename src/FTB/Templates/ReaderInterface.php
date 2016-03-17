<?php


interface FTB_Templates_ReaderInterface {

	public function set_template_contents( $template_contents );

	/**
	 * @param $template_name
	 *
	 * @return mixed
	 */
	public function read_and_process( $template_name );
}