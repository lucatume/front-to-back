<?php
namespace FTB\Nodes;

class DOMNodeTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \DOMNode
	 */
	protected $node;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
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

		$this->assertInstanceOf( 'FTB_Nodes_DOMNode', $sut );
	}

	/**
	 * @test
	 * it should return the node node value
	 */
	public function it_should_return_the_node_node_value() {
		$this->node = (object) [ 'nodeValue' => 'some-value' ];

		$sut = $this->make_instance();

		$this->assertEquals( 'some-value', $sut->nodeValue() );
	}

	/**
	 * @test
	 * it should return empty string if node nodeValue is empty
	 */
	public function it_should_return_empty_string_if_node_node_value_is_empty() {
		$this->node = (object) [ 'nodeValue' => null ];

		$sut = $this->make_instance();

		$this->assertEquals( '', $sut->nodeValue() );
	}

	/**
	 * @test
	 * it should return default if tryng to get attr on node with no attributes
	 */
	public function it_should_return_default_if_tryng_to_get_attr_on_node_with_no_attributes() {
		$this->node = (object) [ 'nodeValue' => 'foo', 'attributes' => null ];

		$sut = $this->make_instance();

		$this->assertEquals( 'some', $sut->attr( 'foo', 'some' ) );
	}

	/**
	 * @test
	 * it should return default if trying to get missing node attr
	 */
	public function it_should_return_default_if_trying_to_get_missing_node_attr() {
		$nodes = $this->prophesize( 'DOMNamedNodeMap' );
		$nodes->getNamedItem( 'foo' )->willReturn( null );
		$this->node = (object) [ 'nodeValue' => 'foo', 'attributes' => $nodes->reveal() ];

		$sut = $this->make_instance();

		$this->assertEquals( 'some', $sut->attr( 'foo', 'some' ) );
	}

	/**
	 * @test
	 * it should return node attribute
	 */
	public function it_should_return_node_attribute() {
		$nodes = $this->prophesize( 'DOMNamedNodeMap' );
		$nodes->getNamedItem( 'key' )->willReturn( (object) [ 'nodeValue' => 'value' ] );
		$this->node = (object) [ 'nodeValue' => 'foo', 'attributes' => $nodes->reveal() ];

		$sut = $this->make_instance();

		$this->assertEquals( 'value', $sut->attr( 'key', 'some' ) );
	}

	private function make_instance() {
		return new \FTB_Nodes_DOMNode( $this->node );
	}

}