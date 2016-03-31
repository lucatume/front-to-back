var $ = require( 'jQuery' ),
	Backbone = require( 'Backbone' ),
	ftbData = require( 'ftbData' );

module.exports = Backbone.Model.extend( {
	get_attachment_image_from: function ( newSrc, size, attr ) {
		var settings = {
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-NONCE', ftbData.nonce );
			},
			url: ftbData.rest_url_prefix + '/ftb/v1/markup/attachment',
			data: {
				newSrc: newSrc,
				size: size,
				attr: attr
			},
			dataType: 'json'
		};

		return $.get( settings );
	}
} );
