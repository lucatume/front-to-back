<?php


class FTB_Pages_Filters implements FTB_Pages_FiltersInterface {

	protected $page_name = '';

	protected $page_slug = '';

	/**
	 * @var FTB_Locators_PageInterface
	 */
	protected $page_locator;

	protected $custom_fields = array();
	/**
	 * @var FTB_Adapters_WPInterface
	 */
	private $wp;

	protected function page_name() {
		return $this->page_name;
	}

	public function set_page_slug( $page_name ) {
		$this->page_name = $page_name;
	}

	public function set_page_name( $page_slug ) {
		$this->page_slug = $page_slug;
	}

	public function set_custom_fields( $custom_fields ) {
		$this->custom_fields = $custom_fields;
	}

	public function __construct( FTB_Adapters_WPInterface $wp, FTB_Locators_PageInterface $page_locator ) {
		$this->wp           = $wp;
		$this->page_locator = $page_locator;
	}

	public function filter_the_title( $title, $post_id = null ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post = get_post( $post );
			if ( empty( $post ) ) {
				return $title;
			}
			$post_id = $post->ID;
		}

		$page_post = $this->page_locator->{"get_{$this->page_name()}"}();
		if ( $post_id == $page_post->ID ) {
			return get_theme_mod( "ftb-page-{$this->page_name()}-title", 'Page Title' );
		}

		return $title;
	}

	public function filter_the_content( $content, $post_id = null ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post = get_post( $post );
			if ( empty( $post ) ) {
				return $content;
			}
			$post_id = $post->ID;
		}

		if ( $post_id == $this->page_locator->{"get_{$this->page_name()}"}()->ID ) {
			return get_theme_mod( "ftb-page-{$this->page_name()}-content", 'Page content' );
		}

		return $content;
	}

	public function filter_get_post_metadata( $value, $object_id, $meta_key ) {
		$post = $this->page_locator->{"get_{$this->page_name()}"}();
		if ( $post && $object_id == $post->ID ) {
			$custom_field_id = $this->custom_field_id( $meta_key );

			$value = empty( $custom_field_id ) ? $value : get_theme_mod( "ftb-page-{$this->page_name()}-{$custom_field_id}", '' );
			if ( $meta_key === '_thumbnail_id' ) {
				$value = $this->wp->get_attachment_id_from_url( $value );
			}
		}

		return $value;
	}

	public function on_customize_save_after( $wp_customize ) {
		$title   = $wp_customize->get_setting( "ftb-page-{$this->page_name()}-title" );
		$content = $wp_customize->get_setting( "ftb-page-{$this->page_name()}-content" );

		$meta_input = array();

		foreach ( $this->custom_fields as $custom_field => $field_id ) {
			$setting_id = "ftb-page-{$this->page_name()}-{$field_id}";
			$setting    = $wp_customize->get_setting( $setting_id );
			if ( ! empty( $setting ) ) {
				if ( $custom_field === '_thumbnail_id' ) {
					$meta_input[ $custom_field ] = $this->wp->get_attachment_id_from_url( $setting->value() );
				} else {
					$meta_input[ $custom_field ] = $setting->value();
				}
			}
		}

		if ( ! empty( $title ) ) {
			$postarr['post_title'] = $title->value();
		}

		if ( ! empty( $content ) ) {
			$postarr['post_content'] = $content->value();
		}

		if ( ! empty( $meta_input ) ) {
			$postarr['meta_input'] = $meta_input;
		}

		$post_id = $this->page_locator->{"update_{$this->page_name()}"}( array_filter( $postarr ) );
	}

	protected function custom_field_id( $meta_key ) {
		if ( isset( $this->custom_fields[ $meta_key ] ) ) {
			return $this->custom_fields[ $meta_key ];
		}

		return false;
	}
}
