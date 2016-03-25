var ftb_customizer_functions = {

	replace_src: function ( element, newSrc ) {
		this.replace_attr( element, 'src', newSrc );
	},

	replace_attr: function ( element, attr, value ) {
		if ( !(element && attr) ) {
			return;
		}

		value = value || '';

		jQuery( element ).attr( attr, value );
	}

};
