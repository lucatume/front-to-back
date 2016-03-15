<?php


class FTB_Templates_Template implements FTB_Templates_TemplateInterface {

	/**
	 * @var FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	private $file;

	public function __construct( $file, FTB_Filesystem_FilesystemInterface $filesystem ) {
		$this->file       = $file;
		$this->filesystem = $filesystem;
		$this->name = rtrim(basename( $this->file ),'.php');
	}

	/**
	 * @return string
	 */
	public function get_contents() {
		return $this->filesystem->get_contents( $this->file );
	}

	/**
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
}