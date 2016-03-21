<?php
namespace FTB\Nodes;

use FTB_Nodes_ProcessorFactory as Factory;
use Prophecy\Argument;

class ProcessorFactoryTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Output_TemplateTagsInterface
	 */
	protected $template_tags;

	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var \FTB_Fields_TransportInterface
	 */
	protected $transport;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->template_tags = $this->prophesize( 'FTB_Output_TemplateTagsInterface' );
		$this->config        = $this->prophesize( 'FTB_Fields_ConfigDumperInterface' );
		$this->transport     = $this->prophesize( 'FTB_Fields_TransportInterface' );
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

		$this->assertInstanceOf( 'FTB_Nodes_ProcessorFactory', $sut );
	}

	/**
	 * @test
	 * it should return false if trying to get processor for unsupported type
	 */
	public function it_should_return_false_if_trying_to_get_processor_for_unsupported_type() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$out  = $sut->make_for_type( 'some-type', $node );

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return instance of processor associated to type
	 */
	public function it_should_return_instance_of_processor_associated_to_type() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$sut->set_class_for_type( 'FTB\Test\DummyNodeProcessor', 'some-type' );
		$out = $sut->make_for_type( 'some-type', $node );

		$this->assertInstanceOf( 'FTB\Test\DummyNodeProcessor', $out );
	}

	/**
	 * @test
	 * it should allow associating an instance to a type
	 */
	public function it_should_allow_associating_an_instance_to_a_type() {
		$sut = $this->make_instance();

		$node               = new \DOMNode();
		$processor          = $this->prophesize( 'FTB_Nodes_ProcessorInterface' );
		$revealed_processor = $processor->reveal();

		$sut->set_class_for_type( $revealed_processor, 'some-type' );
		$out = $sut->make_for_type( 'some-type', $node );

		$this->assertInstanceOf( 'FTB_Nodes_ProcessorInterface', $out );

		$this->assertSame( $revealed_processor, $out );
	}

	/**
	 * @test
	 * it should throw if trying to associate a class not implementing the ProcessorInterface with a type
	 */
	public function it_should_throw_if_trying_to_associate_a_class_not_implementing_the_processor_interface_with_a_type() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$this->setExpectedException( 'InvalidArgumentException' );

		$sut->set_class_for_type( 'stdClass', 'some-type' );
	}

	/**
	 * @test
	 * it should set the node on the processor
	 */
	public function it_should_set_the_node_on_the_processor() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$sut->set_class_for_type( 'FTB\Test\DummyNodeProcessor', 'some-type' );
		/** @var \FTB_Nodes_ProcessorInterface $out */
		$out = $sut->make_for_type( 'some-type', $node );

		$this->assertInstanceOf( 'FTB_Nodes_DOMNodeInterface', $out->get_node() );
	}

	/**
	 * @test
	 * it should set the template_tags on the processor
	 */
	public function it_should_set_the_template_tags_on_the_processor() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$sut->set_class_for_type( 'FTB\Test\DummyNodeProcessor', 'some-type' );
		/** @var \FTB_Nodes_ProcessorInterface $out */
		$out = $sut->make_for_type( 'some-type', $node );

		$this->assertInstanceOf( 'FTB_Output_TemplateTagsInterface', $out->get_template_tags() );
	}

	/**
	 * @test
	 * it should set the config on the processor
	 */
	public function it_should_set_the_config_on_the_processor() {
		$sut = $this->make_instance();

		$node = new \DOMNode();
		$sut->set_class_for_type( 'FTB\Test\DummyNodeProcessor', 'some-type' );
		/** @var \FTB_Nodes_ProcessorInterface $out */
		$out = $sut->make_for_type( 'some-type', $node );

		$this->assertInstanceOf( 'FTB_Fields_ConfigDumperInterface', $out->get_config() );
	}

	private function make_instance() {
		return new Factory( $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}
}