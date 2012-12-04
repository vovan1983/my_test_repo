jQuery( document ).ready( function( ) {
	jQuery( '#gift-form #doaction' ).click( function( ) {
		if( jQuery('#gift-form select[name=action]').val() == 'delete' ){
			if ( ! confirm( 'Do you want delete gift(s)?' ) ){ 
				return false;
			}			
		}

	});
	jQuery( '#gift-form #doaction2' ).click( function( ) {
		if( jQuery('#gift-form select[name=action2]').val() == 'delete' ){
			if ( ! confirm( 'Do you want delete gift(s)?' ) ){ 
				return false;
			}			
		}

	});

	jQuery( "#CutOffDate" ).datepicker( {
		minDate: "today",
		dateFormat: 'yy-mm-dd'
	} );
} );



