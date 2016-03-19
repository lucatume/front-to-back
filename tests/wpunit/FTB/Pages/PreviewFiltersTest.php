<?php
namespace FTB\Pages;

use FTB_Pages_PreviewFilters;
use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class PreviewFiltersTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \tad_DI52_Container
	 */
	protected $container;

	/**
	 * @var \FTB_Customizer_ControlsConfigInterface
	 */
	protected $controls_config;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		$this->container       = $this->prophesize( 'tad_DI52_Container' );
		$this->controls_config = $this->prophesize( 'FTB_Customizer_ControlsConfigInterface' );
	}

	public function tearDown() {
		// your tear down methods here
		Test::tearDown();

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Pages_PreviewFilters', $sut );
	}

	/**
	 * @test
	 * it should not make any filter if page slugs are empty
	 */
	public function it_should_not_make_any_filter_if_page_slugs_are_empty() {
		$this->controls_config->get_page_slugs()->willReturn( [ ] );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->shouldNotBeCalled();

		$sut = $this->make_instance();

		$sut->add_preview_filters();
		$sut->add_save_filters();
	}

	/**
	 * @test
	 * it should make a filter for each page
	 */
	public function it_should_make_a_filter_for_each_page() {
		$this->controls_config->get_page_slugs()->willReturn( [ 'one', 'two' ] );
		/** @var \FTB_Pages_FiltersInterface $filter */
		$filter = $this->prophesize( 'FTB_Pages_FiltersInterface' );
		$filter->set_page_slug( Argument::type( 'string' ) )->shouldBeCalledTimes( 2 );
		$filter->set_page_name( Argument::type( 'string' ) )->shouldBeCalledTimes( 2 );
		$filter->set_custom_fields( Argument::type( 'array' ) )->shouldBeCalledTimes( 2 );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->shouldBeCalledTimes( 2 )->willReturn( $filter->reveal() );

		$sut = $this->make_instance();

		$sut->add_preview_filters();
		$sut->add_save_filters();

		$this->assertEquals( [ 'one' => $filter->reveal(), 'two' => $filter->reveal() ], $sut->get_page_filters() );
	}

	/**
	 * @test
	 * it should hook each filter to the_title filter
	 */
	public function it_should_hook_each_filter_to_the_title_filter() {
		$add_filter = Test::replace( 'add_filter' );
		$this->controls_config->get_page_slugs()->willReturn( [ 'one' ] );
		/** @var \FTB_Pages_FiltersInterface $filter */
		$filter = $this->prophesize( 'FTB_Pages_FiltersInterface' );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->willReturn( $filter->reveal() );

		$sut = $this->make_instance();

		$sut->add_preview_filters();

		$add_filter->wasCalledWithOnce( [ 'the_title', [ $filter->reveal(), 'filter_the_title' ], $this->isType( 'int' ), $this->isType( 'int' ) ] );
	}

	/**
	 * @test
	 * it should hook each filter to the_content filter
	 */
	public function it_should_hook_each_filter_to_the_content_filter() {
		$add_filter = Test::replace( 'add_filter' );
		$this->controls_config->get_page_slugs()->willReturn( [ 'one' ] );
		/** @var \FTB_Pages_FiltersInterface $filter */
		$filter = $this->prophesize( 'FTB_Pages_FiltersInterface' );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->willReturn( $filter->reveal() );

		$sut = $this->make_instance();

		$sut->add_preview_filters();

		$add_filter->wasCalledWithOnce( [ 'the_content', [ $filter->reveal(), 'filter_the_content' ], $this->isType( 'int' ), $this->isType( 'int' ) ] );
	}

	/**
	 * @test
	 * it should hook each filter to get_post_metadata filter
	 */
	public function it_should_hook_each_filter_to_get_post_metadata_filter() {
		$add_filter = Test::replace( 'add_filter' );
		$this->controls_config->get_page_slugs()->willReturn( [ 'one' ] );
		/** @var \FTB_Pages_FiltersInterface $filter */
		$filter = $this->prophesize( 'FTB_Pages_FiltersInterface' );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->willReturn( $filter->reveal() );

		$sut = $this->make_instance();

		$sut->add_preview_filters();

		$add_filter->wasCalledWithOnce( [ 'get_post_metadata', [ $filter->reveal(), 'filter_get_post_metadata' ], $this->isType( 'int' ), $this->isType( 'int' ) ] );
	}

	/**
	 * @test
	 * it should hook the each filter to the save operations
	 */
	public function it_should_hook_the_each_filter_to_the_save_operations() {
		$add_action = Test::replace( 'add_action' );
		$this->controls_config->get_page_slugs()->willReturn( [ 'one' ] );
		/** @var \FTB_Pages_FiltersInterface $filter */
		$filter = $this->prophesize( 'FTB_Pages_FiltersInterface' );
		$this->container->make( 'FTB_Pages_FiltersInterface' )->willReturn( $filter->reveal() );

		$sut = $this->make_instance();

		$sut->add_save_filters();

		$add_action->wasCalledWithOnce( [ 'customize_save_after', [ $filter->reveal(), 'on_customize_save_after' ], $this->isType( 'int' ), $this->isType( 'int' ) ] );
	}

	private function make_instance() {
		return new FTB_Pages_PreviewFilters( $this->container->reveal(), $this->controls_config->reveal() );
	}
}