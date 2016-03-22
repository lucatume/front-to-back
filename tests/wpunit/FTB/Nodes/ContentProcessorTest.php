<?php
namespace FTB\Nodes;
require_once 'ProcessorTestCase.php';
use FTB_Nodes_ContentProcessor as ContentProcessor;
use Prophecy\Argument;

class ContentProcessorTest extends ProcessorTestCase {


	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'FTB_Nodes_ContentProcessor', $this->make_instance() );
	}

	/**
	 * @test
	 * it should return the content template tag markup when processing
	 */
	public function it_should_return_the_content_template_tag_markup_when_processing() {
		$this->config->add_field( Argument::type( 'string' ), Argument::any() )->willReturn( true );
		$this->node->nodeValue()->willReturn( 'Something' );
		$this->node->attr( 'more-link-text', '' )->willReturn( '' );
		$this->node->attr( 'strip-teaser', '' )->willReturn( '' );
		$this->template_tags->the_content( '', '' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-content',
			'section'  => 'some-section',
			'label'    => 'Content',
			'type'     => 'text',
			'default'  => 'Some Content',
		];
		$this->transport->add_field_args( 'content', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'content', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$this->assertEquals( 'foo', $sut->process() );
	}

	/**
	 * @test
	 * it should add content theme mod to the current section
	 */
	public function it_should_add_content_theme_mod_to_the_current_section() {
		$this->node->nodeValue()->willReturn( 'Some Content' );
		$this->node->attr( 'more-link-text', '' )->willReturn( 'before' );
		$this->node->attr( 'strip-teaser', '' )->willReturn( 'after' );
		$field_args = [
			'settings' => 'ftb-page-some_page-content',
			'section'  => 'some-section',
			'label'    => 'Content',
			'type'     => 'text',
			'default'  => 'Some Content',
		];
		$this->config->add_field( 'some-section-post_content',
			$field_args )->shouldBeCalled();
		$this->template_tags->the_content( 'before', 'after' )->willReturn( 'foo' );
		$this->transport->add_field_args( 'content', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'content', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$sut->set_section( 'some-section' );
		$sut->set_page_slug( 'some_page' );
		$sut->process();
	}

	private function make_instance() {
		return new ContentProcessor( $this->node->reveal(), $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}

}