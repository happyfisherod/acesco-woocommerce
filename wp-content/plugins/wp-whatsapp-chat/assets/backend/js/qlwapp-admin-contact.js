(function ($) {

  var count = 0,
          timer;

  var is_blocked = function ($node) {
    return $node.is('.processing') || $node.parents('.processing').length;
  };

//fix
  var block = function () {
    //var $node = $('#qlwapp_modal');
    // if (!is_blocked($node)) {
    $('#qlwapp_modal').addClass('processing');
    //  }
  };

  var unblock = function () {
    $('#qlwapp_modal').removeClass('processing');
  };


  _.mixin({
    escapeHtml: function (attribute) {
      return attribute.replace('&amp;', /&/g)
              .replace(/&gt;/g, ">")
              .replace(/&lt;/g, "<")
              .replace(/&quot;/g, '"')
              .replace(/&#039;/g, "'");
    },
  });
  var Contact = Backbone.Model.extend({
    defaults: qlwapp_contact.args
  });

  var SubHeader = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });

  var SubFooter = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });

  var SubTabs = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });

  var SubContact = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });

  var SubViewChat = Backbone.View.extend({

    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });

  var SubVisibility = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });
  var SubInfo = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }

  });

  var LoadTemplate = Backbone.View.extend({
    templates: {},
    initialize: function (options) {
      this.templates.window = wp.template(options.subview);
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.attributes));
      return this;
    }
  });


  var ContactView = Backbone.View.extend({

    events: {
      'change input': 'enable',
      'change textarea': 'enable',
      'change select': 'enable',
      'click .media-modal-backdrop': 'close',
      'click .media-modal-close': 'close',
      'click .media-modal-prev': 'edit',
      'click .media-modal-next': 'edit',
      'change .media-modal-change': 'change',
      'change .media-modal-subview': 'subview',
      'submit .media-modal-form': 'submit'
    },
    templates: {},
    initialize: function () {
      _.bindAll(this, 'open', 'edit', 'change', 'subview', 'load', 'render', 'close', 'submit');
      this.init();
      this.open();
    },
    init: function () {
      this.templates.window = wp.template('qlwapp-modal-window');
    },
    LoadTemplate1: function (options) {
      var x = Backbone.View.extend({
        templates: {},
        initialize: function (options) {
          //  this.model.attributes = options.attributes;
          this.templates.window = wp.template(options.subview);
          // return this.init(); 
        },
        render: function () {
          var modal = this;
          modal.$el.html(modal.templates.window(modal.attributes));
          return this;
        }
      });
      return x;
    },
    assign: function (view, selector) {
      view.setElement(this.$(selector)).render();
    },
    render: function () {

      var modal = this;
      // get active tab from the previous modal
      var tab = this.$el.find('ul.wc-tabs li.active a').attr('href');
      modal.$el.html(modal.templates.window(modal.model.attributes));

      this.header = new SubHeader({subview: "subview-header", attributes: modal.model.attributes});
      this.footer = new SubFooter({subview: "subview-footer", attributes: modal.model.attributes});
      this.tabs = new SubTabs({subview: "subview-tabs", attributes: modal.model.attributes});
      this.contact = new SubContact({subview: "subview-contact", attributes: modal.model.attributes});
      this.contact_chat = new SubViewChat({subview: "subview-contact-chat", attributes: modal.model.attributes});
      this.info = new SubInfo({subview: "subview-contact-info", attributes: modal.model.attributes});
      this.visibility = new SubVisibility({subview: "subview-visibility", attributes: modal.model.attributes});

      this.assign(this.header, '#panel-header');
      this.assign(this.footer, '#panel-footer');
      this.assign(this.tabs, '#panel-tabs');
      this.assign(this.contact, '#panel-contact');
      this.assign(this.contact_chat, '#subpanel-contact-chat');
      this.assign(this.info, '#panel-info');
      this.assign(this.visibility, '#panel-visibility');

      _.delay(function () {
        modal.$el.trigger('qlwapp-enhanced-select');
        modal.$el.trigger('qlwapp-tab-panels', tab);
        //        modal.$el.trigger('init_tooltips');
      }, 100);
    },
    load: function () {
      var modal = this;

      block();

      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_edit_contact',
          nonce: qlwapp_contact.nonce.qlwapp_edit_contact,
          contact_id: this.model.attributes.id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          // block($modal); fix si se puede
        },
        complete: function () {
          unblock();
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {
            modal.model.set(response.data);
            modal.render();
          } else {
            alert(response.data);
          }
        }
      });
    },
    edit: function (e) {
      e.preventDefault();
      var modal = this,
              $button = $(e.target),
              contact_count = parseInt($('#qlwapp_contacts_table tr[data-contact_id]').length),
              order = parseInt(modal.model.get('order'));
      //var global 
      count++;
      if (timer) {
        clearTimeout(timer);
      }

      timer = setTimeout(function () {

        if ($button.hasClass('media-modal-next')) {
          order = Math.min(order + count, contact_count);
        } else {
          order = Math.max(order - count, 1);
        }
        modal.model.set({
          id: parseInt($('#qlwapp_contacts_table tr[data-contact_position=' + order + ']').data('contact_id'))
        });
        count = 0;
        modal.load();
      }, 300);
    },
    open: function (e) {
      $('body').addClass('modal-open').append(this.$el);
      if (this.model.attributes.id == undefined) {
        _.delay(function () {
          unblock();
        }, 100);
        return;
      }
      this.load();
    },
    update: function (e) {

      e.preventDefault();
      var $field = $(e.target),
              name = $field.attr('name'),
              value = $field.val();
      if (e.target.type === 'checkbox') {
        value = $field.prop('checked') === true ? 1 : 0;
      }

      this.model.attributes[name] = value;
      this.model.changed[name] = value;
    },
    change: function (e) {
      e.preventDefault();
      this.update(e);
//      this.render();
    },
    subview: function (e) {
      this.contact_chat.render();
    },
    reload: function (e) {
      if (this.$el.find('#qlwapp_modal').hasClass('reload')) {
        location.reload();
        return;
      }
      this.remove();
      return;
    },
    close: function (e) {
      e.preventDefault();
      this.undelegateEvents();
      $(document).off('focusin');
//      $('body').removeClass('modal-open');
      // if necesary reload... 
      this.$el.find('#qlwapp_modal').addClass('reload');
      this.reload(e);
      return;
    },
    enable: function (e) {
      $('.media-modal-submit').removeProp('disabled');
    },
    submit: function (e) {
      e.preventDefault();
      var modal = this,
              $modal = modal.$el.find('#qlwapp_modal'),
              $details = $modal.find('.attachment-details');
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_save_contact',
          nonce: qlwapp_contact.nonce.qlwapp_save_contact,
          contact_id: modal.model.attributes.id,
          contact_data: $('form', this.$el).serialize()
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          $('.media-modal-submit').prop('disabled', true);
          $details.addClass('save-waiting');
        },
        complete: function () {
          $details.addClass('save-complete');
          $details.removeClass('save-waiting');
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          //.log(response);
          if (response.success) {

            if (modal.model.attributes.id == undefined) {
              $modal.addClass('reload');
              modal.reload(e);
              modal.close(e);
            }

          } else {
            alert(response.data);
          }
        }
      });
      return false;
    }
  });
  var ContactModal = Backbone.View.extend({
    initialize: function (e) {
      var $button = $(e.target),
              contact_id = $button.closest('[data-contact_id]').data('contact_id');
      var model = new Contact();
      model.set({
        id: contact_id
      });
      new ContactView({
        model: model
      }).render();
    }
  });

  var exist_modal = false;
  $('.qlwapp_settings_edit').on('click', function (e) {
    e.preventDefault();
    if (!exist_modal) {
      new ContactModal(e);
      exist_modal = true;
    }
  });

  $('#qlwapp_contact_add').on('click', function (e) {

    e.preventDefault();
    new ContactModal(e);
  });

  $('.qlwapp_settings_delete').on('click', function (e) {
    e.preventDefault();
    var nonce = $('#qlwapp_delete_contact_nonce').val();
    var $button = $(e.target),
            contact_id = $button.closest('[data-contact_id]').data('contact_id');
    if (!confirm(qlwapp_contact.message.contact_confirm_delete)) {
      return false;
    } else {
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_delete_contact',
          nonce: nonce,
          contact_id: contact_id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
//        $spinner.addClass('is-active');
        },
        complete: function () {
//        $spinner.removeClass('is-active');
        },
        error: function (response) {
          console.log(response);
        },
        success: function (response) {

          if (response.data) {
            location.reload();
          } else {
            alert(response.data);
          }
        }
      });
    }
  });
  // Sorting
  // ---------------------------------------------------------------------------
  $('table#qlwapp_contacts_table tbody').sortable({
    items: 'tr',
    cursor: 'move',
    axis: 'y',
    handle: 'td.sort',
    scrollSensitivity: 40,
    helper: function (event, ui) {
      ui.children().each(function () {
        $(this).width($(this).width());
      });
      ui.css('left', '0');
      return ui;
    },
    start: function (event, ui) {
      ui.item.css('background-color', '#f6f6f6');
    },
    stop: function (event, ui) {
      ui.item.removeAttr('style');
      ui.item.trigger('updateMoveButtons');
      ui.item.trigger('updateSaveButton');
    }
  });
  $(document).on('updateSaveButton', function () {
    $('#qlwapp_contact_order').removeProp('disabled');
  });
  // Re-order buttons
  // ---------------------------------------------------------------------------
  $('.wc-item-reorder-nav').find('.wc-move-up, .wc-move-down').on('click', function () {

    var moveBtn = $(this),
            $row = moveBtn.closest('tr');
    moveBtn.focus();
    var isMoveUp = moveBtn.is('.wc-move-up'),
            isMoveDown = moveBtn.is('.wc-move-down');
    if (isMoveUp) {
      var $previewRow = $row.prev('tr');
      if ($previewRow && $previewRow.length) {
        $previewRow.before($row);
//					wp.a11y.speak( params.i18n_moved_up );
      }
    } else if (isMoveDown) {
      var $nextRow = $row.next('tr');
      if ($nextRow && $nextRow.length) {
        $nextRow.after($row);
//					wp.a11y.speak( params.i18n_moved_down );
      }
    }

    moveBtn.focus(); // Re-focus after the container was moved.
    moveBtn.closest('table').trigger('updateMoveButtons');
    moveBtn.closest('table').trigger('updateSaveButton');
  });
  $('.wc-item-reorder-nav').closest('table').on('updateMoveButtons', function () {
    var table = $(this),
            lastRow = $(this).find('tbody tr:last'),
            firstRow = $(this).find('tbody tr:first');
    table.find('.wc-item-reorder-nav .wc-move-disabled').removeClass('wc-move-disabled')
            .attr({'tabindex': '0', 'aria-hidden': 'false'});
    firstRow.find('.wc-item-reorder-nav .wc-move-up').addClass('wc-move-disabled')
            .attr({'tabindex': '-1', 'aria-hidden': 'true'});
    lastRow.find('.wc-item-reorder-nav .wc-move-down').addClass('wc-move-disabled')
            .attr({'tabindex': '-1', 'aria-hidden': 'true'});
  });
  $('table#qlwapp_contacts_table tbody').trigger('updateMoveButtons');
