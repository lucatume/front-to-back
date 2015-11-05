<?php

namespace tad\FrontToBack\Adapters;


class WP {

	public function safe_redirect( $location, $status = 302 ) {
		wp_safe_redirect( $location, $status );
	}
}