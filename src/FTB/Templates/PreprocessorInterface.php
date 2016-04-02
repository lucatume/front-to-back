<?php


interface FTB_Templates_PreprocessorInterface {

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function preprocess($template_contents);
}