<?php
namespace FTB\Templates;

use FTB_Templates_Generator as Generator;
use tad\FunctionMocker\FunctionMocker as Test;

class GeneratorTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Templates_RepositoryInterface
	 */
	protected $templates_repository;

	/**
	 * @var \FTB_Templates_ReaderInterface
	 */
	protected $templates_reader;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		Test::replace( 'wp_redirect' );
		$this->templates_repository = $this->prophesize( 'FTB_Templates_RepositoryInterface' );
		$this->templates_reader     = $this->prophesize( 'FTB_Templates_ReaderInterface' );
	}

	public function tearDown() {
		// your tear down methods here
		Test::tearDown();

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Templates_Generator', $sut );
	}

	/**
	 * @test
	 * it should not genereate templates if GET not set
	 */
	public function it_should_not_genereate_templates_if_get_not_set() {
		$_GET = null;
		$this->templates_repository->has_templates()->shouldNotBeCalled();

		$sut = $this->make_instance();
		$sut->maybe_generate();
	}

	/**
	 * @test
	 * it should not generate templates if GET var not set
	 */
	public function it_should_not_generate_templates_if_get_var_not_set() {
		unset( $_GET['ftb-generate-templates'] );
		$this->templates_repository->has_templates()->shouldNotBeCalled();

		$sut = $this->make_instance();
		$sut->maybe_generate();
	}

	/**
	 * @test
	 * it should not generate templates if GET var not truthy
	 */
	public function it_should_not_generate_templates_if_get_var_not_truthy() {
		$_GET['ftb-generate-templates'] = false;
		$this->templates_repository->has_templates()->shouldNotBeCalled();

		$sut = $this->make_instance();
		$sut->maybe_generate();
	}

	/**
	 * @test
	 * it should genereate templates if GET var set and truthy
	 */
	public function it_should_genereate_templates_if_get_var_set_and_truthy() {
		$_GET['ftb-generate-templates'] = true;
		$this->templates_repository->has_templates()->willReturn( false );
		$this->templates_repository->get_templates()->shouldNotBeCalled();

		$sut = $this->make_instance();
		$sut->maybe_generate();
	}

	/**
	 * @test
	 * it should generate a template for each found template
	 */
	public function it_should_generate_a_template_for_each_found_template() {
		$_GET['ftb-generate-templates'] = true;
		$this->templates_repository->has_templates()->willReturn( true );
		$template = $this->prophesize( 'FTB_Templates_TemplateInterface' );
		$template->get_contents()->willReturn( 'one' );
		$template->name()->willReturn( 'template-one' );
		$this->templates_repository->get_templates()->willReturn( [ $template->reveal() ] );
		$this->templates_reader->set_template_contents( 'one' )->shouldBeCalled();
		$this->templates_reader->read_and_process( 'template-one' )->willReturn( 'output' );
		$this->templates_repository->write_template( 'template-one', 'output' )->shouldBeCalled();

		$sut = $this->make_instance();
		$sut->maybe_generate();
	}

	private function make_instance() {
		return new Generator( $this->templates_repository->reveal(), $this->templates_reader->reveal() );
	}
}