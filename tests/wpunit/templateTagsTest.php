<?php

use tad\FunctionMocker\FunctionMocker as Test;

class templateTagsTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Test::setUp();
	}

	public function tearDown() {
		// your tear down methods here
		Test::tearDown();

		// then
		parent::tearDown();
	}

	public function thumbnail_sizes_and_attrs() {
		return [
			[ '<img class="one" data-some="some" data-ftb-size="" data-ftb-attr="class=one&data-some=some" src=""/>', '', array( 'class' => 'one', 'data-some' => 'some' ) ],
			[ '<img foo="bar" data-ftb-size="some-size" data-ftb-attr="foo=bar" src=""/>', 'some-size', array( 'foo' => 'bar' ) ],
		];
	}

	/**
	 * ftb_the_post_thumbnail with no thumbnail
	 *
	 * @dataProvider thumbnail_sizes_and_attrs
	 */
	public function test_ftb_the_post_thumbnail_with_no_thumbnail( $expected, $size, $attr ) {
		global $post;
		$post = $this->factory()->post->create();

		$this->assertEquals( $expected, ftb_get_the_post_thumbnail( $size, $attr ) );
	}

	public function thumbnail_sizes_and_attrs_with_thumbnail() {
		return [
			[ '', '', '', [ 'data-ftb-size' => '', 'data-ftb-attr' => '' ] ],
			[ 'some-size', '', 'some-size', [ 'data-ftb-size' => 'some-size', 'data-ftb-attr' => '' ] ],
			[ [ 100, 200 ], '', [ 100, 200 ], [ 'data-ftb-size' => '0=100&1=200', 'data-ftb-attr' => '' ] ],
		];
	}

	/**
	 * ftb_the_post_thumbnail with thumbnail
	 *
	 * @dataProvider thumbnail_sizes_and_attrs_with_thumbnail
	 */
	public function test_ftb_the_post_thumbnail_with_thumbnail( $size, $attr, $expected_size, $expected_attr ) {
		$get_the_post_thumbnail = Test::replace( 'get_the_post_thumbnail' );
		Test::replace( 'has_post_thumbnail', true );

		ftb_get_the_post_thumbnail( $size, $attr );

		$get_the_post_thumbnail->wasCalledWithOnce( [null, $expected_size, $expected_attr ] );
	}
}