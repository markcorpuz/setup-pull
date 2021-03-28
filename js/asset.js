(function($) {

	/*function copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}*/

	// SHOW/HIDE PRE
	$( '[id^="show_raw_"]' ).click( function() {
		//alert( $( this ).attr( 'id' ) );
		var ThisID = $( this ).attr( 'id' ),
			ThisIDSplit = ThisID.split( '__' );

		if( $( this ).text() === 'Show Raw' ) {
			$( this ).text( 'Hide Raw' );
		} else {
			$( this ).text( 'Show Raw' );
		}

		$( '#output_pre_container__' + ThisIDSplit[ 1 ] ).slideToggle( 'medium' );
	});

	// COPY TO CLIPBOARD
	$( '[id^="copy_to_clipboard_"]' ).click( function() {
		alert( $( this ).attr( 'id' ) );
		// alert
	});

	/*function BtnShowMe() {
		$( '#output_pre_container' ).removeClass( 'hidden' );
	}*/

})( jQuery );