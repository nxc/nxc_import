;jQuery( function() {
	$( 'select[name="class_id"]' ).change( function( e ) {
		var wrapper = $( 'div.attributes-mapping-wrapper' );
		var loader  = $( 'div.attributes-mapping-loader' );
		var classID = $( this ).val();

		wrapper.empty();

		if( classID <= 0 ) {
			return true;
		}

		loader.show();
		$.ajax( {
			url: $( this ).data( 'url' ).replace( 'CLASS_ID', classID )
		} ).done( function( data ) {
			loader.hide();
			wrapper.empty();
			wrapper.append( data );
		} );
	} );
} );