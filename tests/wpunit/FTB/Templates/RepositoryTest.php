<?php
namespace FTB\Templates;

use FTB_Templates_Repository as Repository;
use org\bovigo\vfs\vfsStream;
use tad\FunctionMocker\FunctionMocker as Test;

class RepositoryTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var int
	 */
	protected static $count = 0;

	/**
	 * @var string
	 */
	protected $templates_folder;

	/**
	 * @var \FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		$this->filesystem = $this->prophesize( 'FTB_Filesystem_FilesystemInterface' );
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

		$this->assertInstanceOf( 'FTB_Templates_Repository', $sut );
	}

	/**
	 * @test
	 * it should not have templates if theme templates folder is missing
	 */
	public function it_should_not_have_templates_if_theme_templates_folder_is_missing() {
		Test::replace( 'get_stylesheet_directory', codecept_data_dir() );
		$this->templates_folder = null;

		$sut = $this->make_instance();

		$this->assertFalse( $sut->has_templates() );
	}

	/**
	 * @test
	 * it should not have templates if templates folder contains no templates
	 */
	public function it_should_not_have_templates_if_templates_folder_contains_no_templates() {
		$this->templates_folder = codecept_data_dir( 'templates0' );
		$sut                    = $this->make_instance();
		$this->assertFalse( $sut->has_templates() );
	}

	/**
	 * @test
	 * it should have templates if templates folder contains one template
	 */
	public function it_should_have_templates_if_templates_folder_contains_one_template() {
		$this->templates_folder = codecept_data_dir( 'templates1' );
		$sut                    = $this->make_instance();

		$this->assertTrue( $sut->has_templates() );
	}

	/**
	 * @test
	 * it should not return any template if has no templates
	 */
	public function it_should_not_return_any_template_if_has_no_templates() {
		$this->templates_folder = codecept_data_dir( 'templates0' );
		$sut                    = $this->make_instance();

		$out = $sut->get_templates();

		$this->assertEquals( [ ], $out );
	}

	/**
	 * @test
	 * it should return a template object for each template in the templates folder
	 */
	public function it_should_return_a_template_object_for_each_template_in_the_templates_folder() {
		$this->templates_folder = codecept_data_dir( 'templates2' );
		$sut                    = $this->make_instance();

		$out = $sut->get_templates();

		$this->assertCount( 2, $out );
		$this->assertContainsOnlyInstancesOf( 'FTB_Templates_TemplateInterface', $out );
	}

	/**
	 * @test
	 * it should write the template to the theme root directory
	 */
	public function it_should_write_the_template_to_the_theme_root_directory() {
		vfsStream::setup( 'theme', null, [ 'ftb-templates' => [ ] ] );
		$this->filesystem->put_contents( vfsStream::url( 'theme/page-some-page.php' ), 'Some Content' )->shouldBeCalled();
		
		$sut = $this->make_instance();
		$sut->set_templates_folder( vfsStream::url( 'theme/ftb-templates' ) );

		$sut->write_template( 'some-page', 'Some Content' );
	}

	private function make_instance( $templates = [ ] ) {
		return new Repository( $this->templates_folder, $this->filesystem->reveal() );
	}

}
