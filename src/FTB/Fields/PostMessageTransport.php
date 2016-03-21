<?php


class FTB_Fields_PostMessageTransport implements FTB_Fields_TransportInterface {

	/**
	 * @var string
	 */
	protected $wrapping_tag = 'span';

	public function add_field_args( $tag, array $field_args ) {
		if ( ! in_array( $tag, $this->supported_tags() ) ) {
			return $field_args;
		}

		$field_args['transport'] = 'postMessage';
		$js_vars                 = array(
			'element'  => '.' . $field_args['settings'],
			'function' => $this->get_function( $tag, $field_args ),
			'property' => $this->get_property( $tag, $field_args ),
			'prefix'   => $this->get_prefix( $tag, $field_args ),
			'suffix'   => $this->get_suffix( $tag, $field_args ),
		);

		$field_args['js_vars'] = array( array_filter( $js_vars ) );

		return array_filter( $field_args );
	}

	protected function get_function( $tag, $field_args ) {
		$tag_functions = $this->tag_functions();
		if ( isset( $tag_functions[ $tag ] ) ) {
			return $tag_functions[ $tag ];
		}

		return '';
	}

	protected function get_property( $tag, $field_args ) {
		$tag_properties = $this->tag_properties();
		if ( isset( $tag_properties[ $tag ] ) ) {
			return $tag_properties[ $tag ];
		}

		return '';
	}

	protected function supported_tags() {
		return array( 'title' );
	}

	protected function tag_properties() {
		return array( 'title' => '' );
	}

	protected function tag_functions() {
		return array( 'title' => 'html' );
	}

	public function modify_output( $tag, array $field_args, $output ) {
		if ( ! in_array( $tag, $this->supported_tags() ) ) {
			return $field_args;
		}

		return $this->modify_markup( $tag, $field_args, $output );
	}

	private function modify_markup( $tag, $field_args, $output ) {
		$markup_mods = $this->markup_mods();
		if ( ! isset( $markup_mods[ $tag ] ) ) {
			return $output;
		}

		if ( isset( $markup_mods[ $tag ]['callback'] ) ) {
			return call_user_func( $markup_mods[ $tag ]['callback'], $tag, $field_args, $output );
		} else {
			return $markup_mods[ $tag ]['before'] . $output . [ $markup_mods ]['after'];
		}
	}

	protected function markup_mods() {
		return array();
	}

	protected function wrap( $tag, array $field_args, $output ) {
		return sprintf( '<span class=".%s" style="display:inline;">%s</span>', $field_args['settings'], $output );
	}

	protected function get_prefix( $tag, $field_args ) {
		$tag_prefixes = $this->tag_prefixes();

		return isset( $tag_prefixes[ $tag ] ) ? ftb_template( $tag_prefixes[ $tag ], $field_args ) : '';
	}

	protected function get_suffix( $tag, $field_args ) {
		$tag_suffixes = $this->tag_suffixes();

		return isset( $tag_suffixes[ $tag ] ) ? ftb_template( $tag_suffixes[ $tag ], $field_args ) : '';
	}

	private function tag_prefixes() {
		return array(
			'title' => '<' . $this->wrapping_tag() . ' class=".{{settings}}" style="display: inline;">',
		);
	}

	protected function tag_suffixes() {
		return array(
			'title' => '</' . $this->wrapping_tag() . '>',
		);
	}

	protected function wrapping_tag() {
		return 'span';
	}

	public function set_wrapping_tag( $wrapping_tag ) {
		$this->wrapping_tag = $wrapping_tag;
	}
}