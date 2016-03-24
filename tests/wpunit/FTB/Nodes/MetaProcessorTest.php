<?php
namespace FTB\Nodes;
require_once 'ProcessorTestCase.php';
use FTB_Nodes_MetaProcessor as MetaProcessor;
use Prophecy\Argument;

class MetaProcessorTest extends ProcessorTestCase {


	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'FTB_Nodes_MetaProcessor', $this->make_instance() );
	}

	/**
	 * @test
	 * it should return the meta template tag markup when processing
	 */
	public function it_should_return_the_meta_template_tag_markup_when_processing() {
		$this->config->add_field( Argument::type( 'string' ), Argument::any() )->willReturn( true );
		$this->node->nodeValue()->willReturn( 'Something' );
		$this->node->attr( 'var', Argument::type( 'string' ) )->willReturn( '' );
		$this->node->attr( 'type', 'text' )->willReturn( '' );
		$this->template_tags->the_var( '' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-featured_image',
			'section'  => 'some-section',
			'label'    => 'Meta',
			'type'     => 'text',
			'default'  => 'Some Meta',
		];
		$this->transport->add_field_args( 'meta', Argument::type( 'array' ), $this->node->reveal() )->willReturn( $field_args );
		$this->transport->modify_output( 'meta', Argument::type( 'array' ), 'foo', $this->node->reveal() )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$this->assertEquals( 'foo', $sut->process() );
	}

	/**
	 * @test
	 * it should add meta theme mod to the current section
	 */
	public function it_should_add_meta_theme_mod_to_the_current_section() {
		$this->node->nodeValue()->willReturn( 'Some Meta' );
		$this->node->attr( 'var', Argument::type( 'string' ) )->willReturn( 'foo' );
		$this->node->attr( 'type', 'text' )->willReturn( 'bar' );
		$this->template_tags->the_var( 'foo' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-featured_image',
			'section'  => 'some-section',
			'label'    => 'Meta',
			'type'     => 'text',
			'default'  => 'Some Meta',
		];
		$this->config->add_field( 'some-section-meta-foo', $field_args )->shouldBeCalled();
		$this->transport->add_field_args( 'meta', Argument::type( 'array' ), $this->node->reveal() )->willReturn( $field_args );
		$this->transport->modify_output( 'meta', Argument::type( 'array' ), 'foo', $this->node->reveal() )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$sut->set_section( 'some-section' );
		$sut->set_page_slug( 'some_page' );
		$sut->process();
	}

	private function make_instance() {
		return new MetaProcessor( $this->node->reveal(), $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}

}