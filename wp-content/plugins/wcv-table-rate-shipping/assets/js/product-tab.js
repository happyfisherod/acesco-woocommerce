/**
 * JavaScript for the Shipping Rates product tab.
 */
jQuery(function ($) {
    var $tab = $('#tab-shipping');

    $(document).on('click', '.trs-change-destination', function () {
        $('#trs_customer_location .view').hide();
        $('#trs_customer_location .edit').show();
    });

    $(document).on('submit', '#trs_location_form', function (e) {
        e.preventDefault();

        $tab.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6,
                zIndex: 100000
            }
        });

        $.ajax({
            type: 'post',
            url: trs_shipping_tab_data.ajax_url,
            data: {
                action: 'trs_change_destination',
                nonce: trs_shipping_tab_data.nonce,
                region: $('#trs_region_select').val(),
                postcode: $('#trs_postcode').val(),
                product_id: trs_shipping_tab_data.product_id
            },
            success: function (html) {
                $tab
                    .html(html)
                    .unblock();

                initRegionSelect();
            }
        });
    });

    function initRegionSelect() {
        var $select = $('#trs_region_select');

        if (!$select.hasClass('enhanced')) {
            var select2_args = {
                minimumResultsForSearch: 10,
                placeholder: $select.data('placeholder'),
                templateSelection: function(selection) {
                    return selection.text.replace('&nbsp;', '').trim();
                },
            };

            $select.selectWoo(select2_args).addClass('enhanced');
        }
    }

    initRegionSelect();
});
