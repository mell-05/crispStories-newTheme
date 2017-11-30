(function($){
	'use strict';

	$(function(){

		var $isotope = jQuery( '.isotope' );

		if ( $isotope.length ) {

			$( window ).resize( function() {

				$isotope.each( function() {

					$( this ).isotope( 'layout' );

				});

				setTimeout( function() {

					$isotope.each( function() {

						$( this ).isotope( 'layout' );

					});
				
				}, 400 );

			});

		}

		$( document ).foundation();

		if ( $( '.peThemeContactForm' ).length > 0 ) {

			$( '.peThemeContactForm' ).peThemeContactForm();

		}

		var innerNav = $( '.inner-nav' );
		// fix top spacing with simple nav
		if ( innerNav.length ) {

			innerNav.next().css( 'padding-top', Number( innerNav.next().css( 'padding-top' ).replace( 'px', '' ) ) + innerNav.height() );

		}

		// add social icons to menu
		if ( $( '#menu ul' ).length ) {

			var $social = $( '.header-social' );

			$( '#menu > ul' ).append( $social.html() );

			$social.remove();

		}

		$( 'section:not(#contact) .social-icons ul' ).each( function() {

			$( this ).css( 'background-color', $( this ).closest( 'section' ).css( 'background-color' ) );

		});

		// correct colors for pricing tables
		$( '.price-table .btn-holder, .price-table' ).each( function() {

			var $this = $( this );

			$this.css( 'background-color', $this.closest( 'section' ).css( 'background-color' ) );

		});

		// Add correct container parameters to portfolio instances (used by portfolio script in order to work with multiple portfolio instances on one page)
		$( 'ul.filters, .project-container, .ajax-container' ).each( function() {

			var $this = $( this );

			$this.attr( 'data-container', $this.closest( 'section' ).attr( 'id' ) );

		});

		// responsive videos
		$( '.video-container' ).fitVids();

		// remove empty content on projects page
		$( '.section-portfolio' ).find( '.page-body:empty' ).parent().remove();

		// correct some blog classes
		$( '.controls button' ).attr( 'class', 'btn btn-success' );
		$( '.span1' ).addClass( 'large-1 columns' );
		$( '.span11' ).addClass( 'large-11 columns' );
		$( '.row-fluid' ).not( '#comments' ).addClass( 'row' );

		// correct password form look
		$( '.post-password-form' ).find( 'input[type="submit"]' ).addClass( 'btn' ).css( 'border', 'none' );

		// fix button click area
		$( '#form-button' ).click( function( e ) {

			if ( $( e.target ).is( $( this ) ) ) {

				$( this ).find( 'button' ).click();

			}

		});

		// Remove '/' from dropdown menu items
		$( '.dropdown-menu li h6' ).each( function() {

			var $this = $( this ),
				text = $this.text();

			$this.text( text.slice( 0, -2 ) );

		});

		$( '.sub-menu' ).addClass( 'dropdown-menu' );

		$( '.menu-item-language a' ).each( function() {

			var $this = $( this ),
				text = $this.text();

			$this.html( '<h6>' + text + '</h6>' );

		});

	});

})(jQuery);