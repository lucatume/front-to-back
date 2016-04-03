<?php
namespace FTB\Templates;

class PostprocessorTest extends \Codeception\TestCase\WPTestCase {

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
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Templates_Postprocessor', $sut );
	}

	/**
	 * @test
	 * it should not replace anything in markup without modified php tags
	 */
	public function it_should_not_replace_anything_in_markup_without_modified_php_tags() {
		$in = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<p>something</p>
</body>
</html>
HTML;

		$sut = $this->make_instance();

		$this->assertEquals( $in, $sut->postprocess( $in ) );
	}

	/**
	 * @test
	 * it should replace neutered php comment tag
	 */
	public function it_should_replace_neutered_php_comment_tag() {
		$in = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<!--?php f(); ?-->
</body>
</html>
HTML;

		$expected = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<?php f(); ?>
</body>
</html>
HTML;

		$sut = $this->make_instance();
		$out = $sut->postprocess( $in );

		$this->assertTrue( html_strcasecmp( $expected, $out ) );
	}

	private function make_instance() {
		return new \FTB_Templates_Postprocessor();
	}

}