/** global jQuery, wfr_manager_data */
jQuery( function( $ ) {
    var $plugins_table = $( 'table.plugins' );

    function reset_errors() {
        $( '.wfr-error, .wfr-shake' ).removeClass( 'wfr-error wfr-shake' );
    }

    function do_license_action( license_key, plugin, action ) {
        var data = {
            action: 'wfr_' + action + '_license',
            nonce: wfr_manager_data.nonce,
            license_key: license_key,
            plugin: plugin
        };
        var $row = $( '.plugin-update-tr[data-plugin="' + plugin + '"]' );

        reset_errors();

        $row.addClass( 'processing' );

        $.post( ajaxurl, data, function( response ) {
            $row.removeClass( 'processing' );

            if ( response.success ) {
                $row.replaceWith( response.data.html );
            } else {
                $row.find( '.notice' ).addClass( 'wfr-shake' );
            }
        } );
    }

    $plugins_table
        .on( 'click', '.wfr-activate-license', function( e ) {
            e.preventDefault();

            $( this ).closest( 'span' ).hide();
            $( this )
                .closest( '.notice' )
                .find( '.wfr-license-form' )
                .slideDown( 100 );
        } )
        .on( 'click', 'button.wfr-activate', function( e ) {
            e.preventDefault();

            var key_input   = $( this ).siblings( 'input[name="license_key"]' ),
                license_key = key_input.val().trim(),
                plugin      = $( this ).closest( 'tr').data( 'plugin' );

            if ( '' === license_key ) {
                key_input.addClass( 'wfr-error wfr-shake' );
            } else {
                do_license_action( license_key, plugin, 'activate' );
            }
        } )
        .on( 'click', '.wfr-activate-cancel', function( e ) {
            e.preventDefault();

            reset_errors();

            $( this )
                .closest( '.wfr-license-form' )
                .slideUp( 100 );

            $( this ).closest( '.notice' ).find( 'p span' ).show();
        } )
        .on( 'click', '.wfr-deactivate-license', function( e ) {
            e.preventDefault();

            var row         = $( this ).closest( 'tr' ),
                license_key = row.data( 'license' ),
                plugin      = row.data( 'plugin' );

            do_license_action( license_key, plugin, 'deactivate' );
        } );
} );