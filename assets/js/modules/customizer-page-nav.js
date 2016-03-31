require( "customizer-page-nav.scss" );

var $ = require( 'jQuery' ),
	ftbData = require( 'ftbData' );

$( function () {
	if ( ftbData.customizer.page_nav.html ) {
		$( '#customize-info' ).append( ftbData.customizer.page_nav.html );
	}

	$( '.ftbPageLinks__Link' ).on( 'click', function ( evt ) {
		evt.preventDefault();

		$( '#customize-preview iframe' ).attr( 'src', $( this ).find( 'a' ).data( 'link' ) );
	} );
} );
