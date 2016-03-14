<?php


class FTB_ServiceProviders_PreviewFilters extends tad_DI52_ServiceProvider{

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		add_action( 'customize_preview_init', 'ftb_add_preview_filters' );
		function ftb_add_preview_filters() {
			$page_locator  = new FTB_Locators_Page();
			$wp            = new FTB_Adapters_WP();
			$about_us_page = new FTB_Pages_Filters( $wp, $page_locator );
			$about_us_page->set_page_slug( 'about_us' );
			$about_us_page->set_page_name( 'about-us' );
			$about_us_page->set_custom_fields( array(
				'_thumbnail_id'           => 'featured_image',
				'_featured_image_caption' => 'featured_image_caption',
			) );

			add_filter( 'the_title', array( $about_us_page, 'filter_the_title' ), 0, 2 );
			add_filter( 'the_content', array( $about_us_page, 'filter_the_content' ), 0, 2 );
			add_filter( 'get_post_metadata', array( $about_us_page, 'filter_get_post_metadata' ), 0, 3 );
		}

		add_action( 'customize_save_after', array( $about_us_page, 'on_customize_save_after' ), 10, 1 );
	}

	/**
	 * Binds and sets up implementations at boot time.
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}