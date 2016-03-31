<?php
namespace FTB\Output;

use FTB_Output_TemplateTags;
use tad\FunctionMocker\FunctionMocker as Test;

class TemplateTagsTest extends \Codeception\TestCase\WPTestCase {

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

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'FTB_Output_TemplateTags', $sut );
	}

	public function title_tag_inputs() {
		return [
			[ '', '', '<?php the_title(); ?>' ],
			[ 'something', '', "<?php the_title( 'something' ); ?>" ],
			[ 'something', 'else', "<?php the_title( 'something', 'else' ); ?>" ],
			[ '', 'else', "<?php the_title( '', 'else' ); ?>" ],
		];
	}

	/**
	 * @test
	 * it should return proper the_title tag
	 * @dataProvider title_tag_inputs
	 */
	public function it_should_return_proper_the_title_tag( $before, $after, $expected ) {
		$sut = $this->make_instance();
		$this->assertEquals( $expected, $sut->the_title( $before, $after ) );
	}

	/**
	 * @test
	 * it should return the excerpt
	 */
	public function it_should_return_the_excerpt() {
		$sut = $this->make_instance();

		$this->assertEquals( '<?php the_excerpt(); ?>', $sut->the_excerpt() );
	}

	/**
	 * @test
	 * it should call the_content with proper arguments
	 */
	public function it_should_call_the_content_with_proper_arguments() {
		Test::replace( 'ftb_args_string', 'foo' );

		$sut = $this->make_instance();

		$this->assertEquals( '<?php the_content(foo); ?>', $sut->the_content() );
	}

	/**
	 * @test
	 * it should call the_post_thumbnail with proper arguments
	 */
	public function it_should_call_the_post_thumbnail_with_proper_arguments() {
		Test::replace( 'ftb_args_string', 'foo' );

		$sut = $this->make_instance();

		$this->assertEquals( '<?php ftb_the_post_thumbnail(foo); ?>', $sut->the_post_thumbnail() );
	}

	/**
	 * @test
	 * it should return proper markup for the_var
	 */
	public function it_should_return_proper_markup_for_the_var() {
		$sut = $this->make_instance();

		$this->assertEquals( '<?php $foo = get_post_meta( get_the_ID(), \'foo\', true); ?>', $sut->the_var( 'foo' ) );
	}

	private function make_instance() {
		return new FTB_Output_TemplateTags();
	}

}