<?php
namespace FTB\Repositories;

use Prophecy\Argument;

class AttachmentTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \wpdb
	 */
	protected $wpdb;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->wpdb = $this->prophesize( 'wpdb' );
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

		$this->assertInstanceOf( 'FTB_Repositories_Attachment', $sut );
	}

	/**
	 * @test
	 * it should throw if url is not string
	 */
	public function it_should_throw_if_url_is_not_string() {
		$sut = $this->make_instance();

		$this->expectException( 'InvalidArgumentException' );

		$sut->find_by_url( 2313123 );
	}

	/**
	 * @test
	 * it should throw if refetch is not bool
	 */
	public function it_should_throw_if_refetch_is_not_bool() {
		$sut = $this->make_instance();

		$this->expectException( 'InvalidArgumentException' );

		$sut->find_by_url( 'http://some.com', 12313 );
	}

	/**
	 * @test
	 * it should return false if guid is not found
	 */
	public function it_should_return_false_if_guid_is_not_found() {
		$this->wpdb->get_blog_prefix( Argument::any() )->willReturn( 'wp_' );
		$this->wpdb->get_results( Argument::type( 'string' ) )->willReturn( [ ] );

		$sut = $this->make_instance();

		$out = $sut->find_by_url( 'http://some.com' );

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should cache guids after first request
	 */
	public function it_should_cache_guids_after_first_request() {
		$this->wpdb->get_blog_prefix( Argument::any() )->willReturn( 'wp_' );
		$this->wpdb->get_results( Argument::type( 'string' ) )->willReturn( [ ] )->shouldBeCalledTimes( 1 );

		$sut = $this->make_instance();

		$sut->find_by_url( 'http://some.com' );
		$sut->find_by_url( 'http://some.com' );
		$sut->find_by_url( 'http://some.com' );
	}

	/**
	 * @test
	 * it should return the attachment ID if found in the database
	 */
	public function it_should_return_the_attachment_id_if_found_in_the_database() {
		$this->wpdb->get_blog_prefix( Argument::any() )->willReturn( 'wp_' );
		$this->wpdb->get_results( Argument::type( 'string' ) )->willReturn( [ (object) [ 'ID' => 23, 'guid' => 'http://some.com' ] ] )->shouldBeCalledTimes( 1 );

		$sut = $this->make_instance();

		$out = $sut->find_by_url( 'http://some.com' );

		$this->assertEquals( 23, $sut->find_by_url( 'http://some.com' ) );
	}

	private function make_instance() {
		return new \FTB_Repositories_Attachment( $this->wpdb->reveal() );
	}
}
