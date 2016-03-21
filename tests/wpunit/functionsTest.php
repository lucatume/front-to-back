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
}