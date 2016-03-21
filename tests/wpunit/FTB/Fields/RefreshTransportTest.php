<?php
namespace FTB\Fields;

class RefreshTransportTest extends \Codeception\TestCase\WPTestCase {

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

		$this->assertInstanceOf( 'FTB_Fields_RefreshTransport', $sut );
	}

	/**
	 * @test
	 * it should not require field additions
	 */
	public function it_should_not_require_field_additions() {
		$field_args = $this->make_field_args();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->should_add_args( $field_args ) );
	}

	/**
	 * @test
	 * it should not add or modify field args
	 */
	public function it_should_not_add_or_modify_field_args() {
		$field_args = $this->make_field_args();

		$sut = $this->make_instance();

		$this->assertEquals( $field_args, $sut->add_field_args( $field_args ) );
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