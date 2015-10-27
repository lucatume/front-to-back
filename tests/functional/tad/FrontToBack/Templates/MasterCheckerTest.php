<?php
namespace tad\FrontToBack\Templates;

use org\bovigo\vfs\vfsStream;

class MasterCheckerTest extends \WP_UnitTestCase {

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

	/**
	 * @test
	 * it should fail check if templates folder is missing
	 */
	public function it_should_fail_check_if_templates_folder_is_missing() {
		vfsStream::setup( 'templates_folder', null, [
			'path' => [ ]
		] );
		$templates_folder = vfsStream::url( 'templates_folder/path/templates' );

		$sut   = new MasterChecker();
		$check = $sut->check( $templates_folder );

		$this->assertFalse( $check );
	}

	/**
	 * @test
	 * it should fail check if templates folder does not contain master template
	 */
	public function it_should_fail_check_if_templates_folder_does_not_contain_master_template() {
		vfsStream::setup( 'templates_folder', null, [
			'path' => [ 'templates' => [ ] ]
		] );
		$templates_folder = vfsStream::url( 'templates_folder/path/templates' );

		$sut   = new MasterChecker();
		$check = $sut->check( $templates_folder );

		$this->assertFalse( $check );
	}

	/**
	 * @test
	 * it should pass check if templates folder contains master template
	 */
	public function it_should_pass_check_if_templates_folder_contains_master_template() {
		vfsStream::setup( 'templates_folder', null, [
			'path' => [ 'templates' => [ ftb()->get( 'templates/master-template-name' ) => 'some content' ] ]
		] );
		$templates_folder = vfsStream::url( 'templates_folder/path/templates' );

		$sut   = new MasterChecker();
		$check = $sut->check( $templates_folder );

		$this->assertTrue( $check );
	}
}