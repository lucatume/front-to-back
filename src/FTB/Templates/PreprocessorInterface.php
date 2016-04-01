<?php


interface FTB_Templates_PreprocessorInterface {

	/**
	 * @param string $template_contents
	 *
	 * @return string
	 */
	public function neuter_php_tags($template_contents);
}