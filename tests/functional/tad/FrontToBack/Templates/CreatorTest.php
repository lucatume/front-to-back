<?php
namespace tad\FrontToBack\Templates;

class CreatorTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

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

	public function postStatiNotToCreateOn() {
		return [
			[
				'auto-draft', 'pending', 'draft'
			]
		];
	}

	/**
	 * @test
	 * it should not create the template if the post is not good status
	 * @dataProvider postStatiNotToCreateOn
	 */
	public function it_should_not_create_the_template_if_the_post_is_not_good_status( $post_status ) {
		$filesystem = $this->getMockBuilder( '\tad\FrontToBack\Templates\Filesystem' )
		                   ->disableOriginalConstructor()
		                   ->getMock();
		$post       = $this->factory->post->create_and_get( [ 'post_status' => 'auto-draft' ] );

		$sut = new Creator( $filesystem );

		$this->assertFalse( $sut->create_template( $post->ID, $post, false ) );
	}

	/**
	 * @test
	 * it should create the template when the post is first saved
	 */
	public function it_should_create_the_template_when_the_post_is_first_saved_only() {
		$filesystem = $this->getMockBuilder( '\tad\FrontToBack\Templates\Filesystem' )
		                   ->disableOriginalConstructor()
		                   ->getMock();
		$post       = $this->factory->post->create_and_get( [ 'post_status' => 'publish' ] );
		$filesystem->expects( $this->once() )->method( 'duplicate_master_template' )->with( $post->post_name );

		$sut = new Creator( $filesystem );

		$this->assertTrue( $sut->create_template( $post->ID, $post, false ) );
	}

}