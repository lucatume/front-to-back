<?php
namespace FTB\Templates;

class PreprocessorTest extends \Codeception\TestCase\WPTestCase {

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

		$this->assertInstanceOf( 'FTB_Templates_Preprocessor', $sut );
	}

	/**
	 * @test
	 * it should not modify a string not containing php tags
	 */
	public function it_should_not_modify_a_string_not_containing_php_tags() {
		$in  = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>

</body>
</html>
HTML;
		$sut = $this->make_instance();

		$out = $sut->preprocess( $in );

		$this->assertHtmlEquals( $in, $out );
	}

	/**
	 * @test
	 * it should neuter php tags in html code
	 */
	public function it_should_neuter_php_tags_in_html_code() {
		$in = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<p><?php f(); ?></p>
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
<p><!--?php f(); ?--></p>
</body>
</html>
HTML;
		$sut      = $this->make_instance();

		$out = $sut->preprocess( $in );

		$this->assertHtmlEquals( $expected, $out );
	}

	/**
	 * @test
	 * it should replace php tags in attributes
	 */
	public function it_should_replace_php_tags_in_attributes() {
		$in = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<p class="<?php f(); ?>"></p>
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
<p class="<!--?php f(); ?-->"></p>
</body>
</html>
HTML;
		$sut      = $this->make_instance();

		$out = $sut->preprocess( $in );

		$this->assertHtmlEquals( $expected, $out );
	}

	/**
	 * @test
	 * it should replace php tags in html tags
	 */
	public function it_should_replace_php_tags_in_html_tags() {
		$in = <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<p <?php f(); ?>></p>
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
<p data-ftb-php=" f(); "></p>
</body>
</html>
HTML;
		$sut      = $this->make_instance();

		$out = $sut->preprocess( $in );

		$this->assertHtmlEquals( $expected, $out );
	}

	private function make_instance() {
		return new \FTB_Templates_Preprocessor();
	}

	private function assertHtmlEquals( $expected, $actual, $message = '' ) {
		$expectedString = preg_replace( '/\\s/', ' ', $expected );
		$actualString   = preg_replace( '/\\s/', ' ', $actual );

		$this->assertEquals( $expectedString, $actualString, $message );
	}

}