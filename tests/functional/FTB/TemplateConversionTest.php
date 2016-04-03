<?php
namespace FTB;

class TemplateConversionTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \FTB_Nodes_ProcessorFactory
	 */
	protected $node_processor_factory;

	/**
	 * @var \FTB_Fields_ConfigDumperInterface
	 */
	protected $config;

	/**
	 * @var \FTB_Templates_PreprocessorInterface
	 */
	protected $preprocessor;

	/**
	 * @var \FTB_Templates_PostprocessorInterface
	 */
	protected $postprocessor;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->container = include( codecept_root_dir( 'bootstrap.php' ) );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
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

		$this->assertTrue( html_strcasecmp( $template_contents, $out ) );
	}

	public function title_tag_args_provider() {
		return [
			[ '<ftb-title>About us</ftb-title>', '<?php the_title(); ?>' ],
			[ '<ftb-title before="pre">About us</ftb-title>', "<?php the_title( 'pre' ); ?>" ],
			[ '<ftb-title after="post">About us</ftb-title>', "<?php the_title( '', 'post' ); ?>" ],
			[ '<ftb-title before="pre" after="post">About us</ftb-title>', "<?php the_title( 'pre', 'post' ); ?>" ],
			[ '<ftb-excerpt >Foo</ftb-excerpt>', "<?php the_excerpt(); ?>" ],
			[ '<ftb-content more-link-text="foo">Foo</ftb-content>', "<?php the_content( 'foo' ); ?>" ],
			[ '<ftb-content strip-teaser="bar">Foo</ftb-content>', "<?php the_content( '', 'bar' ); ?>" ],
			[ '<ftb-content more-link-text="foo" strip-teaser="bar">Foo</ftb-content>', "<?php the_content( 'foo', 'bar' ); ?>" ],
			[ '<ftb-meta var="foo">Foo</ftb-meta>', "<?php \$foo = get_post_meta( get_the_ID(), 'foo', true ); ?>" ],
			[ '<ftb-meta var="foo" type="bar">Foo</ftb-meta>', "<?php \$foo = get_post_meta( get_the_ID(), 'foo', true ); ?>" ],
			[ '<ftb-meta var="foo-bar" >Foo</ftb-meta>', "<?php \$foo_bar = get_post_meta( get_the_ID(), 'foo-bar', true ); ?>" ],
			[ '<ftb-featured-image>Foo</ftb-featured-image>', "<?php ftb_the_post_thumbnail(); ?>" ],
			[ '<ftb-featured-image size="foo">Foo</ftb-featured-image>', "<?php ftb_the_post_thumbnail( 'foo' ); ?>" ],
			[ '<ftb-featured-image size="[0]=100&[1]=200">Foo</ftb-featured-image>', "<?php ftb_the_post_thumbnail( array( 100, 200 ), array( 'data-ftb-size' => '[0]=100&[1]=200' ) ); ?>" ],
			[ '<ftb-featured-image attr="[foo]=bar">Foo</ftb-featured-image>', "<?php ftb_the_post_thumbnail( '', array( 'foo' => 'bar', 'data-ftb-attr' => '[foo]=bar') ); ?>" ],
		];

	}

	/**
	 * @test
	 * it should replace the ftb tags with WP title template tag
	 * @dataProvider title_tag_args_provider
	 */
	public function it_should_replace_the_ftb_tags_with_wp_title_template_tag( $in_tag, $out_tag ) {
		$sut = $this->make_instance();

		$template_contents = <<< TEMPLATE
<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<h1 class="entry-header">
			$in_tag
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
			$out_tag
		</h1>
	</main>
	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
TEMPLATE;

		$sut->set_template_contents( $template_contents );

		$out = $sut->read_and_process( 'about-us' );

		$sep     = '=====================================';
		$message = sprintf( "FTB tag compilation failure:\n$sep\n%s\n$sep\nshould have compiled to\n$sep\n%s\n$sep\ncompiled to instead\n$sep\n%s", $template_contents,
			$expected_template_contents, $out );
		$this->assertTrue( html_strcasecmp( $expected_template_contents, $out ), $message );
	}

	/**
	 * @return \FTB_Templates_Reader
	 */
	protected function make_instance() {
		$sut = $this->container->make( 'FTB_Templates_Reader' );

		return $sut;
	}

}