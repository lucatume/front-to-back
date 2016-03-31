var $ = require( 'jQuery' ),
	Backbone = require( 'Backbone' ),
	Events = require( 'Events' ),
	Backend = require( 'Backend' );

module.exports = Backbone.Model.extend( {

	events: Events,

	backend: new Backend(),

	replace: function ( element, newSrc ) {
		var $element = $( element ), size, attr, html, self = this;

		$element.each( function () {
			$this = $( this );
			self.events.trigger( 'ftb.attachment.replace_src.before', element, newSrc );

			size = $this.data( 'ftb-size' );
			attr = $this.data( 'ftb-attr' );
			html = self.backend.get_attachment_image_from( newSrc, size, attr ).success( function ( html ) {
				if ( html === false ) {
					return;
				}

				$this.replaceWith( html );

				self.events.trigger( 'ftb.attachment.replace_src.after', element, newSrc, html );
			} );

		} );
	}
} );
