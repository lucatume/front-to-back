<?php
namespace FTB\Fields;

use FTB_Fields_KirkiConfigDumper as KirkiConfigDumper;
use Prophecy\Argument;

class ConfigDumperTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Adapters_WPInterface
	 */
	protected $wp;

	/**
	 * @var \FTB_Locators_PageInterface
	 */
	protected $page_locator;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->wp           = $this->prophesize( 'FTB_Adapters_WPInterface' );
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
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Fields_KirkiConfigDumper', $sut );
	}

	/**
	 * @test
	 * it should write the config to the database
	 */
	public function it_should_write_the_config_to_the_database() {
		$config = KirkiConfigDumper::get_empty_config();
		$this->wp->save_configuration( $config )->shouldBeCalled();

		$sut = $this->make_instance();

		$sut->save_configuration();
	}

	/**
	 * @test
	 * it should allow adding panels
	 */
	public function it_should_allow_adding_panels() {
		$config                         = KirkiConfigDumper::get_empty_config();
		$panel_config                   = [
			'priority'    => 100,
			'title'       => 'My Panel',
			'description' => 'Some Panel',
		];
		$config['panels']['some-panel'] = $panel_config;
		$this->wp->save_configuration( $config )->shouldBeCalled();

		$sut = $this->make_instance();
		$sut->add_panel( 'some-panel', $panel_config );

		$sut->save_configuration();
	}

	/**
	 * @test
	 * it should throw if panel ID is not string
	 */
	public function it_should_throw_if_panel_id_is_not_string() {
		$config       = KirkiConfigDumper::get_empty_config();
		$panel_config = [
			'priority'    => 100,
			'title'       => 'My Panel',
			'description' => 'Some Panel',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_panel( 134, $panel_config );
	}

	/**
	 * @test
	 * it should throw if panel config does not contain a title
	 */
	public function it_should_throw_if_panel_config_does_not_contain_a_title() {
		$config       = KirkiConfigDumper::get_empty_config();
		$panel_config = [
			'priority'    => 100,
			'description' => 'Some Panel',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_panel( 'some-panel', $panel_config );
	}

	/**
	 * @test
	 * it should allow removing a panel
	 */
	public function it_should_allow_removing_a_panel() {
		$config       = KirkiConfigDumper::get_empty_config();
		$panel_config = [
			'title' => 'Some Panel'
		];

		$sut = $this->make_instance();
		$sut->add_panel( 'some-panel', $panel_config );
		$this->assertTrue( $sut->has_panel( 'some-panel' ) );

		$sut->remove_panel( 'some-panel' );
		$this->assertFalse( $sut->has_panel( 'some-panel' ) );
	}

	/**
	 * @test
	 * it should allow adding sections
	 */
	public function it_should_allow_adding_sections() {
		$config                             = KirkiConfigDumper::get_empty_config();
		$section_config                     = [
			'priority'    => 100,
			'title'       => 'My Panel',
			'description' => 'Some Panel',
		];
		$config['sections']['some-section'] = $section_config;
		$this->wp->save_configuration( $config )->shouldBeCalled();

		$sut = $this->make_instance();
		$sut->add_section( 'some-section', $section_config );

		$sut->save_configuration();
	}

	/**
	 * @test
	 * it should throw if section ID is not string
	 */
	public function it_should_throw_if_section_id_is_not_string() {
		$config         = KirkiConfigDumper::get_empty_config();
		$section_config = [
			'priority'    => 100,
			'title'       => 'My Panel',
			'description' => 'Some Panel',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_section( 134, $section_config );
	}

	/**
	 * @test
	 * it should throw if section config does not contain a title
	 */
	public function it_should_throw_if_section_config_does_not_contain_a_title() {
		$config         = KirkiConfigDumper::get_empty_config();
		$section_config = [
			'priority'    => 100,
			'description' => 'Some Panel',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_section( 'some-section', $section_config );
	}

	/**
	 * @test
	 * it should allow removing a section
	 */
	public function it_should_allow_removing_a_section() {
		$config         = KirkiConfigDumper::get_empty_config();
		$section_config = [
			'title' => 'Some Panel'
		];

		$sut = $this->make_instance();
		$sut->add_section( 'some-section', $section_config );
		$this->assertTrue( $sut->has_section( 'some-section' ) );

		$sut->remove_section( 'some-section' );
		$this->assertFalse( $sut->has_section( 'some-section' ) );
	}

	/**
	 * @test
	 * it should allow adding a field
	 */
	public function it_should_allow_adding_fields() {
		$config                         = KirkiConfigDumper::get_empty_config();
		$field_config                   = [
			'settings' => 'some-setting',
			'section'  => 'some-section',
		];
		$config['fields']['some-field'] = $field_config;
		$this->wp->save_configuration( $config )->shouldBeCalled();

		$sut = $this->make_instance();
		$sut->add_field( 'some-field', $field_config );

		$sut->save_configuration();
	}

	/**
	 * @test
	 * it should throw if field ID is not string
	 */
	public function it_should_throw_if_field_id_is_not_string() {
		$config       = KirkiConfigDumper::get_empty_config();
		$field_config = [
			'settings' => 'some-setting',
			'section'  => 'some-section',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_field( 134, $field_config );
	}

	/**
	 * @test
	 * it should throw if field config does not contain settings
	 */
	public function it_should_throw_if_field_config_does_not_contain_a_title() {
		$config       = KirkiConfigDumper::get_empty_config();
		$field_config = [
			'section' => 'some-section',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_field( 'some-field', $field_config );
	}

	/**
	 * @test
	 * it should throw if field config does not contain a section
	 */
	public function it_should_throw_if_field_config_does_not_contain_a_section() {
		$config       = KirkiConfigDumper::get_empty_config();
		$field_config = [
			'settings' => 'some-setting',
		];

		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = $this->make_instance();
		$sut->add_field( 'some-field', $field_config );
	}

	/**
	 * @test
	 * it should allow removing a field
	 */
	public function it_should_allow_removing_a_field() {
		$config       = KirkiConfigDumper::get_empty_config();
		$field_config = [
			'settings' => 'some-setting',
			'section'  => 'some-section',
		];

		$sut = $this->make_instance();
		$sut->add_field( 'some-field', $field_config );
		$this->assertTrue( $sut->has_field( 'some-field' ) );

		$sut->remove_field( 'some-field' );
		$this->assertFalse( $sut->has_field( 'some-field' ) );
	}

	/**
	 * @test
	 * it should allow adding the page content section
	 */
	public function it_should_allow_adding_the_page_content_section() {
		$config                                                   = KirkiConfigDumper::get_empty_config();
		$section_config                                           = [
			'title'           => 'Page Content',
			'active_callback' => 'is_some_page_page',
		];
		$config['sections']['ftb-page-some_page-section-content'] = $section_config;
		$this->wp->save_configuration( $config )->shouldBeCalled();

		$sut = $this->make_instance();
		$sut->add_content_section( 'some_page' );
		$sut->save_configuration();
	}

	private function make_instance() {
		return new KirkiConfigDumper( $this->wp->reveal(), $this->page_locator->reveal() );
	}

}