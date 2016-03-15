<?php


interface FTB_Templates_ReaderInterface {

	public function set_template_contents( $template_contents );

	public function read_and_process();
}