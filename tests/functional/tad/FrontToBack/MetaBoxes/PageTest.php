<?php
namespace tad\FrontToBack\MetaBoxes;

use tad\FunctionMocker\FunctionMocker as Test;

class PageTest extends \WP_UnitTestCase {

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
	 * it should not call new_cmb2_box if template not exists
	 */
	public function it_should_not_call_new_cmb_2_box_if_template_not_exists() {
		$new_cmb2_metabox = Test::replace( 'new_cmb2_box' );
		$pageTemplate     = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                        ->method( 'exists', false )
		                        ->get();

		$sut = new Page( $pageTemplate );
		$sut->add_page_meta_boxes();

		$new_cmb2_metabox->wasNotCalled();
	}

	/**
	 * @test
	 * it should not call new_cmb2_box if template has no meta fields
	 */
	public function it_should_not_call_new_cmb_2_box_if_template_has_no_meta_fields() {
		$new_cmb2_metabox = Test::replace( 'new_cmb2_box' );
		$pageTemplate     = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                        ->method( 'exists', true )
		                        ->method( 'has_fields', false )
		                        ->get();

		$sut = new Page( $pageTemplate );
		$sut->add_page_meta_boxes();

		$new_cmb2_metabox->wasNotCalled();
	}

	/**
	 * @test
	 * it should call new_cmb2_box id template has meta fields
	 */
	public function it_should_call_new_cmb_2_box_id_template_has_meta_fields() {
		$new_cmb2_metabox = Test::replace( 'new_cmb2_box' );
		$pageTemplate     = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                        ->method( 'exists', true )
		                        ->method( 'has_fields', true )
		                        ->method( 'get_fields', array() )
		                        ->get();
		$_GET['id']       = 2;
		$sut              = new Page( $pageTemplate );
		$sut->add_page_meta_boxes();

		$new_cmb2_metabox->wasCalledOnce();
	}

	/**
	 * @test
	 * it should not call new_cmb2_box if id not set
	 */
	public function it_should_not_call_new_cmb_2_box_if_id_not_set() {
		$expected         = [
			'id'           => 'ftb_foo-bar_fields_metabox', 'title' => __( 'Fields', 'ftb' ),
			'object_types' => array( 'page', ), 'show_on' => array( 'key' => 'id', 'value' => 9 ),
			'context'      => 'normal', 'priority' => 'high', 'show_names' => true, // Show field names on the left
		];
		$_GET['id']       = null;
		$new_cmb2_metabox = Test::replace( 'new_cmb2_box' );
		$pageTemplate     = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                        ->method( 'exists', true )
		                        ->method( 'has_fields', true )
		                        ->get();

		$sut = new Page( $pageTemplate );
		$sut->add_page_meta_boxes();

		$new_cmb2_metabox->wasNotCalled();
	}

	/**
	 * @test
	 * it should call new_cmb2_box with page based id
	 */
	public function it_should_call_new_cmb_2_box_with_page_based_id() {
		$expected         = [
			'id'           => 'ftb_foo-bar_fields_metabox', 'title' => __( 'Fields', 'ftb' ),
			'object_types' => array( 'page', ), 'show_on' => array( 'key' => 'id', 'value' => 3 ),
			'context'      => 'normal', 'priority' => 'high', 'show_names' => true, // Show field names on the left
		];
		$_GET['id']       = 3;
		$new_cmb2_metabox = Test::replace( 'new_cmb2_box' );
		$pageTemplate     = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                        ->method( 'exists', true )
		                        ->method( 'has_fields', true )
		                        ->method( 'get_name', 'foo-bar' )
		                        ->method( 'get_fields', array() )
		                        ->get();

		$sut = new Page( $pageTemplate );
		$sut->add_page_meta_boxes();

		$new_cmb2_metabox->wasCalledWithOnce( [ $expected ] );
	}

	/**
	 * @test
	 * it should add text type field to meta box
	 */
	public function it_should_add_text_type_field_to_meta_box() {
		$_GET['id']   = 3;
		$fields       = [ new Field( 'text', 'field_id', 'Field name' ) ];
		$pageTemplate = Test::replace( '\\tad\\FrontToBack\\Templates\\TemplateInterface' )
		                    ->method( 'exists', true )
		                    ->method( 'has_fields', true )
		                    ->method( 'get_name', 'foo-bar' )
		                    ->method( 'get_fields', $fields )
		                    ->get();
		$expected     = [
			'name' => 'Field name', 'desc' => '', 'id' => 'field_id', 'type' => 'text',
		];
		$mb           = Test::replace( 'CMB2' )->method( 'add_field' )->get();
		Test::replace( 'new_cmb2_box', $mb );
		$sut = new Page( $pageTemplate );

		$sut->add_page_meta_boxes();

		$mb->wasCalledWithOnce( [ $expected ], 'add_field' );
	}
}