<?php
namespace FTB\Fields;

use Prophecy\Argument;

require_once 'RefreshTransportTest.php';


class PostMessageTransportTest extends RefreshTransportTest {

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

		$this->assertInstanceOf( 'FTB_Fields_PostMessageTransport', $sut );
	}

	/**
	 * @test
	 * it should return field args if tag not supported
	 */
	public function it_should_return_field_args_if_tag_not_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();

		$this->assertEquals( $field_args, $sut->add_field_args( 'unsupported', $field_args, $this->node->reveal() ) );
	}

	/**
	 * @test
	 * it should not add function if tag requires no function
	 */
	public function it_should_not_add_function_if_tag_requires_no_function() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );

		$this->assertArrayNotHasKey( 'function', $sut->add_field_args( 'some-type', $field_args, $this->node->reveal() )['js_vars'] );
	}

	/**
	 * @test
	 * it should not add property if tag requires no property
	 */
	public function it_should_not_add_property_if_tag_requires_no_property() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();
		$sut->set_supported_tags( [ 'some-type' ] );

		$this->assertArrayNotHasKey( 'property', $sut->add_field_args( 'some-type', $field_args, $this->node->reveal() )['js_vars'] );
	}

	/**
	 * @test
	 * it should not modify markup if tag not supported
	 */
	public function it_should_not_modify_markup_if_tag_not_supported() {
		$field_args = [ 'settings' => 'value' ];
		$sut        = $this->make_instance();

		$sut->set_wrapping_tag( 'span' );
		$this->assertEquals( 'some-output', $sut->modify_output( 'unsupported', $field_args, 'some-output', $this->node->reveal() ) );
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

		$this->assertEquals( 'before-foo-after', $sut->modify_output( 'some-type', $field_args, 'foo', $this->node->reveal() ) );
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

		$this->assertEquals( 'before-foo-after', $sut->modify_output( 'some-type', $field_args, 'foo', $this->node->reveal() ) );
	}

	public function title_tags_and_fields() {
		$default_title_field_args = $this->default_field_args();

		return [
			[
				'title',
				$default_title_field_args,
				'.some-element',
				array_merge( $default_title_field_args, [ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-element', 'function' => 'html' ] ] ] ),
			],
		];
	}

	/**
	 * @test
	 * it should add fields to title
	 * @dataProvider title_tags_and_fields
	 */
	public function it_should_add_fields_to_title( $tag, $field_args, $element_attr, $expected_field_args ) {
		$this->node->attr( 'element', Argument::type( 'string' ) )->willReturn( $element_attr );
		$this->node->attr( 'attr', Argument::type( 'string' ) )->willReturn( '' );

		$sut = $this->make_instance();

		$this->assertEquals( $expected_field_args, $sut->add_field_args( $tag, $field_args, $this->node->reveal() ) );
	}

	public function excerpt_tags_and_fields() {
		$default_excerpt_field_args = $this->default_field_args();

		return [
			[
				'excerpt',
				$default_excerpt_field_args,
				'.some-element',
				array_merge( $default_excerpt_field_args, [ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-element', 'function' => 'html' ] ] ] ),
			],
		];
	}

	/**
	 * @test
	 * it should add fields to excerpt
	 * @dataProvider excerpt_tags_and_fields
	 */
	public function it_should_add_fields_to_excerpt( $tag, $field_args, $element_attr, $expected_field_args ) {
		$this->node->attr( 'element', Argument::type( 'string' ) )->willReturn( $element_attr );
		$this->node->attr( 'attr', Argument::type( 'string' ) )->willReturn( '' );

		$sut = $this->make_instance();

		$this->assertEquals( $expected_field_args, $sut->add_field_args( $tag, $field_args, $this->node->reveal() ) );
	}

	public function content_tags_and_fields() {
		$default_content_field_args = $this->default_field_args();

		return [
			[
				'content',
				$default_content_field_args,
				'.some-element',
				array_merge( $default_content_field_args, [ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-element', 'function' => 'html' ] ] ] ),
			],
		];
	}

	/**
	 * @test
	 * it should add fields to content
	 * @dataProvider content_tags_and_fields
	 */
	public function it_should_add_fields_to_content( $tag, $field_args, $element_attr, $expected_field_args ) {
		$this->node->attr( 'element', Argument::type( 'string' ) )->willReturn( $element_attr );
		$this->node->attr( 'attr', Argument::type( 'string' ) )->willReturn( '' );

		$sut = $this->make_instance();

		$this->assertEquals( $expected_field_args, $sut->add_field_args( $tag, $field_args, $this->node->reveal() ) );
	}

	public function featured_image_tags_and_fields() {
		$default_featured_image_field_args = $this->default_field_args();

		return [
			// no element? use the setting
			[
				'featured_image',
				$default_featured_image_field_args,
				'.' . $default_featured_image_field_args['settings'],
				'',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-setting', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified element? use that
			[
				'featured_image',
				$default_featured_image_field_args,
				'.some-element',
				'',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-element', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified a class? use that
			[
				'featured_image',
				$default_featured_image_field_args,
				'',
				'class=some-class',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.some-class', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified 2+ classes? use those
			[
				'featured_image',
				$default_featured_image_field_args,
				'',
				'class[]=class-one&class[]=class-two&class[]=class-three',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '.class-one.class-two.class-three', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified an id? use that
			[
				'featured_image',
				$default_featured_image_field_args,
				'',
				'id=some-id',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '#some-id', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified 2+ ids? use those
			[
				'featured_image',
				$default_featured_image_field_args,
				'',
				'id[]=some-id&id[]=another-id',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '#some-id#another-id', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
			// specified ids and classes? use those all
			[
				'featured_image',
				$default_featured_image_field_args,
				'',
				'class[]=class-one&id[]=some-id&id[]=another-id&class[]=class-two',
				array_merge( $default_featured_image_field_args,
					[ 'transport' => 'postMessage', 'js_vars' => [ [ 'element' => '#some-id#another-id.class-one.class-two', 'js_callback' => 'ftb_replace_src' ] ] ] ),
			],
		];
	}

	/**
	 * @test
	 * it should add fields to featured image
	 * @dataProvider featured_image_tags_and_fields
	 */
	public function it_should_add_fields_to_featured_image( $tag, $field_args, $element_attr, $attr_attr, $expected_field_args ) {
		$this->node->attr( 'element', Argument::type( 'string' ) )->willReturn( $element_attr );
		$this->node->attr( 'attr', Argument::type( 'string' ) )->willReturn( $attr_attr );

		$sut = $this->make_instance();

		$out = $sut->add_field_args( $tag, $field_args, $this->node->reveal() );
		$this->assertEquals( $expected_field_args, $out );
	}

	public function meta_tags_and_fields() {
		$default_meta_field_args = $this->default_field_args();

		return [
			// no element? use the setting
			[
				'meta',
				$default_meta_field_args,
				'.' . $default_meta_field_args['settings'],
				'',
				array_merge( $default_meta_field_args,
					[
						'transport' => 'postMessage',
						'js_vars'   => [ [ 'element' => '.some-setting', 'function' => 'html', 'prefix' => '<span class=".some-setting">', 'suffix' => '</span>' ] ]
					] ),
			],
			// specified element? use that
			[
				'meta',
				$default_meta_field_args,
				'.some-element',
				'',
				array_merge( $default_meta_field_args,
					[
						'transport' => 'postMessage',
						'js_vars'   => [ [ 'element' => '.some-element', 'function' => 'html', 'prefix' => '<span class=".some-element">', 'suffix' => '</span>' ] ]
					] ),
			],
			[
				'meta',
				$default_meta_field_args,
				'.one.two',
				'',
				array_merge( $default_meta_field_args,
					[
						'transport' => 'postMessage',
						'js_vars'   => [ [ 'element' => '.one.two', 'function' => 'html', 'prefix' => '<span class=".one.two">', 'suffix' => '</span>' ] ]
					] ),
			],
			[
				'meta',
				$default_meta_field_args,
				'#id1.one',
				'',
				array_merge( $default_meta_field_args,
					[
						'transport' => 'postMessage',
						'js_vars'   => [ [ 'element' => '#id1.one', 'function' => 'html', 'prefix' => '<span class="#id1.one">', 'suffix' => '</span>' ] ]
					] ),
			],
		];
	}

	/**
	 * @test
	 * it should add fields to meta
	 * @dataProvider meta_tags_and_fields
	 */
	public function it_should_add_fields_to_meta( $tag, $field_args, $element_attr, $attr_attr, $expected_field_args ) {
		$this->node->attr( 'element', Argument::type( 'string' ) )->willReturn( $element_attr );
		$this->node->attr( 'attr', Argument::type( 'string' ) )->willReturn( $attr_attr );

		$sut = $this->make_instance();

		$out = $sut->add_field_args( $tag, $field_args, $this->node->reveal() );
		$this->assertEquals( $expected_field_args, $out );
	}

	public function tags_and_markup() {
		$title = $this->default_field_args();

		return [
			[ 'title', $title, 'some-markup-here', 'some-markup-here' ],
			[ 'excerpt', $title, 'some-markup-here', 'some-markup-here' ],
			[ 'content', $title, 'some-markup-here', 'some-markup-here' ],
			[ 'featured-image', $title, 'some-markup-here', 'some-markup-here' ],
			[ 'meta', $title, 'some-markup-here', 'some-markup-here' ],
		];
	}

	/**
	 * @test
	 * it should modify markup for fields
	 * @dataProvider tags_and_markup
	 */
	public function it_should_modify_markup_for_fields( $tag, $field_args, $markup, $expected_output ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected_output, $sut->modify_output( $tag, $field_args, $markup, $this->node->reveal() ) );
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
	private function default_field_args() {
		return array( 'type' => 'text', 'settings' => 'some-setting', 'section' => 'some-section', 'default' => 'Some Value' );
	}

}