<?php


interface FTB_Locators_PageInterface {

	public function __call( $name, array $args = array() );

	public function get_queried_post();
}