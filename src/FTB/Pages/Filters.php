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

	public function filter_the_excerpt( $excerpt, $post_id = null ) {
		if ( empty( $post_id ) ) {
			global $post;
			$post = get_post( $post );
			if ( empty( $post ) ) {
				return $excerpt;
			}
			$post_id = $post->ID;
		}

		$page_post = $this->page_locator->{"get_{$this->page_name()}"}();
		if ( $post_id == $page_post->ID ) {
			return get_theme_mod( "ftb-page-{$this->page_name()}-excerpt", 'Page Excerpt' );
		}

		return $excerpt;
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
			$wp_customize = $this->wp->get_wp_customize();

			if ( empty( $wp_customize ) ) {
				return $value;
			}

			$meta_settings        = array_filter( $wp_customize->settings(), array( $this, 'filter_meta_settings' ) );
			$theme_mod_setting_id = $this->get_theme_mod_setting_id( $meta_key );

			if ( ! isset( $meta_settings[ $theme_mod_setting_id ] ) ) {
				return $value;
			}

			$value = get_theme_mod( $theme_mod_setting_id, '' );
			if ( $meta_key === '_thumbnail_id' ) {
				$value = $this->wp->get_attachment_id_from_url( $value );
			}
		}

		return $value;
	}

	public function on_customize_save_after( $wp_customize ) {
		/** @var WP_Customize_Manager $wp_customize */
		$title   = $wp_customize->get_setting( "ftb-page-{$this->page_name()}-title" );
		$excerpt = $wp_customize->get_setting( "ftb-page-{$this->page_name()}-excerpt" );
		$content = $wp_customize->get_setting( "ftb-page-{$this->page_name()}-content" );

		$meta_input = array();

		$meta_settings = array_filter( $wp_customize->settings(), array( $this, 'filter_meta_settings' ) );
		foreach ( $meta_settings as $setting_id => $setting ) {
			if ( ! empty( $setting ) ) {
				$meta_id = $this->get_meta_setting_name( $setting_id );
				if ( $meta_id === 'featured_image' ) {
					$meta_input[ '_thumbnail_id' ] = $this->wp->get_attachment_id_from_url( $setting->value() );
				} else {
					$meta_input[ $meta_id ] = $setting->value();
				}
			}
		}

		$postarr = array();

		if ( ! empty( $title ) ) {
			$postarr['post_title'] = $title->value();
		}

		if ( ! empty( $excerpt ) ) {
			$postarr['post_excerpt'] = $excerpt->value();
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

	protected function filter_meta_settings( $value ) {
		/** @var WP_Customize_Setting $value */
		return preg_match( '/^ftb-page-' . $this->page_name() . '-meta-[a-z0-9_]*$/', $value->id_data()['base'] );
	}

	protected function get_meta_setting_name( $value ) {
		$matches = array();

		preg_match( '/^ftb-page-' . $this->page_name() . '-meta-([a-z0-9_]*)$/', $value, $matches );

		return isset( $matches[1] ) ? $matches[1] : '';
	}

	/**
	 * @param $custom_field_id
	 *
	 * @return string
	 */
	private function get_theme_mod_setting_id( $custom_field_id ) {
		$meta_key_aliases = $this->meta_key_aliases();
		$custom_field_id  = isset( $meta_key_aliases[ $custom_field_id ] ) ? $meta_key_aliases[ $custom_field_id ] : $custom_field_id;

		return "ftb-page-{$this->page_name()}-meta-{$custom_field_id}";
	}

	protected function meta_key_aliases() {
		return array(
			'_thumbnail_id' => 'featured_image',
		);
	}
}
