<?php


interface FTB_Filesystem_FilesystemInterface {

	/**
	 * @param $path
	 * @param $contents
	 *
	 * @return int
	 */
	public function put_contents( $path, $contents );


	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function get_contents( $file );

	/**
	 * @param $path
	 *
	 * @return string
	 */
	public function root_url($path);
}