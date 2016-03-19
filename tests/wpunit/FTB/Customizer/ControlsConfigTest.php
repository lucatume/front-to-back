<?php
namespace FTB\Customizer;

use FTB_Customizer_ControlsConfig;

class ControlsConfigTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Adapters_WPInterface
	 */
	protected $wp;
	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $dumper;

	/**
	 * @var string
	 */
	protected $option_name = 'some-option';

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->wp     = $this->prophesize( 'FTB_Adapters_WPInterface' );
		$this->dumper = $this->prophesize( 'FTB_Fields_ConfigDumperInterface' );
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

		$this->assertInstanceOf( 'FTB_Customizer_ControlsConfig', $sut );
	}

	/**
	 * @test
	 * it should return panels
	 */
	public function it_should_return_panels() {
		$panels       = [ 'panel1' => [ 'key' => 'value' ] ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'panels' => $panels ] );

		$sut = $this->make_instance();

		$this->assertEquals( $panels, $sut->get_panels() );
	}

	/**
	 * @test
	 * it should return empty array if panels not set in option
	 */
	public function it_should_return_empty_array_if_panels_not_set_in_option() {
		$panels       = [ ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'something' => $panels ] );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_panels() );
	}

	/**
	 * @test
	 * it should return empty array for panels if option not set
	 */
	public function it_should_return_empty_array_for_panels_if_option_not_set() {
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( $empty_config );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_panels() );
	}

	/**
	 * @test
	 * it should return sections
	 */
	public function it_should_return_sections() {
		$sections     = [ 'section1' => [ 'key' => 'value' ] ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'sections' => $sections ] );

		$sut = $this->make_instance();

		$this->assertEquals( $sections, $sut->get_sections() );
	}

	/**
	 * @test
	 * it should return empty array if sections not set in option
	 */
	public function it_should_return_empty_array_if_sections_not_set_in_option() {
		$sections     = [ ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'something' => $sections ] );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_sections() );
	}

	/**
	 * @test
	 * it should return empty array for sections if option not set
	 */
	public function it_should_return_empty_array_for_sections_if_option_not_set() {
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( $empty_config );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_sections() );
	}

	/**
	 * @test
	 * it should return fields
	 */
	public function it_should_return_fields() {
		$fields       = [ 'field1' => [ 'key' => 'value' ] ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'fields' => $fields ] );

		$sut = $this->make_instance();

		$this->assertEquals( $fields, $sut->get_fields() );
	}

	/**
	 * @test
	 * it should return empty array if fields not set in option
	 */
	public function it_should_return_empty_array_if_fields_not_set_in_option() {
		$fields       = [ ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'something' => $fields ] );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_fields() );
	}

	/**
	 * @test
	 * it should return empty array for fields if option not set
	 */
	public function it_should_return_empty_array_for_fields_if_option_not_set() {
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( $empty_config );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_fields() );
	}

	/**
	 * @test
	 * it should return page slugs
	 */
	public function it_should_return_page_slugs() {
		$page_slugs   = [ 'pageSlug1' => [ 'key' => 'value' ] ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'page_slugs' => $page_slugs ] );

		$sut = $this->make_instance();

		$this->assertEquals( $page_slugs, $sut->get_page_slugs() );
	}

	/**
	 * @test
	 * it should return empty array if page slugs not set in option
	 */
	public function it_should_return_empty_array_if_page_slugs_not_set_in_option() {
		$page_slugs   = [ ];
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( [ 'something' => $page_slugs ] );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_page_slugs() );
	}

	/**
	 * @test
	 * it should return empty array for page slugs if option not set
	 */
	public function it_should_return_empty_array_for_page_slugs_if_option_not_set() {
		$empty_config = [ ];
		$this->dumper->empty_config()->willReturn( $empty_config );
		$this->wp->get_json_decoded_option( $this->option_name, $empty_config )->willReturn( $empty_config );

		$sut = $this->make_instance();

		$this->assertEquals( [ ], $sut->get_page_slugs() );
	}

	private function make_instance() {
		return new FTB_Customizer_ControlsConfig( $this->wp->reveal(), $this->dumper->reveal(), $this->option_name );
	}
}