<?php
namespace FTB\Pages;

use FTB\Test\WP_Customize_Manager;
use FTB\Test\WP_Customize_Setting;
use FTB_Pages_Filters as Filters;
use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class FiltersTest extends \Codeception\TestCase\WPTestCase {

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
	 * it should not filter the title if the page is not the target one with global post
	 */
	public function it_should_not_filter_the_title_if_the_page_is_not_the_target_one_with_global_post() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-title' ? 'Some post title' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$not_this_post_id = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		global $post;
		$post = get_post( $not_this_post_id );
		$out  = $sut->filter_the_title( 'Original title' );

		$this->assertEquals( 'Original title', $out );
	}

	/**
	 * @test
	 * it should filter the title if the page is the target one with global post
	 */
	public function it_should_filter_the_title_if_the_page_is_the_target_one_with_global_post() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		global $post;
		$post = get_post( $post_id );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-title' ? 'Some post title' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$out = $sut->filter_the_title( 'Original title' );

		$this->assertEquals( 'Some post title', $out );
	}

	/**
	 * @test
	 * it should not filter the content if the page is not the target one with global post
	 */
	public function it_should_not_filter_the_content_if_the_page_is_not_the_target_one_with_global_post() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-content' ? 'Some post content' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$not_this_post_id = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		global $post;
		$post = get_post( $not_this_post_id );
		$out  = $sut->filter_the_content( 'Original content' );

		$this->assertEquals( 'Original content', $out );
	}

	/**
	 * @test
	 * it should filter the content if the page is the target one with global post
	 */
	public function it_should_filter_the_content_if_the_page_is_the_target_one_with_global_post() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		global $post;
		$post = get_post( $post_id );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-content' ? 'Some post content' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$out = $sut->filter_the_content( 'Original content' );

		$this->assertEquals( 'Some post content', $out );
	}

	/**
	 * @test
	 * it should not filter the title if the page is not the target one
	 */
	public function it_should_not_filter_the_title_if_the_page_is_not_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-title' ? 'Some post title' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$not_this_post_id = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		$out              = $sut->filter_the_title( 'Original title', $not_this_post_id );

		$this->assertEquals( 'Original title', $out );
	}

	/**
	 * @test
	 * it should filter the title if the page is the target one
	 */
	public function it_should_filter_the_title_if_the_page_is_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-title' ? 'Some post title' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$out = $sut->filter_the_title( 'Original title', $post_id );

		$this->assertEquals( 'Some post title', $out );
	}

	/**
	 * @test
	 * it should not filter the content if the page is not the target one
	 */
	public function it_should_not_filter_the_content_if_the_page_is_not_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-content' ? 'Some post content' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$not_this_post_id = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		$out              = $sut->filter_the_content( 'Original content', $not_this_post_id );

		$this->assertEquals( 'Original content', $out );
	}

	/**
	 * @test
	 * it should filter the content if the page is the target one
	 */
	public function it_should_filter_the_content_if_the_page_is_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-content' ? 'Some post content' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );

		$out = $sut->filter_the_content( 'Original content', $post_id );

		$this->assertEquals( 'Some post content', $out );
	}

	/**
	 * @test
	 * it should not filter the post meta data if the page is not the target one
	 */
	public function it_should_not_filter_the_post_meta_data_if_the_page_is_not_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-some_field' ? 'Some foo text' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->set_custom_fields( [ 'some_field' => 'some_field' ] );

		$not_this_post_id = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		update_post_meta( $not_this_post_id, 'some_field', 'Original value' );
		$out = $sut->filter_get_post_metadata( 'Original value', $not_this_post_id, 'some_field' );
		$this->assertEquals( 'Original value', $out );
	}

	/**
	 * @test
	 * it should filter the metadata if the page is the target one
	 */
	public function it_should_filter_the_metadata_if_the_page_is_the_target_one() {
		$post_id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		Test::replace( 'get_theme_mod',
			function ( $id, $default ) {
				return $id === 'ftb-page-some_page-some_field' ? 'Some foo text' : $default;
			} );

		$sut = $this->make_instance();
		$sut->set_page_slug( 'some_page' );
		$sut->set_page_name( 'some-page' );
		$sut->set_custom_fields( [ 'some_field' => 'some_field' ] );

		$this->assertEquals( 'Some foo text', $sut->filter_get_post_metadata( 23, $post_id, 'some_field' ) );
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

	private function make_instance() {
		return new Filters( $this->wp->reveal(), $this->page_locator );
	}
}