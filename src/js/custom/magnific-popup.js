/*==============================================
=            Magnific Popup - Basic            =
==============================================*/
// $( '.popup-link' ).magnificPopup( {
// 	mainClass: 'mfp-fade'
// } );

/*====================================================
=            Manually Open Magnific Popup            =
====================================================*/
// $( '.trigger-form' ).on( 'click', function() {
// 	$.magnificPopup.open({
//     items: {
//       src: '.popup-block'
//     },
//     type: 'inline',
//     mainClass: 'mfp-zoom-in', // add class for animation
//     removalDelay: 500, // delay removal by X to allow out-animation
//   });
// } );

/*=======================================================
=            Open gallery from external link            =
=======================================================*/
// $( '.view-gallery' ).on( 'click', function() {
// 	$( '.gallery' ).magnificPopup( 'open' );
// 	// $(this).next().magnificPopup('open');
// } );

/*================================================
=            Magnific Popup - Gallery            =
================================================*/
// $( '.gallery-grid' ).magnificPopup( {
// 	delegate: 'a', // child items selector, by clicking on it popup will open
// 	type: 'image',
// 	gallery: {
// 		enabled: true,
// 		preload: [ 0, 1 ]
// 	},
// 	mainClass: 'mfp-zoom-in mfp-fade', // add class for animation
// 	removalDelay: 300, // delay removal by X to allow out-animation
// 	titleSrc: 'title', // custom function will nest inside image: {}
// 	callbacks: {
// 		imageLoadComplete: function() {
// 			var self = this;
// 			setTimeout( function() {
// 				self.wrap.addClass( 'mfp-image-loaded' );
// 			}, 16 );
// 		},
// 		close: function() {
// 			this.wrap.removeClass( 'mfp-image-loaded' );
// 		}
// 	}
// } );

/*==============================================
=            Magnific Popup - Video            =
==============================================*/
// $(".popup-video").magnificPopup({
// 	type: "iframe",
// 	removalDelay: 300,
// 	mainClass: "mfp-fade",
// 	fixedContentPos: false, // disable scrollbar
// 	iframe: {
// 		patterns: {
// 			youtube: {
// 				index: "youtube.com/", // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
// 				id: "v=" // String that splits URL in a two parts, second part should be %id%
// 				// Or null - full URL will be returned
// 				// Or a function that should return %id%, for example:
// 				// id: function(url) { return 'parsed id'; }
// 				// src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0' // URL that will be set as a source for iframe.
// 			},
// 			vimeo: {
// 				index: "vimeo.com/",
// 				id: "/",
// 				src: "//player.vimeo.com/video/%id%?autoplay=1"
// 			}
// 		}
// 	}
// })

/*====================================================
=            Magnific Popup - Members Bio            =
====================================================*/
// all the popup should have a same class
/*$( '.list-members' ).magnificPopup( {
  delegate: 'a',
  mainClass: 'mfp-move-from-top',
  removalDelay: 500, // delay removal by X to allow out-animation
  midClick: true,
  gallery: {
	enabled: true
  }
} );*/

/*====================================================
=            Open a popup after 2 seconds            =
====================================================*/
// setTimeout(function () {
// 	$.magnificPopup.open({
// 		items: {
// 			src: "#subscribe-modal"
// 		},
// 		removalDelay: 300,
// 		mainClass: "mfp-fade"
// 	});
// }, 2000);

/*====================================
=            Custom Title            =
====================================*/
// $( '.list-members' ).magnificPopup( {
// 	image: {
// 		titleSrc: function( item ) {
// 			return item.el.attr( 'title' ) + ' - <a href="' + item.el.parents( 'li' ).find( '.download-link' ).attr( 'href' ) + '">Download</a>';
// 		}
// 	}
// } );

/*=======================================================================
=            Next/Previous Arrows for gallery inside content            =
=======================================================================*/
// $( '.element' ).magnificPopup( {
// 	callbacks: {
// 		buildControls: function() {
// 			// re-appends controls inside the main container
// 			this.content.append( this.arrowLeft.add( this.arrowRight ) ); //content is predefined property. Check API
// 		}
// 	}
// } )

/*==============================================================================================
=            Open on load and custom close with cookie set - REQUIRES cookie plugin            =
==============================================================================================*/
// if ( $( 'body' ).hasClass( 'page-template-literature' ) ) {
// 	if ( Cookies.get( '2022' ) != '1' ) {
// 		$.magnificPopup.open( {
// 			items: {
// 				src: '#terms-modal'
// 			},
// 			removalDelay: 300,
// 			mainClass: 'mfp-fade terms-modal',
// 			closeOnBgClick: false,
// 			showCloseBtn: false,
// 			enableEscapeKey: false
// 		} );

// 		$( '.terms-modal .btn-accept' ).click( function( e ) {
// 			$.magnificPopup.close();
// 			Cookies.set( '2022', '1', {
// 				expires: 1
// 			} );
// 		} );
// 	}
// }
