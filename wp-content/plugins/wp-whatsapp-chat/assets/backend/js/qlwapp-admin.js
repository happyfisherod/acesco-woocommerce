(function ($) {

  // Disbled all
  // ---------------------------------------------------------------------------
  function disabled(value) {
    if (value) {
      $('#submit').attr('disabled', true);
    } else {
      $('#submit').attr('disabled', false);
    }
  }

  disabled(true);

  $('form').change(function (e) {
    disabled(false);
  });

  $('.button').click(function () {
    disabled(false);
  });
  $('button').click(function () {
    disabled(false);
  });

  $('.qlwapp-color-field').wpColorPicker({
    change: function (event, ui) {
      disabled(false);
    },
    clear: function (event, ui) {
      disabled(false);
    },
  });

  $(document).on('tinymce_change', function (e) {
    disabled(false);
  });

  $(document).on('qlwapp-enhanced-select', function (e) {
    $('.qlwapp-select2').filter(':not(.enhanced)').each(function () {
      var select2_args = {
        allowClear: false,
        theme: 'default',
        minimumResultsForSearch: -1
      };

      $(this).select2(select2_args).addClass('enhanced');
    });
    $('.qlwapp-select2-search').filter(':not(.enhanced)').each(function () {
      var $select = $(this),
              name = $(this).data('name');
      var select2_args = {
        allowClear: true,
        ajax: {
          url: ajaxurl,
          dataType: 'json',
          //delay: 500,
          data: function (params) {
            return {
              name: name,
              per_page: 10,
              q: params.term || 0,
              selected: $select.select2('val') || 0,
              action: 'qlwapp_get_posts',
              nonce: qlwapp.nonce.qlwapp_get_posts
            };
          },
          processResults: function (response) {

            var options = [];

            if (response) {
              $.each(response, function (id, title) {
                options.push({id: id, text: title});
              });
            }
            return {
              results: options
            };
          },
          cache: true
        },
        minimumInputLength: 3
      };

      $(this).select2(select2_args).addClass('enhanced');

    });

    // $('.qlwapp-select2').select2({allowClear: false, theme: 'default', minimumResultsForSearch: -1});

  }).trigger('qlwapp-enhanced-select');



  $('.qlwapp-color-field').wpColorPicker();

  $(document).on('click', '.upload_image_button', function (e) {
    e.preventDefault();

    var send_attachment_bkp = wp.media.editor.send.attachment,
            button = $(this);

    wp.media.editor.send.attachment = function (props, attachment) {
      $(button).parent().prev().attr('src', attachment.url);
      $(button).prev().val(attachment.url).trigger('change');
      wp.media.editor.send.attachment = send_attachment_bkp;
    }

    wp.media.editor.open(button);

    return false;
  });

  $(document).on('click', '.remove_image_button', function (e) {
    e.preventDefault();

    var src = $(this).parent().prev().attr('data-src');

    $(this).parent().prev().attr('src', src);

    $(this).prev().prev().val('').trigger('change');

    return false;
  });

  // Ajax
  // ---------------------------------------------------------------------------
  $(document).on('qlwapp.save', 'form', function (e, action, nonce) {

    var $form = $(e.currentTarget),
            $spinner = $form.find('.settings-save-status .spinner'),
            $saved = $form.find('.settings-save-status .saved');

    $.ajax({
      url: ajaxurl,
      data: {
        action: action,
        nonce: nonce,
        form_data: $form.serialize()
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
        disabled(true);
        $spinner.addClass('is-active');
      },
      complete: function () {
        $spinner.removeClass('is-active');
      },
      error: function (response) {
        console.log(response);
      },
      success: function (response) {
        $saved.addClass('is-active');
        if (response.success) {
          setTimeout(function () {
            $saved.removeClass('is-active');
          }, 2000);
          console.log(response.data);
        } else {
          alert(response.data);
        }
      }
    });

    return false;
  });

  // Ajax Button Submit
  // ---------------------------------------------------------------------------
  $(document).on('submit', '#qlwapp_button_form', function (e) {
    e.preventDefault();

    var $form = $(this),
            nonce = $form.find('#qlwapp_button_form_nonce').val();

    $form.trigger('qlwapp.save', ['qlwapp_save_button', nonce]);

  });

  // Ajax BOX Submit
  // ---------------------------------------------------------------------------
  $(document).on('submit', '#qlwapp_box_form', function (e) {
    e.preventDefault();

    var $form = $(this),
            nonce = $form.find('#qlwapp_box_form_nonce').val();

    $form.trigger('qlwapp.save', ['qlwapp_save_box', nonce]);

  });

  // Ajax Display Submit
  // ---------------------------------------------------------------------------
  $(document).on('submit', '#qlwapp_display_form', function (e) {

    e.preventDefault();

    var $form = $(this),
            nonce = $form.find('#qlwapp_display_form_nonce').val();

    $form.trigger('qlwapp.save', ['qlwapp_save_display', nonce]);

  });

  // Ajax Scheme Submit
  // ---------------------------------------------------------------------------
  $(document).on('submit', '#qlwapp_scheme_form', function (e) {

    e.preventDefault();

    var $form = $(this),
            nonce = $form.find('#qlwapp_scheme_form_nonce').val();

    $form.trigger('qlwapp.save', ['qlwapp_save_scheme', nonce]);

  });


})(jQuery);