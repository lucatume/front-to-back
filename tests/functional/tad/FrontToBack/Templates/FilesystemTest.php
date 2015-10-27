<?php
namespace tad\FrontToBack\Templates;

use org\bovigo\vfs\vfsStream;
use tad\FunctionMocker\FunctionMocker as Test;

class FilesystemTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

	protected $masterTemplateName;

	protected $templatesExtension;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		$this->masterTemplateName = ftb()->get( 'templates/master-template-name' );
		$this->templatesExtension = ftb()->get( 'templates/extension' );
	}

	public function tearDown() {
		// your tear down methods here
		Test::tearDown();
		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should missing master template from templates folder
	 */
	public function it_should_spot_missing_master_template_from_templates_folder() {
		vfsStream::setup( 'root', null, [
			'wordpress' => [ 'content' => [ 'templates' ] ]
		] );
		$templates_root_folder = vfsStream::url( 'root/wordpress/content/templates' );

		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		$wpfs = Test::replace( 'WP_Filesystem_Base' )->method( 'exists', false )->get();
		$sut  = new Filesystem( $templates_root_folder, $wpfs );

		$sut->initialize_wp_filesystem();
		$this->assertEquals( false, $sut->ensure_master_template() );
	}

	/**
	 * @test
	 * it should duplicate the master template given a post name
	 */
	public function it_should_duplicate_the_master_template_given_a_post_name() {
		vfsStream::setup( 'root', null, [
			'wordpress' => [ 'content' => [ 'templates' => [ $this->masterTemplateName => '// some content' ] ] ]
		] );
		$root = vfsStream::url( 'root/wordpress/content/templates' );

		$sut = new Filesystem( $root );
		$sut->duplicate_master_template( 'foo' );

		$this->assertFileExists( $root . '/foo.' . $this->templatesExtension );
	}

	/**
	 * @test
	 * it should not duplicate the master template again if already done
	 */
	public function it_should_not_duplicate_the_master_template_again_if_already_done() {
		vfsStream::setup( 'root', null, [
			'wordpress' => [
				'content' => [
					'templates' => [
						$this->masterTemplateName => '// foo', 'foo.' . $this->templatesExtension => '// bar'
					]
				]
			]
		] );
		$root = vfsStream::url( 'root/wordpress/content/templates' );

		$sut = new Filesystem( $root );
		$sut->duplicate_master_template( 'foo' );

		$file = $root . '/foo.' . $this->templatesExtension;
		$this->assertFileExists( $file );
		$this->assertEquals( '// bar', file_get_contents( $file ) );
	}

}