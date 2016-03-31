require( "customizer-page-nav.scss" );

var $ = require( 'jQuery' ),
	ftbData = require( 'ftbData' ),
	wp = require( 'wp' );

$( function () {
	if ( ftbData.customizer.page_nav.html ) {
		$( '#customize-info' ).append( ftbData.customizer.page_nav.html );
	}

	$( '.ftbPageLinks__Link' ).on( 'click', function ( evt ) {
		evt.preventDefault();

		var url = $( this ).find( 'a' ).data( 'link' );
		$( '#customize-preview iframe' ).attr( 'src', url );
		wp.customize.previewer.previewUrl( url );
	} );
} );