//save order of contact 
// Ajax order Submit
  $(document).on('submit', '#qlwapp_contacts_form', function (e) {
    e.preventDefault();
    var $form = $(this),
            $spinner = $form.find('.settings-save-status .spinner'),
            $saved = $form.find('.settings-save-status .saved');
    $.ajax({
      url: ajaxurl,
      data: {
        action: 'qlwapp_save_contact_order',
        nonce: qlwapp_contact.nonce.qlwapp_save_contact_order,
        contact_data: $form.serialize()
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
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
        $('#qlwapp_contact_order').prop('disabled', true);
        if (response.success) {
          setTimeout(function () {
            $saved.removeClass('is-active');
          }, 1500);
        } else {
          alert(response.data);
        }
      }
    });
    return false;
  });
  $(document).on('qlwapp-tab-panels', function (e, active) {
    var $modal = $(e.target),
            $tabs = $modal.find('ul.qlwapp-tabs'),
            $active = $tabs.find('a[href="' + active + '"]');
    $tabs.show();
    $tabs.find('a').click(function (e) {
      e.preventDefault();
      var panel_wrap = $(this).closest('div.panel-wrap');
      $tabs.find('li', panel_wrap).removeClass('active');
      $(this).parent().addClass('active');
      $('div.panel', panel_wrap).hide();
      $($(this).attr('href')).show();
    });
    if ($active.length && $($active.attr('href')).length) {
      $active.click();
    } else {
      $tabs.find('li.active').find('a').click();
    }

  });
})(jQuery);