<?php
namespace FTB\Fields;

use FTB_Fields_KirkiConfig as KirkiConfig;
use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class KirkiConfigTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var string
	 */
	protected $config_id = 'some_config';

	/**
	 * @var string
	 */
	protected $prefix = 'some-prefix';

	/**
	 * @var string
	 */
	protected $page_slug = 'some_page';

	/**
	 * @var \FTB_Locators_PageInterface
	 */
	protected $page_locator;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->page_locator = $this->prophesize( 'FTB_Locators_PageInterface' );
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
		$this->assertInstanceOf( 'FTB_Fields_KirkiConfig', $this->make_instance() );
	}

	/**
	 * @test
	 * it should add the section when add section called
	 */
	public function it_should_add_the_section_when_constructing() {
		$this->prefix    = 'ftb-page';
		$this->config_id = 'some-config';
		$add_section     = Test::replace( 'Kirki::add_section' );

		$sut = $this->make_instance();
		$sut->add_content_section( 'some_page' );

		$add_section->wasCalledWithOnce( [
			'ftb-page-some_page-section-content',
			[
				'title'           => 'Content',
				'active_callback' => [ $this->page_locator->reveal(), 'is_some_page' ],
				'priority'        => 150,
			]
		] );
	}

	/**
	 * @test
	 * it should default the fields settings
	 */
	public function it_should_default_the_field_settings() {
		$this->prefix    = 'ftb-page';
		$this->config_id = 'some-config';
		$add_field       = Test::replace( 'Kirki::add_field', true );

		$sut = $this->make_instance();
		$sut->add_content_section( 'some_page' );
		$sut->add_field( [
			'settings' => 'some_setting'
		] );

		$expected_settings = [ 'section' => 'ftb-page-some_page-section-content', 'settings' => 'ftb-page-some_page-some_setting' ];
		$add_field->wasCalledWithOnce( [ 'some-config', $expected_settings ] );
	}

	private function make_instance() {
		return new KirkiConfig( $this->prefix, $this->config_id, $this->page_locator->reveal() );
	}

}