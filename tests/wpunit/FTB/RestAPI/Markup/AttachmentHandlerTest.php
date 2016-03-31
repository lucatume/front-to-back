<?php
namespace FTB\RestAPI\Markup;

use Prophecy\Argument;
use tad\FunctionMocker\FunctionMocker as Test;

class AttachmentHandlerTest extends \Codeception\TestCase\WPRestApiTestCase {

	/**
	 * @var \FTB_Repositories_AttachmentInterface
	 */
	protected $attachment_repository;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		$this->attachment_repository = $this->prophesize( 'FTB_Repositories_AttachmentInterface' );
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

		$this->assertInstanceOf( 'FTB_RestAPI_Markup_AttachmentHandler', $sut );
	}

	/**
	 * @test
	 * it should return 403 if current user cannot edit theme options
	 */
	public function it_should_return_403_if_current_user_cannot_edit_theme_options() {
		Test::replace( 'current_user_can', false );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$this->attachment_repository->find_by_url( Argument::any() )->shouldNotBeCalled();

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$this->assertInstanceOf( 'WP_REST_Response', $out );
		$this->assertEquals( 403, $out->get_status() );
	}

	/**
	 * @test
	 * it should return ftb_get_the_post_thumbnail if user can edit theme options
	 */
	public function it_should_return_ftb_get_the_post_thumbnail_if_user_can_edit_theme_options() {
		Test::replace( 'current_user_can', true );
		Test::replace( 'wp_get_attachment_image', 'foo' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( '' );
		$request->get_param( 'attr' )->willReturn( '' );
		$request->get_param( 'newSrc' )->willReturn( 'http://some.com' );
		$this->attachment_repository->find_by_url( 'http://some.com' )->willReturn( 23 );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$this->assertInternalType( 'string', $out );
	}

	/**
	 * @test
	 * it should return ftb_get_the_post_thumbnaail is image source was not found
	 */
	public function it_should_return_ftb_get_the_post_thumbnaail_is_image_source_was_not_found() {
		Test::replace( 'current_user_can', true );
		Test::replace( 'wp_get_attachment_image', 'foo' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( '' );
		$request->get_param( 'attr' )->willReturn( '' );
		$request->get_param( 'newSrc' )->willReturn( 'http://some.com' );
		$this->attachment_repository->find_by_url( 'http://some.com' )->willReturn( 23 );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$this->assertInternalType( 'string', $out );
	}

	/**
	 * @test
	 * it should get thumbnail with one value size and attr
	 */
	public function it_should_get_thumbnail_with_one_value_size_and_attr() {
		Test::replace( 'current_user_can', true );
		$wp_get_attachment_image = Test::replace( 'wp_get_attachment_image', 'foo' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( 'some-size' );
		$request->get_param( 'attr' )->willReturn( 'class=some-class' );
		$request->get_param( 'newSrc' )->willReturn( 'http://some.com' );
		$this->attachment_repository->find_by_url( 'http://some.com' )->willReturn( 23 );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$expected_attr = [ 'class' => 'some-class', 'data-ftb-attr' => 'class=some-class', 'data-ftb-size' => 'some-size' ];
		$wp_get_attachment_image->wasCalledWithOnce( [ 23, 'some-size', false, $expected_attr ] );
	}

	/**
	 * @test
	 * it should get thumbnail with two value size and attr
	 */
	public function it_should_get_thumbnail_with_two_value_size_and_attr() {
		Test::replace( 'current_user_can', true );
		$wp_get_attachment_image = Test::replace( 'wp_get_attachment_image', 'foo' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( [ 100, 200 ] );
		$request->get_param( 'attr' )->willReturn( 'class=some-class' );
		$request->get_param( 'newSrc' )->willReturn( 'http://some.com' );
		$this->attachment_repository->find_by_url( 'http://some.com' )->willReturn( 23 );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$expected_attr = [ 'class' => 'some-class', 'data-ftb-attr' => 'class=some-class', 'data-ftb-size' => [ '100', '200' ] ];
		$wp_get_attachment_image->wasCalledWithOnce( [ 23, array( 100, 200 ), false, $expected_attr ] );
	}

	/**
	 * @test
	 * it should retun image placeholder if newSrc is empty
	 */
	public function it_should_retun_image_placeholder_if_new_src_is_empty() {
		Test::replace( 'current_user_can', true );
		$wp_get_attachment_image    = Test::replace( 'wp_get_attachment_image' );
		$ftb_get_the_post_thumbnail = Test::replace( 'ftb_get_the_post_thumbnail' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( 'some-size' );
		$request->get_param( 'attr' )->willReturn( 'class=some-class' );
		$request->get_param( 'newSrc' )->willReturn( '' );
		$this->attachment_repository->find_by_url( '' )->willReturn( false );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$wp_get_attachment_image->wasNotCalled();
		$ftb_get_the_post_thumbnail->wasCalledOnce();
	}

	/**
	 * @test
	 * it should return image placeholder if attachment ID was not found
	 */
	public function it_should_return_image_placeholder_if_attachment_id_was_not_found() {
		Test::replace( 'current_user_can', true );
		$wp_get_attachment_image    = Test::replace( 'wp_get_attachment_image' );
		$ftb_get_the_post_thumbnail = Test::replace( 'ftb_get_the_post_thumbnail' );

		/** @var \WP_REST_Request $request */
		$request = $this->prophesize( 'WP_REST_Request' );
		$request->get_param( 'size' )->willReturn( 'some-size' );
		$request->get_param( 'attr' )->willReturn( 'class=some-class' );
		$request->get_param( 'newSrc' )->willReturn( 'http://some.com' );
		$this->attachment_repository->find_by_url( 'http://some.com' )->willReturn( false );
		$this->attachment_repository->find_by_url( 'http://some.com', true )->willReturn( false );

		$sut = $this->make_instance();
		$out = $sut->get_attachment_markup( $request->reveal() );

		$wp_get_attachment_image->wasNotCalled();
		$ftb_get_the_post_thumbnail->wasCalledOnce();
	}

	private function make_instance() {
		return new \FTB_RestAPI_Markup_AttachmentHandler( $this->attachment_repository->reveal() );
	}
}
