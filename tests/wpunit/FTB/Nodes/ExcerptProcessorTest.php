<?php
namespace FTB\Nodes;
require_once 'ProcessorTestCase.php';
use FTB_Nodes_ExcerptProcessor as ExcerptProcessor;
use Prophecy\Argument;

class ExcerptProcessorTest extends ProcessorTestCase {


	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'FTB_Nodes_ExcerptProcessor', $this->make_instance() );
	}

	/**
	 * @test
	 * it should return the excerpt template tag markup when processing
	 */
	public function it_should_return_the_excerpt_template_tag_markup_when_processing() {
		$this->config->add_field( Argument::type( 'string' ), Argument::any() )->willReturn( true );
		$this->node->nodeValue()->willReturn( 'Something' );
		$this->template_tags->the_excerpt()->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-excerpt',
			'section'  => 'some-section',
			'label'    => 'Excerpt',
			'type'     => 'text',
			'default'  => 'Some Excerpt',
		];
		$this->transport->add_field_args( 'excerpt', Argument::type( 'array' ),$this->node->reveal() )->willReturn( $field_args );
		$this->transport->modify_output( 'excerpt', Argument::type( 'array' ), 'foo',$this->node->reveal())->willReturn( 'foo' );

		$sut = $this->make_instance();
		$this->assertEquals( 'foo', $sut->process() );
	}

	/**
	 * @test
	 * it should add excerpt theme mod to the current section
	 */
	public function it_should_add_excerpt_theme_mod_to_the_current_section() {
		$this->node->nodeValue()->willReturn( 'Some Excerpt' );
		$field_args = [
			'settings' => 'ftb-page-some_page-excerpt',
			'section'  => 'some-section',
			'label'    => 'Excerpt',
			'type'     => 'text',
			'default'  => 'Some Excerpt',
		];
		$this->config->add_field( 'some-section-post_excerpt',
			$field_args )->shouldBeCalled();
		$this->template_tags->the_excerpt()->willReturn( 'foo' );
		$this->transport->add_field_args( 'excerpt', Argument::type( 'array' ), $this->node->reveal() )->willReturn( $field_args );
		$this->transport->modify_output( 'excerpt', Argument::type( 'array' ), 'foo',$this->node->reveal() )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$sut->set_section( 'some-section' );
		$sut->set_page_slug( 'some_page' );
		$sut->process();
	}

	private function make_instance() {
		return new ExcerptProcessor( $this->node->reveal(), $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}

}