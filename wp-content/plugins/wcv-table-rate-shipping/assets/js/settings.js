/** global jQuery */
jQuery( function( $ ) {
    var $save_button = $( 'input.wc-shipping-zone-save' );

    $( '#mainform' ).one( 'submit', function( e ) {
        if ( $save_button.is( ':disabled' ) ) {
            return;
        }

        e.preventDefault();

        var interval = setInterval( function() {
            if ( $save_button.is( ':disabled' ) ) {
                clearInterval( interval );
                $( '#mainform' ).submit();
            }
        }, 200 );

        $save_button.click();
    } );
} );