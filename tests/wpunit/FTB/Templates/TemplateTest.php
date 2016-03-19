<?php
namespace FTB\Templates;

use FTB_Templates_Template;

class TemplateTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Filesystem_FilesystemInterface
	 */
	protected $filesystem;

	/**
	 * @var string
	 */
	protected $file = 'some-file.php';

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->filesystem = $this->prophesize( 'FTB_Filesystem_FilesystemInterface' );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Templates_Template', $sut );
	}

	/**
	 * @test
	 * it should return the file contents
	 */
	public function it_should_return_the_file_contents() {
		$this->filesystem->get_contents( $this->file )->willReturn( 'contents' );

		$sut = $this->make_instance();

		$this->assertEquals( 'contents', $sut->get_contents() );
	}

	/**
	 * @test
	 * it should return the template file name
	 */
	public function it_should_return_the_template_file_name() {
		$this->file = __DIR__ . '/some-file.php';

		$sut = $this->make_instance();

		$this->assertEquals( 'some-file', $sut->name() );
	}

	private function make_instance() {
		return new FTB_Templates_Template( $this->file, $this->filesystem->reveal() );
	}
}