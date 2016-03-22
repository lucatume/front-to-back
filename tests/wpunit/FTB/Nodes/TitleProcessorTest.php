<?php
namespace FTB\Nodes;
require_once 'ProcessorTestCase.php';
use FTB_Nodes_TitleProcessor as TitleProcessor;
use Prophecy\Argument;

class TitleProcessorTest extends ProcessorTestCase {


	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( 'FTB_Nodes_TitleProcessor', $this->make_instance() );
	}

	/**
	 * @test
	 * it should return the title template tag markup when processing
	 */
	public function it_should_return_the_title_template_tag_markup_when_processing() {
		$this->config->add_field( Argument::type( 'string' ), Argument::any() )->willReturn( true );
		$this->node->nodeValue()->willReturn( 'Something' );
		$this->node->attr( 'before', '' )->willReturn( '' );
		$this->node->attr( 'after', '' )->willReturn( '' );
		$this->template_tags->the_title( '', '' )->willReturn( 'foo' );
		$field_args = [
			'settings' => 'ftb-page-some_page-title',
			'section'  => 'some-section',
			'label'    => 'Title',
			'type'     => 'text',
			'default'  => 'Some Title',
		];
		$this->transport->add_field_args( 'title', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'title', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$this->assertEquals( 'foo', $sut->process() );
	}

	/**
	 * @test
	 * it should add title theme mod to the current section
	 */
	public function it_should_add_title_theme_mod_to_the_current_section() {
		$this->node->nodeValue()->willReturn( 'Some Title' );
		$this->node->attr( 'before', '' )->willReturn( 'before' );
		$this->node->attr( 'after', '' )->willReturn( 'after' );
		$field_args = [
			'settings' => 'ftb-page-some_page-title',
			'section'  => 'some-section',
			'label'    => 'Title',
			'type'     => 'text',
			'default'  => 'Some Title',
		];
		$this->config->add_field( 'some-section-post_title',
			$field_args )->shouldBeCalled();
		$this->template_tags->the_title( 'before', 'after' )->willReturn( 'foo' );
		$this->transport->add_field_args( 'title', Argument::type( 'array' ) )->willReturn( $field_args );
		$this->transport->modify_output( 'title', Argument::type( 'array' ), 'foo' )->willReturn( 'foo' );

		$sut = $this->make_instance();
		$sut->set_section( 'some-section' );
		$sut->set_page_slug( 'some_page' );
		$sut->process();
	}

	private function make_instance() {
		return new TitleProcessor( $this->node->reveal(), $this->template_tags->reveal(), $this->config->reveal(), $this->transport->reveal() );
	}

}