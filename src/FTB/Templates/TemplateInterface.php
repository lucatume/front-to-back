<?php


interface FTB_Templates_TemplateInterface {

	/**
	 * @return string
	 */
	public function get_contents();

	/**
	 * @return string
	 */
	public function name();
}