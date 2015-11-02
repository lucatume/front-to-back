<?php
namespace tad\FrontToBack\Templates;

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
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		Test::assertInstanceOf( 'tad\FrontToBack\Templates\Filesystem', new Filesystem() );
	}

	/**
	 * @test
	 * it should allow setting the template root folder
	 */
	public function it_should_allow_setting_the_template_root_folder() {
		$sut = new Filesystem( __DIR__ );

		Test::assertEquals( trailingslashit( __DIR__ ), $sut->get_templates_root_folder() );
	}

	/**
	 * @test
	 * it should allow for WP filesystem injection
	 */
	public function it_should_allow_for_wp_filesystem_injection() {
		$wpfs = Test::replace( 'WP_Filesystem_Base' )->get();

		$sut = new Filesystem( __DIR__, $wpfs );

		Test::assertSame( $wpfs, $sut->get_wpfs() );
	}

	/**
	 * @test
	 * it should instance WP Filesystem Base class if not provided
	 */
	public function it_should_instance_wp_filesystem_base_class_if_not_provided() {
		$sut = new Filesystem( __DIR__ );

		Test::assertInstanceOf( '\WP_Filesystem_Base', $sut->get_wpfs() );
	}

	/**
	 * @test
	 * it should not have access if credentials are needed
	 */
	public function it_should_not_have_access_if_credentials_are_needed() {
		Test::replace( 'request_filesystem_credentials', false );

		$sut = new Filesystem();

		Test::assertFalse( $sut->has_access() );
	}

	/**
	 * @test
	 * it should not have access if WP_Filesystem init fails
	 */
	public function it_should_not_have_access_if_wp_filesystem_init_fails() {
		Test::replace( 'WP_Filesystem', false );

		$sut = new Filesystem();

		Test::assertFalse( $sut->has_access() );
	}

	/**
	 * @test
	 * it should have access if credentials are good
	 */
	public function it_should_have_access_if_credentials_are_good() {
		Test::replace( 'request_filesystem_credentials', true );

		$sut = new Filesystem();

		Test::assertTrue( $sut->has_access() );
	}

	/**
	 * @test
	 * it should not call WP filesystem initialization if already set
	 */
	public function it_should_not_call_wp_filesystem_initialization_if_already_set() {
		$request_filesystem_credentials = Test::replace( 'request_filesystem_credentials', true );

		$wpfs = Test::replace( '\WP_Filesystem_Base' )->get();
		$sut  = new Filesystem( __DIR__, $wpfs );

		$request_filesystem_credentials->wasNotCalled();
	}

	/**
	 * @test
	 * it should request credentials for current url
	 */
	public function it_should_request_credentials_for_current_url() {
		$request_filesystem_credentials = Test::replace( 'request_filesystem_credentials', true );
		Test::replace( 'site_url', 'http://example.com' );
		$_SERVER['REQUEST_URI'] = 'foo?some=var';
		$url                    = 'http://example.com/foo?some=var';

		$sut = new Filesystem( __DIR__ );

		$request_filesystem_credentials->wasCalledOnce();
		$request_filesystem_credentials->wasCalledWithOnce( [
			$url,
			'',
			false,
			__DIR__ . '/',
			null
		] );
	}

	/**
	 * @test
	 * it should require credentials again for same url if WP_Filesystem fails on credentials
	 */
	public function it_should_require_credentials_again_for_same_url_if_wp_filesystem_fails_on_credentials() {
		$request_filesystem_credentials = Test::replace( 'request_filesystem_credentials', true );
		Test::replace( 'site_url', 'http://example.com' );
		Test::replace( 'WP_Filesystem', false );
		$_SERVER['REQUEST_URI'] = 'foo?some=var';
		$url                    = 'http://example.com/foo?some=var';

		$sut = new Filesystem( __DIR__ );

		$request_filesystem_credentials->wasCalledTimes( 2 );
		$request_filesystem_credentials->wasCalledWithOnce( [
			$url,
			'',
			false,
			__DIR__ . '/',
			null
		] );
		$request_filesystem_credentials->wasCalledWithOnce( [
			$url,
			'',
			true,
			__DIR__ . '/',
			null
		] );
	}

	/**
	 * @test
	 * it should store valid filesystem credentials in a transient
	 */
	public function it_should_store_valid_filesystem_credentials_in_a_transient() {
		$creds = array(
			'user'     => 'foo',
			'password' => 'baz'
		);
		Test::replace( 'request_filesystem_credentials', $creds );
		Test::replace( 'WP_Filesystem', true );
		$get_transient = Test::replace( 'get_transient' );
		$set_transient = Test::replace( 'set_transient' );

		$sut = new Filesystem();

		$set_transient->wasCalledWithOnce( [
			$sut->get_credentials_transient_key(),
			$creds
		] );
	}

	/**
	 * @test
	 * it should use stored credentials to get filesytem access
	 */
	public function it_should_use_stored_credentials_to_get_filesytem_access() {
		$creds                          = array(
			'user'     => 'foo',
			'password' => 'baz'
		);
		$request_filesystem_credentials = Test::replace( 'request_filesystem_credentials' );
		Test::replace( 'get_transient', $creds );
		$WP_Filesystem = Test::replace( 'WP_Filesystem', true );

		$sut = new Filesystem();

		$request_filesystem_credentials->wasNotCalled();
		$WP_Filesystem->wasCalledwithOnce( [ $creds ] );
	}

	/**
	 * @test
	 * it should delete stored credentials if invalid
	 */
	public function it_should_delete_stored_credentials_if_invalid() {
		Test::replace( 'get_transient', 'foo' );
		$delete_transient = Test::replace( 'delete_transient' );
		$WP_Filesystem    = Test::replace( 'WP_Filesystem', false );

		$sut = new Filesystem();

		$delete_transient->wasCalledWithOnce( [ $sut->get_credentials_transient_key() ] );
	}

	/**
	 * @test
	 * it should not update valid credentials if already stored
	 */
	public function it_should_not_update_valid_credentials_if_already_stored() {
		$creds = array(
			'user'     => 'foo',
			'password' => 'baz'
		);

		Test::replace( 'get_transient', $creds );
		$set_transient = Test::replace( 'set_transient' );
		$WP_Filesystem = Test::replace( 'WP_Filesystem', true );

		$sut = new Filesystem();

		$set_transient->wasNotCalled();
	}

