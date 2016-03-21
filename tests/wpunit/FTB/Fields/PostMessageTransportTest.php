<?php
namespace FTB\Fields;
require_once 'RefreshTransportTest.php';


class PostMessageTransportTest extends RefreshTransportTest {

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

		$this->assertInstanceOf( 'FTB_Fields_PostMessageTransport', $sut );
	}

	public function tags_and_fields() {
		$title = $this->default_title_field_args();

		return [
			[
				'title',
				$title,
				array_merge( $title,
					array(
						'transport' => 'postMessage',
						'js_vars'   => array(
							array(
								'element'  => '.some-setting',
								'function' => 'html',
								'prefix'   => '<span class=".some-setting" style="display: inline;">',
								'suffix'   => '</span>'
							)
						)
					) )
			],
		];
	}

	/**
	 * @test
	 * it should add fields to title
	 * @dataProvider tags_and_fields
	 */
	public function it_should_add_right_fields_for_tag( $tag, $field_args, $expected_field_args ) {
		$sut = $this->make_instance();

		$sut->set_wrapping_tag( 'span' );

		$this->assertEquals( $expected_field_args, $sut->add_field_args( $tag, $field_args ) );
	}

	public function tags_and_markup() {
		$title = $this->default_title_field_args();

		return [
			[ 'title', $title, 'some-markup-here', 'some-markup-here' ],
		];
	}

	/**
	 * @test
	 * it should modify markup for fields
	 * @dataProvider tags_and_markup
	 */
	public function it_should_modify_markup_for_fields( $tag, $field_args, $markup, $expected_output ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected_output, $sut->modify_output( $tag, $field_args, $markup ) );
	}

	private function make_instance() {
		return new \FTB_Fields_PostMessageTransport();
	}

	/**
	 * @return array
	 */
	private function default_title_field_args() {
		return array( 'type' => 'text', 'settings' => 'some-setting', 'section' => 'some-section', 'default' => 'Some Value' );
	}
}