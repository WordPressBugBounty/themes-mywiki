/**
 * MyWiki — Customizer live preview.
 */
( function( $ ) {
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.mw-brand-name, .site-title a' ).text( to );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'mywiki_hero_eyebrow', function( value ) {
		value.bind( function( to ) {
			$( '.mw-hero-eyebrow' ).text( to );
		} );
	} );

	wp.customize( 'mywiki_hero_heading', function( value ) {
		value.bind( function( to ) {
			$( '.mw-hero-title-main' ).text( to );
		} );
	} );

	wp.customize( 'mywiki_hero_italic', function( value ) {
		value.bind( function( to ) {
			$( '.mw-hero-title-italic' ).text( to );
		} );
	} );

	wp.customize( 'mywiki_hero_subtitle', function( value ) {
		value.bind( function( to ) {
			$( '.mw-hero-subtitle' ).text( to );
		} );
	} );
}( jQuery ) );