//	/**
//	 * @test
//	 * it should missing master template from templates folder
//	 */
//	public function it_should_spot_missing_master_template_from_templates_folder() {
//		vfsStream::setup( 'root', null, [
//			'wordpress' => [ 'content' => [ 'templates' ] ]
//		] );
//		$templates_root_folder = vfsStream::url( 'root/wordpress/content/templates' );
//
//		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
//		$wpfs = Test::replace( 'WP_Filesystem_Base' )->method( 'exists', false )->get();
//		$sut  = new Filesystem( $templates_root_folder, $wpfs );
//
//		$sut->initialize_wp_filesystem();
//		$this->assertEquals( false, $sut->ensure_master_template() );
//	}
//
//	/**
//	 * @test
//	 * it should duplicate the master template given a post name
//	 */
//	public function it_should_duplicate_the_master_template_given_a_post_name() {
//		vfsStream::setup( 'root', null, [
//			'wordpress' => [ 'content' => [ 'templates' => [ $this->masterTemplateName => '// some content' ] ] ]
//		] );
//		$root = vfsStream::url( 'root/wordpress/content/templates' );
//
//		$sut = new Filesystem( $root );
//		$sut->duplicate_master_template( 'foo' );
//
//		$this->assertFileExists( $root . '/foo.' . $this->templatesExtension );
//	}
//
//	/**
//	 * @test
//	 * it should not duplicate the master template again if already done
//	 */
//	public function it_should_not_duplicate_the_master_template_again_if_already_done() {
//		vfsStream::setup( 'root', null, [
//			'wordpress' => [
//				'content' => [
//					'templates' => [
//						$this->masterTemplateName          => '// foo',
//						'foo.' . $this->templatesExtension => '// bar'
//					]
//				]
//			]
//		] );
//		$root = vfsStream::url( 'root/wordpress/content/templates' );
//
//		$sut = new Filesystem( $root );
//		$sut->duplicate_master_template( 'foo' );
//
//		$file = $root . '/foo.' . $this->templatesExtension;
//		$this->assertFileExists( $file );
//		$this->assertEquals( '// bar', file_get_contents( $file ) );
//	}
//
//	/**
//	 * @test
//	 * it should move templates
//	 */
//	public function it_should_move_templates() {
//		$contents = 'foo file';
//		vfsStream::setup( 'root', null, [ 'templates' => [ 'foo.php' => $contents ] ] );
//		$root = vfsStream::url( 'root/templates' );
//		$sut  = new Filesystem( $root );
//
//		$sut->move_template( 'foo', 'bar' );
//
//		$this->assertFileNotExists( $root . '/foo.php' );
//		$this->assertFileExists( $root . '/bar.php' );
//		$this->assertEquals( $contents, file_get_contents( $root . '/bar.php' ) );
//	}
}