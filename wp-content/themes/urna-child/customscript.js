(function( $ ) {
    $( document ).ready(function() {
    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
           result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
     }

    setTimeout(function(){ 
        $.each($('.cartPage'), function(){
            var url = $(this).attr('href');
            var newUrl = url +'?'+ makeid(5);
            $(this).attr('href', newUrl);
        });
    }, 1000);

    cart_click = $('.cart-popup');
    cart_click.on('shown.bs.dropdown', function (event) {
        var buttonCarts = $(this).find('.cart-button-popup');
         $.each(buttonCarts, function(){
            var url = $(this).attr('href');
            var newUrl = url +'?'+ makeid(5);
            $(this).attr('href', newUrl);
        });

        $(document).on('click', '.remove_from_cart_button', function(e){
            e.preventDefault();

            var href = $(this).attr('href');
            window.location.href = href;

            /*$.ajax({
                type: 'POST',
                dataType: 'json',
                url: wc_add_to_cart_params.ajax_url,
                data: {
                  action: "product_remove",
                  product_id: product_id,
                  cart_item_key: cart_item_key
                },
                beforeSend: function () {
                  thisItem.find('.mini_cart_content').append('<div class="ajax-loader-wapper"><div class="ajax-loader"></div></div>').fadeTo("slow", 0.3);
                  event.stopPropagation();
                },
                success: response => {
                  this._onRemoveSuccess(response, product_id);
          
                  $(document.body).trigger('wc_fragments_refreshed');
                }
              });*/
        });

        
    }).on('hidden.bs.dropdown', function (event) {
        setTimeout(function(){ 
            var buttonCarts = $(this).find('.cart-button-popup');
            $.each(buttonCarts, function(){
                var url = $(this).attr('href');
                var splitUrl = url.split('?')[0];
                $(this).attr('href', splitUrl);
            });

        }, 5000);

        
        
    });

    $('ul#shipping_method input').each(function () {
        var $this = $(this);
        if ($this.val().indexOf("local_pickup") != -1) {
          if ($this.prop('checked')) {
            hideShippingFields();
          }
          if ($this.attr('type') == 'hidden') {
            hideShippingFields();
          }
        }
      });
    
    
      $(document).on('change', 'ul#shipping_method input', function () {
        var $this = $(this);
        if ($this.val().indexOf("local_pickup") != -1) {
          hideShippingFields();
        } else {
          showShippingFields();
        }
      });
    
      function hideShippingFields() {
        var $col1 = $('.col-1');
        $('#ship-to-different-address-checkbox').prop('checked', false);
        $('.woocommerce-shipping-fields').closest('.col-2').hide();
        $('.woocommerce-additional-fields').appendTo($col1);
        $col1.css({
          marginLeft: "auto",
          marginRight: "auto"
        });
      }
    
      function showShippingFields() {
        $col2 = $('.woocommerce-shipping-fields').closest('.col-2');
        var $col1 = $('.col-1');
        $('.woocommerce-additional-fields').appendTo($col2);
        $col2.show();
        $col1.css({
          marginLeft: "",
          marginRight: ""
        });
      }




    });
})( jQuery );