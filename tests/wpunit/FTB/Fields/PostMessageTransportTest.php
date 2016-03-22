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

	/**
	 * @test
	 * it should return field args if tag not supported
	 */
	public function it_should_return_field_args_if_tag_not_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();

		$this->assertEquals( $field_args, $sut->add_field_args( 'unsupported', $field_args ) );
	}

	/**
	 * @test
	 * it should not add function if tag requires no function
	 */
	public function it_should_not_add_function_if_tag_requires_no_function() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );

		$this->assertArrayNotHasKey( 'function', $sut->add_field_args( 'some-type', $field_args )['js_vars'] );
	}

	/**
	 * @test
	 * it should not add property if tag requires no property
	 */
	public function it_should_not_add_property_if_tag_requires_no_property() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );

		$this->assertArrayNotHasKey( 'property', $sut->add_field_args( 'some-type', $field_args )['js_vars'] );
	}

	/**
	 * @test
	 * it should not modify markup if tag not supported
	 */
	public function it_should_not_modify_markup_if_tag_not_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();

		$sut->set_wrapping_tag( 'span' );
		$this->assertEquals( 'some-output', $sut->modify_output( 'unsupported', $field_args, 'some-output' ) );
	}

	/**
	 * @test
	 * it should wrap markup with before and after is supported
	 */
	public function it_should_wrap_markup_with_before_and_after_is_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );
		$sut->set_markup_mods( [ 'some-type' => [ 'before' => 'before-', 'after' => '-after' ] ] );

		$this->assertEquals( 'before-foo-after', $sut->modify_output( 'some-type', $field_args, 'foo' ) );
	}

	/**
	 * @test
	 * it should call the markup modifying callback if supported
	 */
	public function it_should_call_the_markup_modifying_callback_if_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );
		$callback = function ( $tag, array $field_args, $output ) {
			return 'before-' . $output . '-after';
		};
		$sut->set_markup_mods( [ 'some-type' => [ 'callback' => $callback ] ] );

		$this->assertEquals( 'before-foo-after', $sut->modify_output( 'some-type', $field_args, 'foo' ) );
	}

	public function tags_and_fields() {
		$title = $this->default_title_field_args();

		return [
			[
				'title',
				$title,
				array_merge( $title,
					$this->additional_args() )
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

	/**
	 * @return \FTB_Fields_PostMessageTransport
	 */
	private function make_instance() {
		return new \FTB_Fields_PostMessageTransport();
	}

	/**
	 * @return array
	 */
	private function default_title_field_args() {
		return array( 'type' => 'text', 'settings' => 'some-setting', 'section' => 'some-section', 'default' => 'Some Value' );
	}

	/**
	 * @return array
	 */
	private function additional_args() {
		return array(
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.some-setting',
					'function' => 'html',
					'prefix'   => '<span class=".some-setting" style="display: inline;">',
					'suffix'   => '</span>'
				)
			)
		);
	}
}