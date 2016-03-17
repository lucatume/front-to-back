<?php
namespace FTB\Templates;

use Prophecy\Argument;

class ReaderTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Nodes_ProcessorFactory
	 */
	protected $node_processor_factory;

	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->node_processor_factory = $this->prophesize( 'FTB_Nodes_ProcessorFactory' );
		$this->config                 = $this->prophesize( 'FTB_Fields_ConfigDumperInterface' );
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

		$this->assertInstanceOf( 'FTB_Templates_Reader', $sut );
	}

	/**
	 * @test
	 * it should return the original template content if there are no ftb tags in it
	 */
	public function it_should_return_the_original_template_content_if_there_are_no_ftb_tags_in_it() {
		$sut               = $this->make_instance();
		$template_contents = <<< TEMPLATE
<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1 class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</h1>
	</main>
	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
TEMPLATE;
		$sut->set_template_contents( $template_contents );

		$out = $sut->read_and_process( 'about-us' );

		$this->assertTrue( xml_strcasecmp( $template_contents, $out ) );
	}

	/**
	 * @test
	 * it should replace the ftb-title tag with WP title template tag
	 */
	public function it_should_replace_the_ftb_title_tag_with_wp_title_template_tag() {
		/** @var \FTB_Nodes_ProcessorInterface $title_processor */
		$title_processor = $this->prophesize( 'FTB_Nodes_ProcessorInterface' );
		$title_processor->process()->willReturn( '<?php the_title(); ?>' );
		$this->config->get_section_id( 'about_us' )->willReturn( 'some-section' );
		$this->config->add_content_section( 'about_us' )->shouldBeCalled();
		$title_processor->set_section( 'some-section' )->shouldBeCalled();
		$this->node_processor_factory->make_for_type( 'title', Argument::any() )->willReturn( $title_processor->reveal() );
		$sut               = $this->make_instance();
		$template_contents = <<< TEMPLATE
<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1 class="entry-header">
			<ftb-title>About us</ftb-title>
		</h1>
	</main>
	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
TEMPLATE;

		$expected_template_contents = <<< TEMPLATE
<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1 class="entry-header">
			<?php the_title(); ?>
		</h1>
	</main>
	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
TEMPLATE;

		$sut->set_template_contents( $template_contents );

		$out = $sut->read_and_process( 'about-us' );

		$this->assertTrue( xml_strcasecmp( $expected_template_contents, $out ) );
	}

	private function make_instance() {
		return new \FTB_Templates_Reader( $this->node_processor_factory->reveal(), $this->config->reveal() );
	}
}