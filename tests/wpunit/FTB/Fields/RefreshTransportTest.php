<?php
namespace FTB\Fields;

class RefreshTransportTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Nodes_DOMNodeInterface
	 */
	protected $node;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->node = $this->prophesize( 'FTB_Nodes_DOMNodeInterface' );
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

		$this->assertInstanceOf( 'FTB_Fields_RefreshTransport', $sut );
	}

	/**
	 * @test
	 * it should not add or modify field args
	 */
	public function it_should_not_add_or_modify_field_args() {
		$field_args = $this->make_field_args();

		$sut = $this->make_instance();

		$this->assertEquals( $field_args, $sut->add_field_args( 'some-tag', $field_args, $this->node->reveal() ) );
	}

	/**
	 * @test
	 * it should not modify the markup
	 */
	public function it_should_not_modify_the_markup() {
		$field_args = $this->make_field_args();

		$sut = $this->make_instance();

		$this->assertEquals( 'some-markup', $sut->modify_output( 'some-tag', $field_args, 'some-markup', $this->node->reveal() ) );
	}

	private function make_instance() {
		return new \FTB_Fields_RefreshTransport();
	}

	protected function make_field_args() {
		return [
			'type'     => 'text',
			'settings' => 'some-setting',
			'section'  => 'some-section',
			'default'  => 'Some Value',
		];
	}
}