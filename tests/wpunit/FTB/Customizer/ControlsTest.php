<?php
namespace FTB\Customizer;

use FTB_Customizer_Controls;
use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class ControlsTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Customizer_ControlsConfigInterface
	 */
	protected $config;

	/**
	 * @var \FTB_Locators_PageInterface
	 */
	protected $page_locator;

	/**
	 * @var string
	 */
	protected $config_id;


	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		Test::replace( 'Kirki::add_config' );
		Test::replace( 'Kirki::add_panel' );
		Test::replace( 'Kirki::add_section' );
		Test::replace( 'Kirki::add_field' );
		$this->config       = $this->prophesize( 'FTB_Customizer_ControlsConfigInterface' );
		$this->page_locator = $this->prophesize( 'FTB_Locators_PageInterface' );
		$this->config_id    = 'some-config';
	}

	public function tearDown() {
		// your tear down methods here

		// then
		Test::tearDown();
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Customizer_Controls', $sut );
	}

	/**
	 * @test
	 * it should not configure Kirki if there are no fields
	 */
	public function it_should_not_configure_kirki_if_there_are_no_fields() {
		$add_config = Test::replace( 'Kirki::add_config' );
		$this->config->get_panels()->willReturn( array() );
		$this->config->get_sections()->willReturn( array() );
		$this->config->get_fields()->willReturn( array() );

		$sut = $this->make_instance();
		$sut->register_controls();

		$add_config->wasNotCalled();
	}

	/**
	 * @test
	 * it should add the Kirki config when registering controls
	 */
	public function it_should_add_the_kirki_config_when_registering_controls() {
		$add_config = Test::replace( 'Kirki::add_config' );
		$this->config->get_panels()->willReturn( [ 'panel1' => [ 'key' => 'value' ] ] );
		$this->config->get_sections()->willReturn( [ 'section1' => [ 'key' => 'value' ] ] );
		$this->config->get_fields()->willReturn( [ 'field1' => [ 'key' => 'value' ] ] );

		$sut = $this->make_instance();
		$sut->register_controls();

		$add_config->wasCalledWithOnce( [ $this->config_id, $this->isType( 'array' ) ] );
	}

	/**
	 * @test
	 * it should set the active callback on panels if specified
	 */
	public function it_should_set_the_active_callback_on_panels_if_specified() {
		$add_panel = Test::replace( 'Kirki::add_panel' );
		$this->config->get_panels()->willReturn( [ 'panel1' => [ 'active_callback' => 'is_some_page' ] ] );
		$this->config->get_sections()->willReturn( [ 'section1' => [ 'key' => 'value' ] ] );
		$this->config->get_fields()->willReturn( [ 'field1' => [ 'key' => 'value' ] ] );

		$sut = $this->make_instance();
		$sut->register_controls();

		$add_panel->wasCalledWithOnce( [ 'panel1', [ 'active_callback' => [ $this->page_locator->reveal(), 'is_some_page' ] ] ] );
	}

	/**
	 * @test
	 * it should set the active callback on sections if specified
	 */
	public function it_should_set_the_active_callback_on_sections_if_specified() {
		$add_section = Test::replace( 'Kirki::add_section' );
		$this->config->get_panels()->willReturn( [ 'panel1' => [ 'key' => 'value' ] ] );
		$this->config->get_sections()->willReturn( [ 'section1' => [ 'active_callback' => 'is_some_page' ] ] );
		$this->config->get_fields()->willReturn( [ 'field1' => [ 'key' => 'value' ] ] );

		$sut = $this->make_instance();
		$sut->register_controls();

		$add_section->wasCalledWithOnce( [ 'section1', [ 'active_callback' => [ $this->page_locator->reveal(), 'is_some_page' ] ] ] );
	}

	/**
	 * @test
	 * it should set the active callback on fields if specified
	 */
	public function it_should_set_the_active_callback_on_fields_if_specified() {
		$add_field = Test::replace( 'Kirki::add_field' );
		$this->config->get_panels()->willReturn( [ 'panel1' => [ 'key' => 'value' ] ] );
		$this->config->get_sections()->willReturn( [ 'section1' => [ 'key' => 'value' ] ] );
		$this->config->get_fields()->willReturn( [ 'field1' => [ 'active_callback' => 'is_some_page' ] ] );

		$sut = $this->make_instance();
		$sut->register_controls();

		$add_field->wasCalledWithOnce( [ $this->config_id, [ 'active_callback' => [ $this->page_locator->reveal(), 'is_some_page' ] ] ] );
	}

	private function make_instance() {
		return new FTB_Customizer_Controls( $this->config->reveal(), $this->page_locator->reveal(), $this->config_id );
	}

}
