<?php
namespace FTB\Templates;

use Prophecy\Argument;

class ReaderTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Nodes_ProcessorFactory
	 */
	protected $node_processor_factory;

	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var \FTB_Templates_PreprocessorInterface
	 */
	protected $preprocessor;

	/**
	 * @var \FTB_Templates_PostprocessorInterface
	 */
	protected $postprocessor;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->node_processor_factory = $this->prophesize( 'FTB_Nodes_ProcessorFactory' );
		$this->config                 = $this->prophesize( 'FTB_Fields_ConfigDumperInterface' );
		$this->preprocessor           = new \FTB_Templates_Preprocessor;
		$this->postprocessor          = new \FTB_Templates_Postprocessor;
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

		$this->assertInstanceOf( 'FTB_Templates_Reader', $sut );
	}

	private function make_instance() {
		return new \FTB_Templates_Reader( $this->node_processor_factory->reveal(), $this->config->reveal(), $this->preprocessor, $this->postprocessor );
	}
}