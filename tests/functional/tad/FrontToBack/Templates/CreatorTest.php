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
				'auto-draft',
				'pending',
				'draft'
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

	/**
	 * @test
	 * it should rename the template if the post name changes
	 */
	public function it_should_rename_the_template_if_the_post_name_changes() {
		$post_before      = $this->factory->post->create_and_get();
		$post_name_before = $post_before->post_name;
		$post_name_after = 'new-post-name';

		$fs = $this->getMock( '\tad\FrontToBack\Templates\Filesystem' );
		$fs->expects( $this->once() )->method( 'duplicate_master_template' )->with( $post_name_before );
		$fs->expects( $this->once() )->method( 'move_template' )->with( $post_name_before, $post_name_after );
		$sut     = new Creator( $fs );
		$created = $sut->create_template( $post_before->ID, $post_before );

		$this->assertTrue( $created );

		$post_after            = clone $post_before;
		$post_after->post_name = $post_name_after;

		$sut->move_template( $post_before->ID, $post_after, $post_before );
	}

	/**
	 * @test
	 * it should not move the template if the post name did not change
	 */
	public function it_should_not_move_the_template_if_the_post_name_did_not_change() {
		$post_before      = $this->factory->post->create_and_get();
		$post_name_before = $post_before->post_name;

		$fs = $this->getMock( '\tad\FrontToBack\Templates\Filesystem' );
		$fs->expects( $this->once() )->method( 'duplicate_master_template' )->with( $post_name_before );
		$fs->expects( $this->never() )->method( 'move_template' );
		$sut     = new Creator( $fs );

		$created = $sut->create_template( $post_before->ID, $post_before );

		$this->assertTrue( $created );

		$sut->move_template( $post_before->ID, $post_before, $post_before );
	}
}