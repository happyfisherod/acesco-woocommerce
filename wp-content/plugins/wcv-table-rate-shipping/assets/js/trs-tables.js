/* global wc_enhanced_select_params, wcv_trs_localize, Backbone */
( function( $, data, wp ) {
    $( function() {
        var $table                  = $( '.wc-shipping-zones' ),
            $tbody                  = $( '.wc-shipping-zone-rows' ),
            $save_button            = $( '.wc-shipping-zone-save' ),
            $row_template           = wp.template( 'wc-shipping-zone-row' ),
            $blank_template         = wp.template( 'wc-shipping-zone-row-blank' ),
            $rate_row_template      = wp.template( 'wcv-trs-rate-row' ),
            $rate_blank_template    = wp.template( 'wcv-trs-rate-row-blank' ),
            $keep_defaults_button   = $( '.wcv-trs-keep-defaults' ),
            $delete_defaults_button = $( '.wcv-trs-delete-defaults' ),
            $default_tables_notice  = $( '#wcv_trs_default_tables_notice' ),
            rateTableView           = null,
            select2_args            = {
                minimumResultsForSearch: Infinity,
                allowClear:  $( this ).data( 'allow_clear' ),
                placeholder: $( this ).data( 'placeholder' )
            },

            // Backbone model
            ShippingTable       = Backbone.Model.extend({
                changes: {},
                logChanges: function( changedRows, setUnloadConfirmation ) {
                    if ( 'undefined' === typeof setUnloadConfirmation ) {
                        setUnloadConfirmation = true;
                    }

                    var changes = this.changes || {};

                    _.each( changedRows, function( row, id ) {
                        changes[ id ] = _.extend( changes[ id ] || { table_id : id }, row );
                    } );

                    this.changes = changes;
                    this.trigger( 'change:tables', setUnloadConfirmation );
                },
                discardChanges: function( id ) {
                    var changes      = this.changes || {},
                        set_position = null,
                        tables       = _.indexBy( this.get( 'tables' ), 'table_id' );

                    // Find current set position if it has moved since last save
                    if ( changes[ id ] && changes[ id ].table_order !== undefined ) {
                        set_position = changes[ id ].table_order;
                    }

                    // Delete all changes
                    delete changes[ id ];

                    // If the position was set, and this table does exist in DB, set the position again so the changes are not lost.
                    if ( set_position !== null && tables[ id ] && tables[ id ].table_order !== set_position ) {
                        changes[ id ] = _.extend( changes[ id ] || {}, { table_id : id, table_order : set_position } );
                    }

                    this.changes = changes;

                    // If the table is a default table, add back all initial changes.
                    if ( tables[ id ] && tables[ id ].is_default ) {
                        init_default_table( id, tables[ id ] );
                    }

                    // No changes? Disable save button.
                    if ( 0 === _.size( this.changes ) ) {
                        shippingTableView.clearUnloadConfirmation();
                    }
                },
                save: function() {
                    if ( _.size( this.changes ) ) {
                        $.post( data.ajaxurl, {
                            action        : 'wcv_trs_save_changes',
                            wcv_trs_nonce : data.wcv_trs_nonce,
                            changes       : this.changes,
                            user_id       : data.user_id
                        }, this.onSaveResponse, 'json' );
                    } else {
                        shippingTable.trigger( 'saved:tables' );
                    }
                },
                onSaveResponse: function( response, textStatus ) {
                    if ( 'success' === textStatus ) {
                        if ( response.success ) {
                            shippingTable.set( 'tables', response.data.tables );
                            shippingTable.trigger( 'change:tables' );
                            shippingTable.changes = {};
                            shippingTable.trigger( 'saved:tables' );
                        } else {
                            window.alert( data.strings.save_failed );
                        }
                    }
                }
            } ),

            // Backbone view
            ShippingTableView = Backbone.View.extend({
                rowTemplate: $row_template,
                initialize: function() {
                    this.listenTo( this.model, 'change:tables', this.setUnloadConfirmation );
                    this.listenTo( this.model, 'saved:tables', this.clearUnloadConfirmation );
                    this.listenTo( this.model, 'saved:tables', this.render );
                    this.listenTo( this.model, 'saved:tables', this.hideDefaultTablesNotice );

                    $tbody.on( 'sortupdate', { view: this }, this.updateModelOnSort );

                    $( window ).on( 'beforeunload', { view: this }, this.unloadConfirmation );

                    $save_button.on( 'click', { view: this }, this.onSubmit );

                    $( document.body ).on( 'click', '.wc-shipping-zone-add', { view: this }, this.onAddNewTable );
                    $( document.body ).on( 'click', '.wc-shipping-zone-edit', { view: this }, this.onConfigure );
                    $( document.body ).on( 'click', '.wc-shipping-zone-delete', { view: this }, this.onDeleteTable );
                    $( document.body ).on( 'click', '.trs-table-postcodes-toggle', this.togglePostcodesField );
                    $( document.body ).on( 'wc_backbone_modal_response', this.onConfigureSubmitted );
                },
                hideDefaultTablesNotice: function () {
                    $default_tables_notice.hide();
                },
                block: function() {
                    $table.block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                },
                unblock: function() {
                    $table.unblock();
                },
                render: function() {
                    var tables = _.indexBy( this.model.get( 'tables' ), 'table_id' ),
                        view  = this;

                    view.$el.empty();
                    view.unblock();

                    if ( _.size( tables ) ) {
                        // Sort tables
                        tables = _.sortBy( tables, function( table ) {
                            return parseInt( table.table_order, 10 );
                        } );

                        // Populate $tbody with the current tables
                        $.each( tables, function( id, rowData ) {
                            view.renderRow( rowData );
                        } );
                    } else {
                        view.$el.append( $blank_template );
                    }

                    view.initRows();
                },
                renderRow: function( rowData ) {
                    this.$el.append( this.rowTemplate( rowData ) );
                },
                initRows: function() {
                    // Stripe
                    if ( 0 === ( $( 'tbody.wc-shipping-zone-rows tr' ).length % 2 ) ) {
                        $table.find( 'tbody.wc-shipping-zone-rows' ).next( 'tbody' ).find( 'tr' ).addClass( 'odd' );
                    } else {
                        $table.find( 'tbody.wc-shipping-zone-rows' ).next( 'tbody' ).find( 'tr' ).removeClass( 'odd' );
                    }

                    this.initTips();
                },
                initTips: function() {
                    $( '#tiptip_holder' ).removeAttr( 'style' );
                    $( '#tiptip_arrow' ).removeAttr( 'style' );
                    $( '.tips, .woocommerce-help-tip' ).tipTip({ 'attribute': 'data-tip', 'fadeIn': 50, 'fadeOut': 50, 'delay': 50 });
                },
                onSubmit: function( event ) {
                    event.preventDefault();
                    event.data.view.block();
                    event.data.view.model.save();
                },
                onConfigure: function( event ) {
                    var table_id = $( this ).closest( 'tr' ).data( 'id' ),
                        view     = event.data.view,
                        model    = view.model,
                        tables   = _.indexBy( model.get( 'tables' ), 'table_id' );

                    var table = {};
                    if ( table_id in tables ) {
                        table = tables[ table_id ];
                    }

                    view.openEditModal( table );
                },
                openEditModal: function( table ) {
                    event.preventDefault();

                    $( this ).WCBackboneModal({
                        template : 'wc-modal-shipping-table',
                        variable : {
                            table_id : table.table_id,
                            table    : table
                        },
                        data : {
                            table_id : table.table_id,
                            table    : table
                        }
                    });

                    this.initModal( table );
                },
                initModal: function( table ) {
                    // Select value for "Use Table"
                    jQuery( '#wcv_trs_is_enabled' ).find( 'option[value="' + table.is_enabled + '"]' ).prop( 'selected', true );
                    
                    // Select value for "Calculation Method"
                    jQuery( '#wcv_trs_table_method' ).find( 'option[value="' + table.table_method + '"]' ).prop( 'selected', true );
                    
                    // Select regions
                    _.each( table.table_locations, function( location ) {
                        if ( 'string' === jQuery.type( location ) ) {
                            $( 'option[value="' + location + '"]' ).prop( 'selected', true );
                        } else {
                            $( 'option[value="' + location.type + ':' + location.code + '"]' ).prop( 'selected', true );
                        }
                    } );

                    // Initialize rate table
                    rateTableView = new RateTableView({ 
                        model: new RateTable({
                            rates: table.formatted_table_rates ? table.formatted_table_rates : []
                        })
                    });

                    rateTableView.render();

                    $( '.wc-enhanced-select:not(.enhanced)' )
                        .select2( select2_args )
                        .addClass( 'enhanced' );

                    this.initTips();
                },
                togglePostcodesField: function( e ) {
                    e.preventDefault();
                    $( this ).hide();
                    $( '.trs-table-postcodes' ).show();
                },
                onConfigureSubmitted: function( event, target, posted_data ) {
                    if ( 'wc-modal-shipping-table' === target ) {
                        var view     = shippingTableView,
                            model    = view.model,
                            tables   = _.indexBy( model.get( 'tables' ), 'table_id' ),
                            table_id = posted_data.table_id,
                            table    = tables[ table_id ],
                            changes  = {};

                        changes[ table_id ] = {
                            // Start with a blank slate so we can account for cases where rates are removed
                            'formatted_table_rates': {}
                        };

                        var regex = /([^\[]+)\[(.+)]/;

                        _.each( posted_data, function( data, key )  {
                            if ( key.startsWith( "threshold" ) || key.startsWith( "rate" ) || key.startsWith( "is_percent" ) ) {
                                var matches = regex.exec( key );
                                var attr    = matches[1];
                                var index   = matches[2];

                                if ( ! changes[ table_id ][ 'formatted_table_rates' ][ index ] ) {
                                    changes[ table_id ][ 'formatted_table_rates' ][ index ] = {
                                        rate_id: index // Important
                                    };
                                }

                                changes[ table_id ][ 'formatted_table_rates' ][ index ][ attr ] = data;
                            } else if ( "table_id" !== key ) {
                                changes[ table_id ][ key.replace( "wcv_trs_", "" ) ] = data;
                            }
                        } );

                        if ( table ) {
                            _.each( changes[ table_id ], function( value, key ) {
                                table[ key ] = value;
                            } );
                        }

                        // Special case: All rates removed, send empty string for formatted_table_rates
                        if ( _.isEmpty( changes[ table_id ][ 'formatted_table_rates' ] ) ) {
                            changes[ table_id ][ 'formatted_table_rates' ] = "";
                        }

                        model.logChanges( changes );
                        
                        $save_button.trigger( 'click' );
                    }
                },
                onAddNewTable: function( event ) {
                    event.preventDefault();

                    var view      = event.data.view,
                        model     = view.model,
                        tables    = _.indexBy( model.get( 'tables' ), 'table_id' ),
                        changes   = {},
                        size      = _.size( tables ),
                        new_table = _.extend( {}, data.default_table, {
                            table_id: 'new-' + size + '-' + Date.now(),
                        } );

                    new_table.table_order = 1 + _.max(
                        _.pluck( tables, 'table_order' ),
                        function ( val ) {
                            // Cast them all to integers, because strings compare funky. Sighhh.
                            return parseInt( val, 10 );
                        }
                    );

                    view.openEditModal( new_table );
                },
                onDeleteTable: function( event ) {
                    var view    = event.data.view,
                        model   = view.model,
                        tables  = _.indexBy( model.get( 'tables' ), 'table_id' ),
                        changes = {},
                        row     = $( this ).closest('tr'),
                        table_id = $( this ).closest('tr').data('id');

                    event.preventDefault();

                    if ( tables[ table_id ] ) {
                        delete tables[ table_id ];
                        changes[ table_id ] = _.extend( changes[ table_id ] || {}, { deleted : 'deleted' } );
                        model.set( 'tables', tables );
                        model.logChanges( changes );
                    }

                    row.remove();
                    view.initRows();
                },
                setUnloadConfirmation: function( needsConfirmation ) {
                    this.needsUnloadConfirm = needsConfirmation;
                    $save_button.prop( 'disabled', false );
                },
                clearUnloadConfirmation: function() {
                    this.needsUnloadConfirm = false;
                    $save_button.prop( 'disabled', true );
                },
                unloadConfirmation: function( event ) {
                    if ( event.data.view.needsUnloadConfirm ) {
                        event.returnValue = data.strings.unload_confirmation_msg;
                        window.event.returnValue = data.strings.unload_confirmation_msg;
                        return data.strings.unload_confirmation_msg;
                    }
                },
                updateModelOnSort: function( event ) {
                    var view    = event.data.view,
                        model   = view.model,
                        tables   = _.indexBy( model.get( 'tables' ), 'table_id' ),
                        rows    = $( 'tbody.wc-shipping-zone-rows tr' ),
                        changes = {};

                    // Update sorted row position
                    _.each( rows, function( row ) {
                        var table_id = $( row ).data( 'id' ),
                            old_position = null,
                            new_position = parseInt( $( row ).index(), 10 );

                        if ( tables[ table_id ] ) {
                            old_position = parseInt( tables[ table_id ].table_order, 10 );
                        }

                        if ( old_position !== new_position ) {
                            changes[ table_id ] = _.extend( changes[ table_id ] || {}, { table_order : new_position } );
                        }
                    } );

                    if ( _.size( changes ) ) {
                        model.logChanges( changes );
                    }
                }
            } ),
            RateTable = Backbone.Model.extend({
                rates: []
            }),
            RateTableView = Backbone.View.extend({
                rowTemplate: $rate_row_template,
                rateBody: null,
                initialize: function() {
                    this.rateBody = $( '.wcv-trs-rates-rows' );

                    $( '#wcv_trs_table_method' ).on( 'change', this.updateMethod );

                    $( document.body ).on( 'click', '.wcv-trs-rate-add', { view: this }, this.onAddNewRow );
                },
                render: function() {
                    var view      = this,
                        model     = view.model,
                        rates     = _.indexBy( model.get( 'rates' ), 'rate_id' ),
                        $ratebody = view.rateBody;

                    $ratebody.empty();

                    if ( _.size( rates ) ) {
                        // Populate $ratebody with the current rates
                        $.each( rates, function( id, rowData ) {
                            view.renderRow( rowData );
                        } );

                        view.updateMethod();
                    } else {
                        $ratebody.append( $rate_blank_template );
                    }
                },
                renderRow: function( rowData ) {
                    var view      = this,
                        $ratebody = view.rateBody;
                    
                    $ratebody.append( view.rowTemplate( rowData ) );
                    view.initRow( rowData );
                },
                initRow: function( rowData ) {                  
                    var view      = this,
                        $ratebody = view.rateBody,
                        $tr       = $ratebody.find( 'tr[data-id="' + rowData.rate_id + '"]');

                    // Select rate type
                    $tr.find( 'option[value="' + rowData.is_percent + '"]' ).prop( 'selected', true );

                    // Make the row function
                    $tr.find( '.wcv-trs-rate-delete' ).on( 'click', { view: this }, this.onDeleteRow );
                },
                updateMethod: function() {
                    var target       = $( '#wcv_trs_table_method' ),
                        method       = target.val(),
                        $method_text = $( '.wcv-trs-calc-method' );

                    // Reset
                    $( '.wcv-trs-rate-threshold-before, .wcv-trs-rate-threshold-after' ).html( '' );
                    $method_text.text( '' );

                    // Set before/after based on selected calculation method
                    switch (method) {
                        case 'weighttotal':
                            $( '.wcv-trs-rate-threshold-after' ).html( data.strings.weight_unit );
                            $method_text.text( data.strings.weight );
                            break;
                        case 'subtotal':
                            $( '.wcv-trs-rate-threshold-before' ).html( data.strings.currency_symbol );
                            $method_text.text( data.strings.subtotal );
                            break;
                        case 'itemcount':
                            $method_text.text( data.strings.item_count );
                            break;
                    }
                },
                onAddNewRow: function( event ) {
                    event.preventDefault();

                    var view    = event.data.view,
                        model   = view.model,
                        rates   = _.indexBy( model.get( 'rates' ), 'rate_id' ),
                        size    = _.size( rates ),
                        newRow  = _.extend( {}, data.default_rate, {
                            rate_id  : 'new-' + size + '-' + Date.now()
                        } );

                    $( '.wcv-trs-rates-blank-state' ).closest( 'tr' ).remove();

                    view.renderRow( newRow );
                    view.updateMethod();
                },
                onDeleteRow: function( event ) {
                    event.preventDefault();
                    var row = $( this ).closest( 'tr' );
                    row.remove();
                }
            } ),
            shippingTable = new ShippingTable({
                tables: data.tables
            } ),
            shippingTableView = new ShippingTableView({
                model: shippingTable,
                el: $tbody
            } );

        shippingTableView.render();

        $tbody.sortable({
            items: 'tr',
            cursor: 'move',
            axis: 'y',
            handle: 'td.wc-shipping-zone-sort',
            scrollSensitivity: 40
        });
        
        // Reuse the store settings form save button for saving the shipping tables
        $( window ).load( function() {
            if ( typeof window.Ink === 'undefined' ) {
                return;
            }

            var tabs             = window.Ink.UI.Common_1.getInstance( '.wcv-tabs' )[0];
            var $wcv_save_button = $( '.wcv-form input[type="submit"]' ).last();

            function onTabChanged( tabs ) {
                var tab_id = tabs.activeTab();

                $wcv_save_button.toggle( 'shipping-custom' !== tab_id );
            }

            tabs._options.onChange = onTabChanged;

            onTabChanged( tabs );
        } );

        // Treat default tables as new tables so the save changes button can be clicked
        function init_default_tables() {
            var model   = shippingTable,
                tables  = _.indexBy( model.get( 'tables' ), 'table_id' );

            _.each( tables, function( table, table_id ) {
                tables[ table_id ].is_default = true;
                init_default_table( table_id, table );
            } );
        }

        function init_default_table( table_id, table ) {
            var model   = shippingTable,
                changes = {},
                to_copy = {
                    'table_id'              : null,
                    'table_name'            : null,
                    'table_order'           : null,
                    'table_locations'       : function( location ) {
                        return location.type + ':' + location.code
                    },
                    'table_method'          : null,
                    'table_fee'             : null,
                    'is_enabled'            : null,
                    'formatted_table_rates' : function( rate, key ) {
                        rate['rate_id'] = 'new-' + key;
                        return rate;
                    }
                };

            changes[ table_id ] = {};

            _.each( to_copy, function( map_func, prop ) {
                if ( null !== map_func )
                    changes[ table_id ][ prop ] = _.map( table[ prop ], map_func );
                else
                    changes[ table_id ][ prop ] = table[ prop ];
            } );

            // Don't set the unload confirmation until the tables are changed
            model.logChanges( changes, false );
        }

        if ( $default_tables_notice.length > 0 ) {
            init_default_tables();
        }

        // Power up the "keep" link for default tables
        $keep_defaults_button.one( 'click', function( e ) {
            e.preventDefault();

            keep_or_delete_default_tables( 'keep' );
        } );

        // Power up the "delete" link for default tables
        $delete_defaults_button.one( 'click', function( e ) {
            e.preventDefault();

            keep_or_delete_default_tables( 'delete' );
        } );

        // Helper to send keep/delete default tables requests
        function keep_or_delete_default_tables( action ) {
            shippingTableView.block();

            $.ajax( {
                type: 'post',
                url: data.ajaxurl,
                data: {
                    action: 'wcv_trs_' + action + '_default_tables',
                    wcv_trs_nonce: data.wcv_trs_nonce,
                    user_id: data.user_id
                },
                success: shippingTable.onSaveResponse,
                complete: shippingTableView.unblock
            } );
        }
    });
})( jQuery, wcv_trs_localize, wp );
