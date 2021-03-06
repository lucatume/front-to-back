<?php
namespace FTB\Locators;

use FTB_Locators_Page as Page;

class PageTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
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

		$this->assertInstanceOf( 'FTB_Locators_Page', $sut );
	}

	/**
	 * @test
	 * it should return false if trying to get post for non existing page
	 */
	public function it_should_return_false_if_trying_to_get_post_for_non_existing_page() {
		$sut = $this->make_instance();

		$out = $sut->get_some_page();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false if trying to get post for non page page
	 */
	public function it_should_return_false_if_trying_to_get_post_for_non_page_page() {
		$this->factory()->post->create( [ 'post_type' => 'post', 'post_name' => 'some-post' ] );
		$sut = $this->make_instance();

		$out = $sut->get_some_post();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return post if trying to get post for existing post page
	 */
	public function it_should_return_post_if_trying_to_get_post_for_existing_post_page() {
		$this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		$sut = $this->make_instance();

		$out = $sut->get_some_page();

		$this->assertInstanceOf( 'WP_Post', $out );
	}

	/**
	 * @test
	 * it should return false if trying to assert is page and wp_query empty
	 */
	public function it_should_return_false_if_trying_to_assert_is_page_and_wp_query_empty() {
		query_posts( [ 's' => 'something' ] );

		$sut = $this->make_instance();

		$out = $sut->is_some_page();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false if trying to assert is page and query for archive
	 */
	public function it_should_return_false_if_trying_to_assert_is_page_and_query_for_archive() {
		$this->factory()->post->create_many( 10, [ 'post_type' => 'post' ] );
		query_posts( [ 'post_type' => 'post' ] );

		$sut = $this->make_instance();

		$out = $sut->is_some_page();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false if trying to assert is page and page does not exist
	 */
	public function it_should_return_false_if_trying_to_assert_is_page_and_page_does_not_exist() {
		$ids = $this->factory()->post->create_many( 10, [ 'post_type' => 'post' ] );
		query_posts( [ 'p' => reset( $ids ) ] );

		$sut = $this->make_instance();

		$out = $sut->is_some_page();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false if trying to assert is page and page is not page
	 */
	public function it_should_return_false_if_trying_to_assert_is_page_and_page_is_not_page() {
		$id = $this->factory()->post->create( [ 'post_type' => 'post', 'post_name' => 'some-page' ] );
		query_posts( [ 'p' => $id ] );

		$sut = $this->make_instance();

		$out = $sut->is_some_page();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return true if trying to assert is page and page is page
	 */
	public function it_should_return_true_if_trying_to_assert_is_page_and_page_is_page() {
		$id = $this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		query_posts( [ 'p' => $id, 'post_type' => 'page' ] );

		$sut = $this->make_instance();

		$out = $sut->is_some_page();

		$this->assertTrue( $out );
	}

	/**
	 * @test
	 * it should return false is trying to update page and page does not exist
	 */
	public function it_should_return_false_is_trying_to_update_page_and_page_does_not_exist() {
		$sut = $this->make_instance();

		$out = $sut->update_some_page( [ 'post_title' => 'Some Title' ] );

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false if trying to update page and page is not page
	 */
	public function it_should_return_false_if_trying_to_update_page_and_page_is_not_page() {
		$this->factory()->post->create( [ 'post_type' => 'post', 'post_name' => 'some-page' ] );
		$sut = $this->make_instance();

		$out = $sut->update_some_page( [ 'post_title' => 'Some Title' ] );

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return wp_update_post exit status if trying to update page
	 */
	public function it_should_return_wp_update_post_exit_status_if_trying_to_update_page() {
		$this->factory()->post->create( [ 'post_type' => 'page', 'post_name' => 'some-page' ] );
		$sut = $this->make_instance();

		$out = $sut->update_some_page( [ 'post_title' => 'Some Title' ] );

		$this->assertNotFalse( $out );
		$this->assertInternalType( 'int', $out );
	}

	private function make_instance() {
		return new Page();
	}

	/**
	 * @test
	 * it should return false if trying to get queried post and global wp_query is not set
	 */
	public function it_should_return_false_if_trying_to_get_queried_post_and_global_wp_query_is_not_set() {
		global $wp_query;
		$wp_query = null;

		$sut = $this->make_instance();

		$this->assertFalse( $sut->get_queried_post() );
	}

	/**
	 * @test
	 * it should return false if global wp_query has no posts
	 */
	public function it_should_return_false_if_global_wp_query_has_no_posts() {
		global /** @var \WP_Query $wp_query */
		$wp_query;
		$_wp_query = $this->prophesize( 'WP_Query' );
		$_wp_query->get_posts()->willReturn( [ ] );
		$wp_query = $_wp_query->reveal();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->get_queried_post() );
	}

	/**
	 * @test
	 * it should return false if global wp_query has more than one post
	 */
	public function it_should_return_false_if_global_wp_query_has_more_than_one_post() {
		global /** @var \WP_Query $wp_query */
		$wp_query;
		$_wp_query = $this->prophesize( 'WP_Query' );
		$_wp_query->get_posts()->willReturn( $this->factory()->post->create_many( 3 ) );
		$wp_query = $_wp_query->reveal();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->get_queried_post() );
	}

	/**
	 * @test
	 * it should return the post if global wp_query has one
	 */
	public function it_should_return_the_post_if_global_wp_query_has_one() {
		global /** @var \WP_Query $wp_query */
		$wp_query;
		$_wp_query = $this->prophesize( 'WP_Query' );
		$post      = $this->factory()->post->create_and_get();
		$_wp_query->get_posts()->willReturn( [ $post ] );
		$wp_query = $_wp_query->reveal();

		$sut = $this->make_instance();

		$this->assertEquals( $post, $sut->get_queried_post() );
	}

	/**
	 * @test
	 * it should throw for non supported operations
	 */
	public function it_should_throw_for_non_supported_operations() {
		$this->setExpectedException('BadMethodCallException');
		
		$sut = $this->make_instance();
		
		$sut->non_supported_method();
	}
}
