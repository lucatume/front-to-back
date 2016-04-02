<?php


interface FTB_Templates_PostprocessorInterface {

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function postprocess( $template_contents );
}