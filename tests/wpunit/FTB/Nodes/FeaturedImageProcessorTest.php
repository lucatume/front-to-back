<?php
namespace FTB\Nodes;
require_once 'ProcessorTestCase.php';
use FTB_Nodes_FeaturedImageProcessor as FeaturedImageProcessor;
use Prophecy\Argument;

class FeaturedImageProcessorTest extends ProcessorTestCase {


	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'FTB_Nodes_FeaturedImageProcessor', $this->make_instance() );
	}

	/**
	 * @test
	 * it should return the post_thumbnail template tag markup when processing
	 */
	public function it_should_return_the_post_thumbnail_template_tag_markup_when_processing() {
		$this->config->add_field( Argument::type( 'string' ), Argument::any() )->willReturn( true );
		$this->node->nodeValue()->willReturn( 'Something' );
		$this->node->attr( 'size', '' )->willReturn( '' );
		$this->node->attr( 'attr', '' )->willReturn( '' );
		$this->template_tags->the_post_thumbnail( '', '' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-featured_image',
			'section'  => 'some-section',
			'label'    => 'FeaturedImage',
			'type'     => 'text',
			'default'  => 'Some FeaturedImage',
		];
		$this->transport->add_field_args( 'featured_image', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'featured_image', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$this->assertEquals( 'foo', $sut->process() );
	}

	/**
	 * @test
	 * it should add post_thumbnail theme mod to the current section
	 */
	public function it_should_add_post_thumbnail_theme_mod_to_the_current_section() {
		$this->node->nodeValue()->willReturn( 'Some FeaturedImage' );
		$this->node->attr( 'size', '' )->willReturn( 'foo' );
		$this->node->attr( 'attr', '' )->willReturn( 'bar' );
		$this->template_tags->the_post_thumbnail( 'foo', 'bar' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-meta-featured_image',
			'section'  => 'some-section',
			'label'    => 'FeaturedImage',
			'type'     => 'text',
			'default'  => 'Some FeaturedImage',
		];
		$this->config->add_field( 'some-section-meta-featured_image', $field_args )->shouldBeCalled();
		$this->transport->add_field_args( 'featured_image', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'featured_image', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$sut->set_section( 'some-section' );
		$sut->set_page_slug( 'some_page' );
		$sut->process();
	}

	private function make_instance() {
		return new FeaturedImageProcessor( $this->node->reveal(), $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}

}
