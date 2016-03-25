<?php


class FTB_Filesystem_DirectFilesystem implements FTB_Filesystem_FilesystemInterface {

	/**
	 * @param $path
	 * @param $contents
	 *
	 * @return int
	 */
	public function put_contents( $path, $contents ) {
		return file_put_contents( $path, $contents );
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public function get_contents( $path ) {
		return file_get_contents( $path );
	}

	/**
	 * @return string
	 */
	public function root_url( $path ) {
		return plugins_url( $path, dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/front-to-back.php' );
	}
}