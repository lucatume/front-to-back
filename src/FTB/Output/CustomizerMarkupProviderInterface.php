<?php


interface FTB_Output_CustomizerMarkupProviderInterface {

	/**
	 * @return string The Theme Customizer page nav menu HTML markup.
	 */
	public function get_page_nav_markup();
}