<?php
namespace FTB\Output;

use FTB_Output_TemplateTags;

class TemplateTagsTest extends \Codeception\TestCase\WPTestCase {

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

	private function make_instance() {
		return new FTB_Output_TemplateTags();
	}

}