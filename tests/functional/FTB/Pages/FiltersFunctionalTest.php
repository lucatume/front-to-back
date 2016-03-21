<?php
namespace FTB\Pages;

use FTB\Test\WP_Customize_Manager;
use FTB\Test\WP_Customize_Setting;
use FTB_Pages_Filters as Filters;
use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class FiltersFunctionalTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * @var \FTB_Locators_PageInterface
	 */
	protected $page_locator;

	/**
	 * @var \FTB_Adapters_WPInterface
	 */
	protected $wp;


	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		$this->page_locator = new \FTB_Locators_Page();
		$this->wp           = $this->prophesize( 'FTB_Adapters_WPInterface' );
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

		$this->assertInstanceOf( 'FTB_Pages_Filters', $sut );
	}

	/**
	 * @test
	 * it should filter the thumbnail id with an attachment ID
	 */
	public function it_should_filter_the_thumbnail_id_with_an_attachment_id() {
		$post_id        = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		$attachment_url = 'some/path';
		$attachment_id  = 42;

		Test::replace( 'get_theme_mod',
			function ( $id, $default ) use ( $attachment_url, $attachment_id ) {
				return $id === 'ftb-page-some_page-featured_image' ? $attachment_url : $default;
			} );
		$this->wp->get_attachment_id_from_url( $attachment_url )->willReturn( $attachment_id );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->set_custom_fields( [ '_thumbnail_id' => 'featured_image' ] );

		$this->assertEquals( $attachment_id, $sut->filter_get_post_metadata( 23, $post_id, '_thumbnail_id' ) );
	}

	/**
	 * @test
	 * it should set the post title on save
	 */
	public function it_should_set_the_post_title_on_save() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		/** @var \FTB\Test\WP_Customize_Setting $setting */
		$setting = $this->prophesize( '\FTB\Test\WP_Customize_Setting' );
		$setting->value()->willReturn( 'Some title' );
		$setting->id_data()->willReturn( [ 'base' => 'ftb-page-some_page-featured_image' ] );
		/** @var \FTB\Test\WP_Customize_Manager $wp_customize */
		$wp_customize = $this->prophesize( '\FTB\Test\WP_Customize_Manager' );
		$wp_customize->settings()->willReturn( [ $setting->reveal() ] );
		$wp_customize->get_setting( Argument::not( 'ftb-page-some_page-title' ) )->willReturn( null );
		$wp_customize->get_setting( 'ftb-page-some_page-title' )->willReturn( $setting->reveal() );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->on_customize_save_after( $wp_customize->reveal() );

		$this->assertEquals( 'Some title', get_post( $post_id )->post_title );
	}

	/**
	 * @test
	 * it should set the post content on save
	 */
	public function it_should_set_the_post_content_on_save() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		/** @var \FTB\Test\WP_Customize_Setting $setting */
		$setting = $this->prophesize( '\FTB\Test\WP_Customize_Setting' );
		$setting->value()->willReturn( 'Some post content' );
		$setting->id_data()->willReturn( [ 'base' => 'ftb-page-some_page-featured_image' ] );
		/** @var \FTB\Test\WP_Customize_Manager $wp_customize */
		$wp_customize = $this->prophesize( '\FTB\Test\WP_Customize_Manager' );
		$wp_customize->settings()->willReturn( [ $setting->reveal() ] );
		$wp_customize->get_setting( Argument::not( 'ftb-page-some_page-content' ) )->willReturn( null );
		$wp_customize->get_setting( 'ftb-page-some_page-content' )->willReturn( $setting->reveal() );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->on_customize_save_after( $wp_customize->reveal() );

		$this->assertEquals( 'Some post content', get_post( $post_id )->post_content );
	}

	/**
	 * @test
	 * it should set the post meta on save
	 */
	public function it_should_set_the_post_meta_on_save() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		/** @var \FTB\Test\WP_Customize_Setting $setting */
		$setting = $this->prophesize( '\FTB\Test\WP_Customize_Setting' );
		$setting->value()->willReturn( 'some_value' );
		$setting->id_data()->willReturn( [ 'base' => 'ftb-page-some_page-some_field' ] );
		/** @var \FTB\Test\WP_Customize_Manager $wp_customize */
		$wp_customize = $this->prophesize( '\FTB\Test\WP_Customize_Manager' );
		$wp_customize->settings()->willReturn( [ $setting->reveal() ] );
		$wp_customize->get_setting( Argument::not( 'ftb-page-some_page-some_field' ) )->willReturn( null );
		$wp_customize->get_setting( 'ftb-page-some_page-some_field' )->willReturn( $setting->reveal() );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->set_custom_fields( [ '_some_field' => 'some_field' ] );
		$sut->on_customize_save_after( $wp_customize->reveal() );

		$this->assertEquals( 'some_value', get_post_meta( $post_id, '_some_field', true ) );
	}

	/**
	 * @test
	 * it should set the thumbnail_id on save when saving the featured image
	 */
	public function it_should_set_the_thumbnail_id_on_save_when_saving_the_featured_image() {
		$post_id        = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		$attachment_id  = $this->factory()->attachment->create_upload_object( codecept_data_dir( 'images/featured-image.jpg' ) );
		$attachment_url = get_attached_file( $attachment_id );
		/** @var \FTB\Test\WP_Customize_Setting $setting */
		$setting = $this->prophesize( '\FTB\Test\WP_Customize_Setting' );
		$setting->value()->willReturn( $attachment_url );
		$setting->id_data()->willReturn( [ 'base' => 'ftb-page-some_page-featured_image' ] );
		/** @var \FTB\Test\WP_Customize_Manager $wp_customize */
		$wp_customize = $this->prophesize( '\FTB\Test\WP_Customize_Manager' );
		$wp_customize->settings()->willReturn( [ $setting->reveal() ] );
		$wp_customize->get_setting( Argument::not( 'ftb-page-some_page-featured_image' ) )->willReturn( null );
		$wp_customize->get_setting( 'ftb-page-some_page-featured_image' )->willReturn( $setting->reveal() );
		$this->wp->get_attachment_id_from_url( $attachment_url )->willReturn( $attachment_id );


		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->set_custom_fields( [ '_thumbnail_id' => 'featured_image' ] );
		$sut->on_customize_save_after( $wp_customize->reveal() );

		$this->assertEquals( $attachment_id, get_post_meta( $post_id, '_thumbnail_id', true ) );
	}

	private function make_instance() {
		return new Filters( $this->wp->reveal(), $this->page_locator );
	}
}