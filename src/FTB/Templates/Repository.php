<?php


class FTB_Templates_Repository implements FTB_Templates_RepositoryInterface {

	/**
	 * @var null
	 */
	protected $templates_folder;

	/**
	 * @var array
	 */
	protected $_found = false;

	/**
	 * @var RecursiveIteratorIterator
	 */
	protected $iterator;

	/**
	 * @var SPLFileInfo[]
	 */
	protected $_templates;

	/**
	 * @var FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	/**
	 * FTB_Templates_Repository constructor.
	 *
	 * @param null                               $templates_folder
	 * @param FTB_Filesystem_FilesystemInterface $filesystem
	 */
	public function __construct( $templates_folder = null, FTB_Filesystem_FilesystemInterface $filesystem ) {
		$this->templates_folder = $templates_folder ? $templates_folder : get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'ftb-templates';
		$this->filesystem       = $filesystem;
	}

	/**
	 * @return bool
	 */
	public function has_templates() {
		return count( $this->found() ) > 0;
	}

	/**
	 * @return FTB_Templates_TemplateInterface[]
	 */
	public function get_templates() {
		if ( ! $this->has_templates() ) {
			return array();
		}

		return array_map( array( $this, 'instantiate_template' ), $this->_found );
	}

	/**
	 * @param string $name
	 * @param string $output
	 *
	 * @return bool
	 */
	public function write_template( $name, $output ) {
		$path = dirname( $this->templates_folder ) . DIRECTORY_SEPARATOR . 'page-' . $name . '.php';
		$this->filesystem->put_contents( $path, $output );
	}

	/**
	 * @return array
	 */
	private function found() {
		if ( $this->_found === false ) {
			$this->_found = glob( $this->templates_folder . DIRECTORY_SEPARATOR . '*.php' );
		}

		return $this->_found;
	}

	/**
	 * @return string
	 */
	public function get_templates_folder_path() {
		return $this->templates_folder;
	}

	/**
	 * @param $templates_folder
	 */
	public function set_templates_folder( $templates_folder ) {
		$this->templates_folder = $templates_folder;
	}

	private function instantiate_template( $file ) {
		return new FTB_Templates_Template( $file, $this->filesystem );
	}
}