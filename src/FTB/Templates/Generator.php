<?php


class FTB_Templates_Generator implements FTB_Templates_GeneratorInterface {

	/**
	 * @var FTB_Templates_RepositoryInterface
	 */
	protected $templates_repository;
	/**
	 * @var FTB_Templates_ReaderInterface
	 */
	protected $templates_reader;
	/**
	 * @var FTB_Fields_ConfigDumperInterface
	 */
	private $config;

	public function __construct( FTB_Templates_RepositoryInterface $templates_repository, FTB_Templates_ReaderInterface $templates_reader, FTB_Fields_ConfigDumperInterface $config ) {
		$this->templates_repository = $templates_repository;
		$this->templates_reader     = $templates_reader;
		$this->config               = $config;
	}

	public function maybe_generate() {
		$should_generate_templates = ! empty( $_GET ) && isset( $_GET['ftb-generate-templates'] ) && $_GET['ftb-generate-templates'];
		if ( $should_generate_templates && $this->templates_repository->has_templates() ) {
			$templates = $this->templates_repository->get_templates();
			array_walk( $templates, array( $this, 'process_template' ) );

			$this->config->save_configuration();

			wp_redirect( remove_query_arg( 'ftb-generate-templates' ) );
		}

	}

	private function process_template( FTB_Templates_TemplateInterface $template ) {
		$this->templates_reader->set_template_contents( $template->get_contents() );
		$output = $this->templates_reader->read_and_process( $template->name() );
		$this->templates_repository->write_template( $template->name(), $output );
	}
}