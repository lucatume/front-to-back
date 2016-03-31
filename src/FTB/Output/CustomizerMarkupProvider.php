<?php


class FTB_Output_CustomizerMarkupProvider implements FTB_Output_CustomizerMarkupProviderInterface {

	/**
	 * @var WP_Post[]
	 */
	protected $pages;

	/**
	 * @var Handlebars_Engine
	 */
	private $handlebars;

	public function __construct( Handlebars_Engine $handlebars ) {
		$this->handlebars = $handlebars;
	}

	/**
	 * @return string The Theme Customizer page nav menu HTML markup.
	 */
	public function get_page_nav_markup() {
		$pages = get_posts( array(
			'post_type' => 'page',
			'parent'    => 0,
			'nopaging'  => true,
			'orderby'   => 'title',
			'order'     => 'ASC',
		) );

		$pages = array_map( array( $this, 'modify_post' ), $pages );

		$data = array(
			'title'       => esc_html__( 'Pages navigation', 'ftb' ),
			'pages'       => array_map( 'ftb_to_array', $pages ),
			'link_base'   => admin_url( 'customize.php/?url=' ),
			'description' => esc_html__( 'Quickly navigate to the site pages to edit them.', 'ftb' ),
		);

		return $this->handlebars->render( 'customizer/page-nav', $data );
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return WP_Post
	 */
	protected function modify_post( WP_Post $post ) {
		$post->title = $post->post_title;

		return $post;
	}
}