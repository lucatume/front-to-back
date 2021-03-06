<?php
use tad\FunctionMocker\FunctionMocker as Test;

class functionsTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
		Test::replace( 'get_stylesheet_directory', '/theme' );
		Test::replace( 'wp_cache_get', false );
		Test::replace( 'wp_cache_set' );
	}

	public function tearDown() {
		// your tear down methods here
		Test::tearDown();

		// then
		parent::tearDown();
	}

	public function paths() {
		return [
			[ '', '/theme/ftb-templates' ],
			[ 'foo', '/theme/ftb-templates/foo.php' ],
			[ 'foo/bar', '/theme/ftb-templates/foo/bar.php' ],
			[ 'foo/bar-baz', '/theme/ftb-templates/foo/bar-baz.php' ],
			[ 'foo/bar.php', '/theme/ftb-templates/foo/bar.php' ],
			[ 'foo/bar.tmpl', '/theme/ftb-templates/foo/bar.tmpl' ],
		];
	}

	/**
	 * ftb_template_path
	 *
	 * @dataProvider paths
	 */
	public function test_ftb_template_path( $in, $expected ) {
		$this->assertEquals( $expected, ftb_template_path( $in ) );
	}

	public function templates() {
		return [
			[ 'foo', [ ], 'foo' ],
			[ 'foo', [ 'bar' => 'baz' ], 'foo' ],
			[ 'foo{{bar}}', [ 'bar' => 'some' ], 'foosome' ],
			[ 'foo{{bar}}', [ 'baz' => 'some' ], 'foo{{bar}}' ],
			[ 'foo{{bar}}', [ 'bar' => 'some', 'baz' => 'more' ], 'foosome' ],
			[ 'foo{{bar}}{{baz}}', [ 'bar' => 'some', 'baz' => 'more' ], 'foosomemore' ],
			[ 'foo{{bar}}{{baz}}', [ 'baz' => 'more' ], 'foo{{bar}}more' ],
			[ 'foo{{bar}}', [ 'bar' => [ 'more' ] ], 'foo{{bar}}' ],
		];
	}

	/**
	 * ftb_template
	 *
	 * @dataProvider templates
	 */
	public function test_ftb_template( $template, $data, $expected ) {
		$this->assertEquals( $expected, ftb_template( $template, $data ) );
	}

	public function args_and_strings() {
		return [
			[ '', '' ],
			[ '', array() ],
			[ '', null ],
			[ '', false ],
			[ " 'foo' ", 'foo' ],
			[ " 'foo' ", array( 'foo' ) ],
			[ " 'foo', 'bar' ", array( 'foo', 'bar' ) ],
			[ " 'foo', 'bar', 'baz' ", array( 'foo', 'bar', 'baz' ) ],
			[ " 'foo', '', 'baz' ", array( 'foo', '', 'baz' ) ],
			[ " '', '', 'baz' ", array( '', '', 'baz' ) ],
			[ "", array( '', '', '' ) ],
			[ " 'foo' ", array( 'foo', '', '' ) ],
			[ " 'foo', 'bar' ", array( 'foo', 'bar', '' ) ],
			[ " 'foo', 'bar' ", array( 'foo', 'bar' ) ],
			[ " '', 'bar' ", array( '', 'bar' ) ],
			[ " '', array('some'=>'var') ", array( '', "array('some'=>'var')" ) ],
		];

	}

	/**
	 * ftb_args_string
	 *
	 * @dataProvider args_and_strings
	 */
	public function test_ftb_args_string( $expected, $args ) {
		$this->assertEquals( $expected, ftb_args_string( $args ) );
	}

	public function text_vars() {
		return [
			[ [ ], '' ],
			[ 'foo', 'foo' ],
			[ [ 'foo', 'bar' ], 'foo&bar' ],
			[ [ 'foo' => 'bar', 'key' => 'value' ], 'foo=bar&key=value' ],
			[ [ 'foo' => 'bar' ], 'foo=bar' ],
			[ [ 'foo' => [ 'bar', 'baz' ] ], 'foo[]=bar&foo[]=baz' ],
			[ [ 'foo' => [ 'bar', 'baz' ], 'key' => 'value' ], 'foo[]=bar&foo[]=baz&key=value' ],
		];
	}

	/**
	 * ftb_parse_text_var
	 *
	 * @dataProvider text_vars
	 */
	public function test_ftb_parse_text_array( $expected, $in ) {
		$this->assertEquals( $expected, ftb_parse_text_var( $in ) );
	}

	public function ftb_textualize_var_inputs() {
		return [
			[ '', '' ],
			[ 'foo', "'foo'" ],
			[ 23, 23 ],
			[ array( 'foo' ), "array( 'foo' )" ],
			[ array( 'foo', 'bar' ), "array( 'foo', 'bar' )" ],
			[ array( 'foo' => 'bar', 'bar' => 23 ), "array( 'foo' => 'bar', 'bar' => 23 )" ],
			[ 'foo', "'foo'" ],
		];

	}

	/**
	 * ftb_textualize_var
	 *
	 * @dataProvider ftb_textualize_var_inputs
	 */
	public function test_ftb_textualize_var( $in, $expected ) {
		$this->assertEquals( $expected, ftb_textualize_var( $in ) );
	}

	public function query_strings() {
		return [
			[ true, '', '', '' ],
			[ true, array(), '', '' ],
			[ true, array(), array(), '' ],
			[ true, array(), 'some=var', 'some=var' ],
			[ true, array(), 'some=var&foo=bar', 'some=var&foo=bar' ],
			[ true, array( 'foo' => 'bar' ), 'some=var', 'foo=bar&some=var' ],
			[ true, array( 'foo' => 'bar', 'bar' => 'baz' ), 'some=var', 'foo=bar&bar=baz&some=var' ],
			[ true, array( 'foo' => 'bar', 'bar' => 'baz' ), 'some=var&another=one', 'foo=bar&bar=baz&some=var&another=one' ],
			[ true, array( 'class' => 'one' ), 'class=two', 'class%5B0%5D=one&class%5B1%5D=two' ],
			[ false, array( 'class' => 'one' ), 'class=two', 'class=two' ],
			[ false, 'class=one', 'class=two', 'class=two' ],
			[ false, array( 'class' => 'one' ), array( 'class' => 'two' ), 'class=two' ],
			[ true, array( 'class' => 'one' ), array( 'class' => 'two' ), 'class%5B0%5D=one&class%5B1%5D=two' ],
			[ true, array( 'class' => 'one' ), array( 'class' => 'one' ), 'class=one' ],
		];
	}

	/**
	 * ftb_merge_query_strings
	 *
	 * @dataProvider query_strings
	 */
	public function test_ftb_merge_query_strings( $merge, $original, $add, $expected ) {
		$this->assertEquals( $expected, ftb_merge_query_strings( $original, $add, $merge ) );
	}

	public function array_query_strings() {
		return [
			[ true, '', '', array() ],
			[ true, array(), '', [ ] ],
			[ true, array(), array(), [ ] ],
			[ true, array(), 'some=var', [ 'some' => 'var' ] ],
			[ true, array(), 'some=var&foo=bar', [ 'some' => 'var', 'foo' => 'bar' ] ],
			[ true, array( 'foo' => 'bar' ), 'some=var', [ 'foo' => 'bar', 'some' => 'var' ] ],
			[ true, array( 'foo' => 'bar', 'bar' => 'baz' ), 'some=var', [ 'foo' => 'bar', 'bar' => 'baz', 'some' => 'var' ] ],
			[ true, array( 'foo' => 'bar', 'bar' => 'baz' ), 'some=var&another=one', [ 'foo' => 'bar', 'bar' => 'baz', 'some' => 'var', 'another' => 'one' ] ],
			[ true, array( 'class' => 'one' ), 'class=two', [ 'class' => [ 'one', 'two' ] ] ],
			[ false, array( 'class' => 'one' ), 'class=two', [ 'class' => 'two' ] ],
			[ false, 'class=one', 'class=two', [ 'class' => 'two' ] ],
			[ false, array( 'class' => 'one' ), array( 'class' => 'two' ), [ 'class' => 'two' ] ],
			[ true, array( 'class' => 'one' ), array( 'class' => 'two' ), [ 'class' => [ 'one', 'two' ] ] ],
			[ true, array( 'class' => 'one' ), array( 'class' => 'one' ), [ 'class' => 'one' ] ],
		];
	}

	/**
	 * ftb_merge_query_string_to_array
	 *
	 * @dataProvider array_query_strings
	 */
	public function test_ftb_merge_query_string_to_array( $merge, $original, $add, $expected ) {
		$this->assertEquals( $expected, ftb_merge_query_string_to_array( $original, $add, $merge ) );
	}
}