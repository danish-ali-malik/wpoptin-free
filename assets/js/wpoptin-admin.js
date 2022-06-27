var loader_html = '<div class="wpop-ajax-loader"><img src="' +
    wpoptin.logo_url + '"/></div>';
jQuery(window).load(function() {
  // Animate loader off screen
  jQuery('.xo_loader_holder').fadeOut('slow');
});

jQuery(document).ready(function($) {

  M.AutoInit();
  jQuery('.wpop-tooltipped').tooltip();

  jQuery('.datepicker').datepicker({
    minDate: new Date(),
    format: 'yyyy-mm-dd',
    setDefaultDate: true,
  });

  $('.xo-tabs	.xo-tab .mdl-tabs__tab').click(function() {

    jQuery('.xo-tabs .xo-tab .mdl-tabs__tab').removeClass('active');

    jQuery(this).addClass('active');

    var id = $(this).attr('href');

    jQuery('.xo-tab-content .tab-content').
        removeClass('active').
        css('display', 'none');

    jQuery('.xo-tab-content .tab-content' + id).
        addClass('active').
        css('display', 'block');

    // console.log(id);

  });

  $('.wpop-modal-trigger').click(function() {

    $('.modal').modal('close');

    var id = $(this).data('href');

    $('#' + id).modal('open');
  });

  var mediaUploader;

  $('.wpop_feat_img_btn_wraper').on('click', function(e) {
    e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: wpoptin.feat_img_title,
      button: {
        text: wpoptin.feat_img_title,
      },
      multiple: false,
    });

    // When a file is selected, grab the URL and set it as the text field's
    // value
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#wpop_feat_img').text(' ');
      $('#wpop_feat_img').val(attachment.url);
    });
    // Open the uploader dialog
    mediaUploader.open();
  });

  /**
   * Accepts either a URL or querystring and returns an object associating
   * each querystring parameter to its value.
   *
   * Returns an empty object if no querystring parameters found.
   */
  function wpopgetUrlParams(urlOrQueryString = location.search) {
    if ((i = urlOrQueryString.indexOf('?')) >= 0) {
      const queryString = urlOrQueryString.substring(i + 1);
      if (queryString) {
        return _mapUrlParams(queryString);
      }
    }

    return {};
  }

  /**
   * Helper function for `getUrlParams()`
   * Builds the querystring parameter to value object map.
   *
   * @param queryString {string} - The full querystring, without the leading
   *     '?'.
   */
  function _mapUrlParams(queryString) {
    return queryString.split('&').map(function(keyValueString) {
      return keyValueString.split('=');
    }).reduce(function(urlParams, [key, value]) {
      if (Number.isInteger(parseInt(value)) && parseInt(value) == value) {
        urlParams[key] = parseInt(value);
      }
      else {
        urlParams[key] = decodeURI(value);
      }
      return urlParams;
    }, {});
  }

  $('#sortable-units.xo-tabs .tab-content').click(function() {
    url = $(this).attr('href');
    window.history.pushState('newurl', 'newurl', url);
  });

  if ($('.xo_accounts_table').length == 0) {
    $('.no_xo_accounts').slideDown('slow');
  }

  if ($('body').hasClass('wpoptin_page_wpop_new')) {

    $('html').css('padding', '0px');
  }

  var Qid = wpopgetUrlParams()['wpop_optin_id'];
  var Qgoal = wpopgetUrlParams()['goal'];

  if (!Qid && Qgoal) {
    if (Qgoal === 'optin') {
      $('.xo_wrap').addClass('xo_fullscreen');
      $('.xo_content_tab a').addClass('disabled  mdl-tabs__tab');
      $('.xo_triggers_tab a').addClass('disabled  mdl-tabs__tab');
      $('.xo-tab a, .tab-content').removeClass('active');
      $('.xo_acount_tab a, #accounts-panel').addClass('active');
    }
    else {
      $('.xo_triggers_tab a').addClass('disabled  mdl-tabs__tab');
    }
  }

  /* Shows welcome div.*/
  if ($('.xo-settings-genral-wrapper').length >= 1) {
    $('.no_xo_optins').hide();
  }

  /* Selected goal*/
  $('.wpop-select-goal').click(function() {

    $('#wpop-selected-goal').val($(this).data('goal'));

    $('.wpop-goals-holder').slideUp('slow', function() {
      $('.wpop-types-holder').slideDown();
    });

  });

  /* Selected Type*/
  $('.wpop-select-type, .wpop-select-type-new').click(function() {
    jQuery('.xo_loader_holder').show();

    var goal = $('#wpop-selected-goal').val();

    var url = $(this).data('href') + goal;

    url = url + '&type=' + $(this).data('type');

    // console.log(url);return;

    window.location.replace(url);
  });

  /* Save Goal val.*/
  $('.wpop-select-type-new').click(function() {
    $('.wpop-types-holder').slideUp();
    var goal = $('#wpop-selected-goal').val();
    jQuery('.xo-close').fadeIn('slow');

    $('.xo_content_tab a').addClass('disabled  mdl-tabs__tab');
    $('.xo_triggers_tab a').addClass('disabled  mdl-tabs__tab');
    $('#xo_account_goal_hidden').val(goal);
    $('#wpop_new_goal').val(goal);
    $('.xo_new_holder').addClass(goal);
    $('.xo_wrap').addClass('xo_fullscreen');
    $('.xo_wrap').addClass(goal);
    jQuery('.xo-settings').css({
      'padding-left': '0px',
      'padding-top': '0px',
      'padding-bottom': '0px',
    });

    $('.xo_new_holder').fadeIn('slow');

    /* Save Optin accounts.*/
    if (goal == 'optin') {

      $('.xo-tab a, .tab-content').removeClass('active');
      $('.xo_acount_tab a, #accounts-panel').addClass('active');
    }
    else {
      $('.xo_acount_tab').remove();
      $('#accounts-panel').remove();
      $('.xo_content_tab a').removeClass('disabled');
    }

  });

  /**
   * Publish campagin when publish button clicked
   *
   * @since 1.2.0
   */
  $( ".xo_wrap .wpop-publish-campaign" ).on( "click", function() {

    const id = $( this ).data('id');
    if( !id ){
      return;
    }
    const status = $( this ).data('status');
    var ChangedStatus; var message;
    if( status === 'draft' ){
       ChangedStatus = 'publish';
       message = wpoptin.publishing;
    }else{
       ChangedStatus = 'draft';
       message = wpoptin.SavingAsDraft;
    }

    M.Toast.dismissAll();
    M.toast({
      html: message,
      displayLength: 400000,
    });

    const data = {
      action: 'wpop_publish_campaign',
      id: id,
      status: status,
      wpop_nonce: wpoptin.nonce,
    };
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {
        M.Toast.dismissAll();
        M.toast({
          html: response.data['message'],
        });

        if (response.success) {
          $( ".xo_wrap .wpop-publish-campaign" ).data('status', ChangedStatus );
          $( ".xo_wrap .wpop-publish-campaign" ).html(' ').html( response.data['buttonText'] );
        }
      },

    });
  });

  $('.xo_account_provider').change(function() {

    var current_val = $(this).val();

    if (current_val == 'mailpoet' && wpoptin.free_plan) {

      $('.modal').modal('close');
      $('#wpop-upgrade-mail-poet').modal('open');

      return;

    }

    $('.xo_sub_holder .wpop-ajax-loader').remove();
    $('.xo_sub_holder').append(loader_html);

    $('.xo_invalid').remove();

    if (current_val == '0') {
      $('.xo_lists_holder').slideUp('slow');
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      return;
    }

    $('.xo_accounts_li').slideUp();

    if ($('.xo_accounts_li_' + current_val).length > 0) {
      $('.xo_lists_holder').slideDown('slow');
      return;
    }
    var account_data = {
      action: 'wpop_get_account',
      service_provider: $(this).val(),
      wpop_nonce: wpoptin.nonce,
    };

    //window.alert(goal);
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: account_data,
      dataType: 'json',
      success: function(response) {

        $('.xo_sub_holder .wpop-ajax-loader').fadeOut('slow');
        if (response.success) {
          $('.xo_lists_holder').html('');
          $('.xo_lists_holder').append(response.data);
          $('.xo_lists_holder').slideDown('slow');
          $('.xo_account_name').formSelect({
            allowClear: true,
          });
        }
        else {
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_account_add_holder_' + current_val).slideDown('slow');
        }
      },

    });

  });

  /* Change status.*/
  $('.wpop-delete-feat-img').click(function() {

    M.Toast.dismissAll();

    M.toast({
      html: wpoptin.deleting,
      displayLength: 400000,
    });

    var outer_this = $(this);

    var id = $(this).data('id');
    var data = {
      action: 'wpop_remove_featured_img',
      id: id,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {
        M.Toast.dismissAll();
        M.toast({
          html: response.data,
        });

        if (response.success) {

          outer_this.fadeOut('slow');
          jQuery('#wpop_feat_img').val(' ');
          jQuery('#wpop_feat_img').
              attr('placeholder', '(' + wpoptin.optional + ')');
        }

      },

    });

  });

  /* Change status.*/
  $('.xo_status_switch').click(function() {

    M.Toast.dismissAll();

    M.toast({
      html: wpoptin.updating,
      displayLength: 400000,
    });

    if ($(this).data('status') === 'active') {
      var status = 'draft';
    }
    else {
      var status = 'publish';
    }
    var id = $(this).data('id');
    var data = {
      action: 'wpop_change_status',
      id: id,
      status: status,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {
        M.Toast.dismissAll();
        M.toast({
          html: response.data,
        });
      },

    });

  });

  

  /* Submit Accounts values in database.*/
  $('.xo_submit_btn').click(function() {
    $('.xo_submit_btn').attr('disabled', 'disabled');
    $('.xo_sub_holder .wpop-ajax-loader').remove();
    $('.xo_sub_holder').append(loader_html);
    M.Toast.dismissAll();

    var account_provider = $('.xo_account_provider option:selected').val();
    var acount_id = $('.xo_account_name option:selected').val();
    var list_id = $('.xo_account_list_li option:selected').val();
    if (account_provider == '0') {
      $('.xo_account_provider_li').
          append('<p class="xo_invalid">Please select account provider</p>');
      $('.xo_submit_btn').removeAttr('disabled');
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      return;
    }

    if (acount_id == null || acount_id === undefined) {
      $('.xo_accounts_li').
          append('<p class="xo_invalid">Please select any account</p>');
      $('.xo_submit_btn').removeAttr('disabled');
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      return;
    }

    if (list_id == null || list_id === undefined) {
      $('.xo_account_list_li').
          append('<p class="xo_invalid">Please select any list</p>');
      $('.xo_submit_btn').removeAttr('disabled');
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      return;
    }

    var currentUrl = document.location;
    var goal = $('#xo_account_goal_hidden').val();
    var type = $('#xo_account_type_hidden').val();
    var redirect = $(this).data('snc');
    var data = {
      action: 'wpop_save_acounts_data',
      acount_id: acount_id,
      list_id: list_id,
      type: type,
      account_provider: account_provider,
      edit_id: wpopgetUrlParams()['wpop_optin_id'],
      goal: goal,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {
        $('.xo_submit_btn').removeAttr('disabled');
        if (response.success) {

          $('.xo_sub_holder .wpop-ajax-loader').remove();

          M.toast({
            html: response.data['1'],
          });

          $('#xo_edit_id, #xo_new_id, #wpop_edit_id').val(response.data['0']);
          $('#wpop_new_goal').val(goal);
          $('.xo_content_tab a').removeClass('disabled');
          $('.xo_triggers_tab a').removeClass('disabled');
          $('#xo_design_tab a').removeClass('disabled');
          $('#xo_hidden_id').val(response.data['0']);
          var Qexists = window.location.search.indexOf('wpop_optin_id=') >= 0;
          var Qgoal = window.location.search.indexOf('goal=') >= 0;
          $( '.xo_wrap .wpop-publish-campaign').attr('data-id', response.data['0']);
          $( '.xo_wrap .wpop-publish-campaign').fadeIn();
          if (response.data['2']) {
            $('#xo_all_skins_wrap .row').html(' ');
            $('#xo_all_skins_wrap .row').html(response.data['2']);
          }

          if (Qgoal === false && Qexists === false) {
            var finalUrl = currentUrl + '&wpop_optin_id=' + response.data['0'] +
                '&goal=' + goal;
            window.history.pushState('newurl', 'newurl', finalUrl);

          }
          if (Qgoal && Qexists === false) {
            var finalUrl = currentUrl + '&wpop_optin_id=' + response.data['0'];
            window.history.pushState('newurl', 'newurl', finalUrl);
          }

          if (redirect == true) {
            $('.xo-tabs .xo-tab .mdl-tabs__tab').removeClass('active');
            $('.xo_content_tab .mdl-tabs__tab').addClass('active');
            $('.xo-tab-content .tab-content').
                removeClass('active').
                css('display', 'none');
            $('#content-panel').addClass('active').css('display', 'block');
            $('.xo_sub_holder .wpop-ajax-loader').remove();
          }

        }
        else {
          M.toast({
            html: response.data,
          });
          $('.xo_sub_holder .wpop-ajax-loader').remove();
          return;
        }

      },

    });

  });

  /* Delete xo Offer/optin.*/
  $('.wpop_remove_db').click(function() {

    var outer_this = $(this);
    var remove_id = $(this).data('remove_id');
    var child_id = $(this).data('varint_id');

    M.Toast.dismissAll();

    M.toast({
      html: wpoptin.deleting,
      displayLength: 40000,
    });

    var data = {
      action: 'wpop_delete_entity',
      remove_id: remove_id,
      child_id: child_id,
      wpop_nonce: wpoptin.nonce,
    };
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

        if (response.success) {

          M.Toast.dismissAll();

          M.toast({
            html: response.data.notification,
          });

          if (response.data.parent_id) {
            jQuery('.row_' + response.data.parent_id).remove();

          }

          if (response.data.child_id) {
            jQuery('.row_' + response.data.child_id).remove();

          }

          if (jQuery('body').hasClass('toplevel_page_wpoptin')) {
            if (jQuery('.xo_offers_table tbody tr').length < '1') {
              jQuery('.xo_header #xo_stats').remove();
              jQuery('.xo-settings-genral-wrapper').remove();
              jQuery('.no_xo_optins').slideDown('slow');

            }

          }

          if (jQuery('body').hasClass('wpoptin_page_wpop_accounts')) {

            if (jQuery('.xo_accounts_table tbody tr').length < '1') {
              jQuery('.xo_header #xo_stats').remove();
              jQuery('.xo_accounts_table').remove();
              jQuery('.no_xo_accounts').slideDown('slow');

            }

          }

          if (outer_this.data('parent_id')) {
            var parent_id = outer_this.data('parent_id');

            $('.xo_offers_table .row_' + parent_id +
                ' .action_holder .xo_remove_varint').css('display', 'block');
          }

        }
        else {
          M.toast({
            html: response.data,
          });
        }
      },

    });

  });

  /* Add new post.*/
  jQuery('.xo_new_sub').on('click', function(event) {

    $('.xo_addNewform .wpop-ajax-loader').remove();
    $('.xo_addNewform').append(loader_html);

    $('.xo_new_sub').attr('disabled', 'disabled');

    var goal = $('#wpop_new_goal').val();

    M.Toast.dismissAll();

    event.preventDefault();
    var currentUrl = document.location;
    var redirect = $(this).data('snc');

    

    var form_data = $('.xo_addNewform').serialize();

    /* Offer bars settings.*/
    var goal_action = 'wpop_save_' + goal;
    var data = {
      action: goal_action,
      form_data: form_data,
      return_url: window.location.href,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        if (response.success) {

          $('.xo_new_sub').removeAttr('disabled');

          M.toast({
            html: response.data['0'],
          });

          $('#xo_design_tab a').removeClass('disabled');
          $('.xo_content_tab a').removeClass('disabled');
          $('.xo_triggers_tab a').removeClass('disabled');
          $('#wpop_edit_id').val(response.data['1']);
          $('#xo_new_id').val(response.data['1']);
          $( '.xo_wrap .wpop-publish-campaign').attr('data-id', response.data['1']);
          $( '.xo_wrap .wpop-publish-campaign').fadeIn();

          var Qexists = window.location.search.indexOf('wpop_optin_id=') >= 0;
          var Qgoal = window.location.search.indexOf('goal=') >= 0;

          if (response.data['3']) {

            $('.wpop-delete-feat-img').fadeIn('slow');
          }

          if (Qgoal === false && Qexists === false) {
            var finalUrl = currentUrl + '&wpop_optin_id=' + response.data['1'] +
                '&goal=' + goal;
            window.history.pushState('newurl', 'newurl', finalUrl);

          }
          if (Qgoal && Qexists === false) {
            var finalUrl = currentUrl + '&wpop_optin_id=' + response.data['1'];
            window.history.pushState('newurl', 'newurl', finalUrl);
          }

          if (response.data['2']) {
            $('#xo_all_skins_wrap .row').html(' ');
            $('#xo_all_skins_wrap .row').html(response.data['2']);
          }

          if (redirect == true) {

            $('.xo-tabs .xo-tab .mdl-tabs__tab').removeClass('active');
            $('.xo_triggers_tab .mdl-tabs__tab').addClass('active');
            $('.xo-tab-content .tab-content').
                removeClass('active').
                css('display', 'none');
            $('#triggers-panel').addClass('active').css('display', 'block');

            $('.xo_addNewform .wpop-ajax-loader').remove();
          }
          else {
            $('.xo_addNewform .wpop-ajax-loader').remove();
            return;
          }

        }
        else {

          M.toast({
            html: response.data,
            classes: 'rounded',
          });

          $('.xo_addNewform .wpop-ajax-loader').remove();
        }

      },

    });

  });

  /* New post conditions.*/
  jQuery('.xo_new_tnc').on('click', function(event) {

    M.Toast.dismissAll();

    $('.xo_addNew_tnc .wpop-ajax-loader').remove();
    $('.xo_addNew_tnc').append(loader_html);

    event.preventDefault();
    $('.xo_new_tnc').attr('disabled', 'disabled');
    var form_data = $('.xo_addNew_tnc').serialize();

    // console.log(form_data);return;

    var redirect = $(this).data('snc');
    var data = {
      action: 'wpop_save_triggers_and_conditions',
      form_data: form_data,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {
        $('.xo_new_tnc').removeAttr('disabled');
        M.toast({
          html: response.data,
        });
        if (response.success) {

          $('.xo_new_sub').removeAttr('disabled');

          if (redirect == true) {

            $('.xo-tabs .xo-tab .mdl-tabs__tab').removeClass('active');
            $('#xo_design_tab .mdl-tabs__tab').addClass('active');
            $('.xo-tab-content .tab-content').
                removeClass('active').
                css('display', 'none');
            $('#design-panel').addClass('active').css('display', 'block');
            $('.xo_addNew_tnc .wpop-ajax-loader').remove();
          }
          else {
            $('.xo_addNew_tnc .wpop-ajax-loader').remove();
            return;
          }

        }

      },

    });

  });

  /* Authenticate the mailchimp api.*/
  $('.xo_acount_auth_mailchimp').click(function() {

    M.Toast.dismissAll();

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#acount_name').val();
    var api = $('#acount_api').val();
    var post_id = $('#xo_hidden_id').val();
    var post_name = $('.xo_account_provider option:selected').val();

    var data = {
      action: 'wpop_mailchimp_authentication',
      acount_name: name,
      api_key: api,
      post_name: post_name,
      post_id: post_id,
      service_provider: $('.xo_account_provider').val(),
      wpop_nonce: wpoptin.nonce,
    };

    $('.xo_authBtn_li').append(loader_html);

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {
        // console.log(response); return;
        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {
          M.toast({
            html: response.data[2],
          });

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_name').formSelect();
          $('.xo_account_list').formSelect();

        }
        else {
          M.toast({
            html: response.data,
          });
        }

        return; //stop the execution of function
      },

    });
  });

  /* Authenticate the constant_contact api.*/
  $('.xo_acount_auth_constant_contact').click(function() {
    M.Toast.dismissAll();

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#cc_acount_name').val();
    var api = $('#cc_acount_api').val();
    var access_token = $('#cc_acount_token').val();
    var post_id = $('#xo_hidden_id').val();
    var service_provider = $('.xo_account_provider option:selected').val();

    var data = {
      action: 'wpop_constant_contact_authentication',
      acount_name: name,
      api_key: api,
      access_token: access_token,
      service_provider: service_provider,
      post_id: post_id,
      wpop_nonce: wpoptin.nonce,
    };
    $('.xo_authBtn_li').append(loader_html);
    //window.alert(goal);
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {

          M.toast({
            html: response.data[2],
          });

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list').formSelect();
          $('.xo_account_name').formSelect();

        }
        else {
          M.toast({
            html: response.data,
          });
        }

        return; //stop the execution of function
      },

    });
  });

  /* Authenticate the constant_contact api.*/
  $('.xo_acount_auth_campaign_monitor').click(function() {

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#cm_acount_name').val();
    var api = $('#cm_acount_api').val();
    var post_id = $('#xo_hidden_id').val();
    var service_provider = $('.xo_account_provider option:selected').val();

    var data = {
      action: 'wpop_campaign_monitor_authentication',
      acount_name: name,
      api_key: api,
      service_provider: service_provider,
      post_id: post_id,
      wpop_nonce: wpoptin.nonce,
    };
    $('.xo_authBtn_li').append(loader_html);
    //window.alert(goal);
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list').formSelect();
          $('.xo_account_name').formSelect();

        }
        else {

          $('.xo_invalid').remove();
          $('.xo_apikey_li').
              append('<p class="xo_invalid">Invalid Api key</p>');
        }

        return; //stop the execution of function
      },

    });
  });

  /* Authenticate the mad_mimi api.*/
  $('.xo_acount_auth_mad_mimi').click(function() {

    M.Toast.dismissAll();

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#mm_acount_name').val();
    var api = $('#mm_acount_api').val();
    var username = $('#mm_user_name').val();
    var post_id = $('#xo_hidden_id').val();
    var service_provider = $('.xo_account_provider option:selected').val();

    var data = {
      action: 'wpop_mad_mimi_authentication',
      acount_name: name,
      api_key: api,
      username: username,
      service_provider: service_provider,
      post_id: post_id,
      wpop_nonce: wpoptin.nonce,
    };
    $('.xo_authBtn_li').append(loader_html);
    //window.alert(goal);
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {

          M.toast({
            html: response.data[2],
          });

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list').formSelect();
          $('.xo_account_name').formSelect();

        }
        else {
          M.toast({
            html: response.data,
          });
        }

        return; //stop the execution of function
      },

    });
  });

  /* Authenticate the mailpoet api.*/
  $('.xo_acount_auth_mailpoet').click(function() {

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#mp_acount_name').val();
    var post_id = $('#xo_hidden_id').val();
    var service_provider = $('.xo_account_provider option:selected').val();

    var data = {
      action: 'xo_mailpoet_auth_save',
      acount_name: name,
      service_provider: service_provider,
      post_id: post_id,
      wpop_nonce: wpoptin.nonce,
    };
    $('.xo_authBtn_li').append(loader_html);
    //window.alert(goal);
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list').formSelect();
          $('.xo_account_name').formSelect();

        }
        else {

          $('.xo_invalid').remove();
          $('.xo_account_add_holder_mailpoet li:first-child').
              append('<p class="xo_invalid">' + response.data + '</p>');
        }

        return; //stop the execution of function
      },

    });
  });

  /* Authenticate and add account only.*/
  $('.xo_reload_account').click(function() {
    $('.no_xo_accounts').fadeOut('slow');
    $('.xo_accounts_table').fadeIn('slow');
  });

  /* Editing Account.*/
  $('.xo_acount_page_edit').click(function() {

    $('.xo_accounts_table').fadeOut('slow');
    $('.no_xo_accounts').fadeIn('slow');
    var service_name = $(this).data('service_name');
    var account_name = $(this).data('account_name');
    var api_key = atob($(this).data('api_key'));
    var account_id = $(this).data('account_id');

    $('.xo_account_provider_page').
        val(service_name).
        formSelect().
        trigger('change');
    $('.xo_account_add_holder #acount_name').val(account_name);
    $('.xo_account_add_holder #acount_api').val(api_key);
    $('.xo_account_add_holder #acount_id').val(account_id);

    $('.xo_account_add_holder label').addClass('active');

  });

  /* Authenticate and add account only.*/
  $('.xo_acount_auth_page').click(function() {

    $('.xo_authBtn_li .wpop-ajax-loader').remove();
    var name = $('#acount_name').val();
    var api = $('#acount_api').val();
    var acount_id = $('#acount_id').val();
    var post_name = $('.xo_account_provider_page option:selected').val();

    var data = {
      action: 'xo_acount_data_page',
      acount_name: name,
      api_key: api,
      acount_id: acount_id,
      post_name: post_name,
      service_provider: $('.xo_account_provider_page').val(),
      wpop_nonce: wpoptin.nonce,
    };
    $('.xo_authBtn_li').append(loader_html);

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {
        //console.log(response); return;
        $('.xo_authBtn_li .wpop-ajax-loader').remove();
        if (response.success) {

          $('.xo_lists_holder').html('');

          $('.xo_lists_holder').append(response.data[0]);
          $('.xo_account_name').val(response.data[1]).trigger('change');
          $('.xo_account_add_holder').slideUp('slow');
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list').formSelect();
          $('.xo_account_name').formSelect();

        }
        else {
          $('.xo_invalid').remove();
          $('.xo_apikey_li').
              append('<p class="xo_invalid">Invalid Api key</p>');
        }

        return; //stop the execution of function
      },

    });
  });

  /* Get specific account list.*/
  $(document).on('change', '.xo_account_name', function() {
    var current_val = $(this).val();
    var selected_provider = $('.xo_account_provider').val();

    $('.xo_sub_holder .wpop-ajax-loader').remove();
    $('.xo_sub_holder').append(loader_html);

    if (current_val == '0') {
      $('.xo_sub_holder .wpop-ajax-loader').remove();
    }

    if (current_val == 'new') {
      $('.xo_account_list_li').slideUp('slow');
      $('.xo_account_add_holder_' + selected_provider).slideDown('slow');

    }
    else {
      $('.xo_account_add_holder').slideUp('slow');

    }
    if ($('.xo_account_list_li_' + current_val).length > 0) {
      $('.xo_account_list_li').slideUp('slow');
      $('.xo_account_list_li_' + current_val).slideDown('slow');
      return;
    }
    var account_data = {
      action: 'xo_get_accountLists',
      acount_id: $(this).val(),
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: account_data,
      dataType: 'json',
      success: function(response) {
        $('.xo_sub_holder .wpop-ajax-loader').fadeOut('slow');
        if (response.success) {
          $('.xo_sub_holder').slideDown('slow');
          $('.xo_account_list_li').slideUp('slow');
          $('.xo_accounts_li').slideDown('slow');
          $('.xo_account_provider_li').slideDown('slow');
          $('.xo_lists_holder').append(response.data);
          $('.xo_account_list').formSelect();
        }

      },
    });

  });

  /* Show Account Add Html.*/
  $(document).on('change', '.xo_account_provider_page', function() {
    var current_val = $(this).val();

    if (current_val == 'mailpoet' && wpoptin.free_plan) {

      $('.modal').modal('close');
      $('#wpop-upgrade-mail-poet').modal('open');

      return;

    }

    $('.xo_sub_holder .wpop-ajax-loader').remove();
    $('.xo_sub_holder').append(loader_html);

    if (current_val == '0') {
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      $('.xo_account_add_holder').slideUp('slow');
    }
    else {
      $('.xo_account_add_holder').slideUp('slow');
      $('.xo_sub_holder .wpop-ajax-loader').remove();
      $('.xo_account_add_holder_' + current_val).slideDown('slow');
    }

  });

  //Account Add html holder show
  jQuery(document).on('click', '#xo_account_add', function($) {
    jQuery('.xo_accounts_table').slideUp('slow');
    jQuery('.no_xo_accounts').slideDown('slow');

  });

  //On skin add new
  jQuery(document).on('click', '#xo_skin_add', function($) {
    jQuery('#xo_all_skins_wrap').slideUp('slow');
    jQuery('.xo_loader_holder').css('display', 'block');
    jQuery('body').addClass('xo_skins_body');
    jQuery('.xoptin_page_xo_skins .xo_wrap').
        addClass('xo_wraper_skins xo_skins_wrap');

    jQuery('html').addClass('xo_skins_html');

    setTimeout(function() {
      jQuery('.xo_loader_holder').fadeOut('slow');
      jQuery('#xo_new_skin_form_wraper').fadeIn('slow');
    }, 1500);

  });

  var mediaUploader;

  $('#wpop_skin_feat_img_btn').on('click', function(e) {
    e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
        text: 'Choose Image',
      },
      multiple: false,
    });

    // When a file is selected, grab the URL and set it as the text field's
    // value
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#xo_new_skin_form_wraper #wpop_skin_feat_img').
          next('.mdl-textfield__label').
          text(' ');
      $('#wpop_skin_feat_img').val(attachment.url);
    });
    // Open the uploader dialog
    mediaUploader.open();
  });

  /* Add new skin.*/
  jQuery('.xo_new_skin_sub').on('click', function(event) {

    event.preventDefault();

    M.Toast.dismissAll();

    $('.xo_new_skin_form .wpop-ajax-loader').remove();
    $('.xo_new_skin_form').append(loader_html);

    $('.xo_new_skin_sub').attr('disabled', 'disabled');

    var form_data = $('.xo_new_skin_form').serialize();

    var data = {
      action: 'wpop_create_skin',
      form_data: form_data,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response) {

        $('.xo_new_skin_form .wpop-ajax-loader').remove();

        M.toast({
          html: response.data,
        });
      },

    });

  });

  /* Delete skin.*/
  $('.xo_del_skin').click(function(e) {

    M.Toast.dismissAll();

    event.preventDefault();

    M.toast({
      html: wpoptin.deleting,
    });

    var outer_this = $(this);
    var remove_id = $(this).data('remove_skin_id');

    var data = {
      action: 'wpop_delete_skin',
      remove_id: remove_id,
      wpop_nonce: wpoptin.nonce,
    };
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

        if (response.data['0']) {
          jQuery('.xo_skin_' + response.data['0']).remove();

          M.Toast.dismissAll();

          M.toast({
            html: response.data['1'],
          });

        }
        else {

          M.Toast.dismissAll();

          M.toast({
            html: response.data,
          });
        }

      },

    });

  });

  /* selected skin save.*/
  $('.xo_skin_select').click(function(e) {

    e.preventDefault();

    M.Toast.dismissAll();

    var outer_this = $(this);
    var skin_id = $(this).data('skin_id');
    var id = $('#xo_new_id').val();

    var data = {
      action: 'wpop_save_skin_id',
      skin_id: skin_id,
      id: id,
      wpop_nonce: wpoptin.nonce,
    };
    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

        if (response.success) {
          M.toast({
            html: response.data,
          });
          jQuery('.xo_skin_' + skin_id).addClass('wpop_show_selected_label');
          jQuery('.xo_skin_' + skin_id + ' .wpop-skin-actions .xo_skin_select').
              addClass('disabled');
        }
        else {
          M.toast({
            html: response.data,
          });
        }
      },

    });

  });

  /* Edit skin.*/
  $('.xo_edit_skin').click(function(e) {

    e.preventDefault();

    M.Toast.dismissAll();

    jQuery('#xo_all_skins_wrap').slideUp('slow');
    jQuery('.xo_loader_holder').css('display', 'block');
    jQuery('#xo_new_skin_form_wraper').css('display', 'block');
    jQuery('body').addClass('xo_skins_body');
    jQuery('html').addClass('xo_skins_html');
    jQuery('.xoptin_page_xo_skins .xo_wrap').
        addClass('xo_wraper_skins xo_skins_wrap');

    var outer_this = $(this);
    var skin_id = $(this).data('skin_id');

    var data = {
      action: 'wpop_edit_skin',
      skin_id: skin_id,
      wpop_nonce: wpoptin.nonce,
    };

    $.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

        if (response.success) {
          $('#xo_new_skin_form_wraper .material-icons.prefix, #xo_new_skin_form_wraper label').
              addClass('active');
          $('#xo_new_skin_form_wraper #wpop_new_skin_title').
              attr('value', response.data['title']);
          $('#xo_new_skin_form_wraper #wpop_skin_description').
              attr('value', response.data['description']);
          $('#xo_new_skin_form_wraper #wpop_skin_feat_img').
              attr('value', response.data['feat_img']);
          $('#xo_new_skin_form_wraper #xo_skin_for_input').
              val(response.data['skin_for']);
          $('#xo_skin_id').val(response.data['id']);
          $('.xo_loader_holder').fadeOut('slow');

        }
        else {
          M.toast({
            html: response.data,
          });
        }
      },

    });

  });

  //Add class tp slected icon
  jQuery(document).on('click', '.xo_trigger_icons', function($) {
    jQuery('.xo_trigger_icons').removeClass('xo_trigger_slected');
    jQuery(this).addClass('xo_trigger_slected');

  });
  //Add class to selected trigger
  jQuery('.xo_tr_radio').
      filter(':checked').
      parent().
      addClass('xo_trigger_slected');

  jQuery(document).on('click', '#wpop_enable_cupon', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop_new_cupon_wrap').slideDown('slow');
    }
    else {
      jQuery('.wpop_new_cupon_wrap').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_content', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop_new_content_wrap').slideDown('slow');
    }
    else {
      jQuery('.wpop_new_content_wrap').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_button', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop_new_btn_wrap').slideDown('slow');
    }
    else {
      jQuery('.wpop_new_btn_wrap').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_social_current_url', function($) {
    if (!jQuery(this).is(':checked')) {
      jQuery('.wpop_new_social_url_wrap').slideDown('slow');
    }
    else {
      jQuery('.wpop_new_social_url_wrap').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_timer', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop_new_timer_wrap').slideDown('slow');
    }
    else {
      jQuery('.wpop_new_timer_wrap').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_timer_title', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop-timer-title').slideDown('slow');
    }
    else {
      jQuery('.wpop-timer-title').slideUp('slow');
    }
  });

  jQuery(document).on('click', '#wpop_enable_fomo_countdown', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.wpop-fomo-countdown-wrap').slideDown('slow');
      jQuery('.wpop-new-timer-default-wrap').slideUp('slow');

    }
    else {
      jQuery('.wpop-fomo-countdown-wrap').slideUp('slow');
      jQuery('.wpop-new-timer-default-wrap').slideDown('slow');
    }
  });

  if ($('#wpop_enable_timer_title').is(':checked')) {
    $('.wpop-timer-title').slideDown('slow');
  }
  else {
    $('.wpop-timer-title').slideUp('slow');
  }

  if ($('#wpop_enable_fomo_countdown').is(':checked')) {
    $('.wpop-fomo-countdown-wrap').slideDown('slow');
    $('.wpop-new-timer-default-wrap').slideUp('slow');
  }
  else {
    $('.wpop-fomo-countdown-wrap').slideUp('slow');
  }

  if ($('#wpop_enable_timer').is(':checked')) {
    $('.wpop_new_timer_wrap').slideDown('slow');
  }
  else {
    $('.wpop_new_timer_wrap').slideUp('slow');
  }

  if ($('#wpop_enable_cupon').is(':checked')) {
    $('.wpop_new_cupon_wrap').slideDown('slow');
  }
  else {
    $('.wpop_new_cupon_wrap').slideUp('slow');
  }

  if ($('#wpop_enable_content').is(':checked')) {
    $('.wpop_new_content_wrap').slideDown('slow');
  }
  else {
    $('.wpop_new_content_wrap').slideUp('slow');
  }

  if ($('#wpop_enable_button').is(':checked')) {
    $('.wpop_new_btn_wrap').slideDown('slow');
  }
  else {
    $('.wpop_new_btn_wrap').slideUp('slow');
  }

  if (!$('#wpop_enable_social_current_url').is(':checked')) {
    $('.wpop_new_social_url_wrap').slideDown('slow');
  }
  else {
    $('.wpop_new_social_url_wrap').slideUp('slow');
  }

  //Condtions on Triggers selected
  $('.xo_auto_cond').hide();
  $('.xo_auto_p').slideUp('slow');
  $('.xo_scroll_cond').hide();
  $('.xo_exit_cond').hide();
  $('.xo_click_cond').hide();
  $('.xo_scroll_percent').slideUp('slow');
  $('.xo_scroll_s').slideUp('slow');
  $('.xo_pages_cond').hide();
  $('.xo_posts_cond').hide();

  var wpop_selected_trigger = $(
      'input:radio[name=wpop_trigger_method]:checked').val();

  switch (wpop_selected_trigger) {
    case 'auto':
      $('.xo_auto_cond').slideDown();
      break;
    case 'scroll':
      $('.xo_scroll_cond').slideDown();
      break;
    case 'click':
      $('.xo_click_cond').slideDown();
      break;
    default:
      // code block
  }

  if ($('#xo_auto_sec:checked').val() == 'sec') {
    $('.xo_auto_p').slideDown();
  }

  if ($('input:radio[name=wpop_scroll_method]:checked').val() ==
      'scroll_perc') {
    $('.xo_scroll_percent').slideDown();
  }

  if ($('input:radio[name=wpop_scroll_method]:checked').val() ==
      'scroll_slect') {
    $('.xo_scroll_s').slideDown();
  }

  $('.xo_trigger_icons').click(function() {

    var trigger_checked = $(this).find('.xo_tr_radio').val();
    switch (trigger_checked) {
      case 'auto':
        $('.xo_scroll_cond').slideUp();
        $('.xo_click_cond').slideUp();
        $('.xo_auto_cond').slideDown();
        break;
      case 'scroll':
        $('.xo_click_cond').slideUp();
        $('.xo_auto_cond').slideUp();
        $('.xo_scroll_cond').slideDown();
        break;
      case 'click':
        $('.xo_scroll_cond').slideUp();
        $('.xo_auto_cond').slideUp();
        $('.xo_click_cond').slideDown();
        break;

      default:
        $('.xo_scroll_cond').slideUp();
        $('.xo_click_cond').slideUp();
        $('.xo_auto_cond').slideUp();
    }
  });

  $('input:radio[name=wpop_auto_method]').click(function() {
    var auto_method_checked = $(this).val();
    if (auto_method_checked == 'sec') {
      $('.xo_auto_p').slideDown();
    }
    else {
      $('.xo_auto_p').slideUp();
    }

  });

  $('input:radio[name=wpop_scroll_method]').click(function() {
    var scroll_method_checked = $(this).val();
    if (scroll_method_checked == 'scroll_perc') {
      $('.xo_scroll_percent').slideDown();
      $('.xo_scroll_s').slideUp();
    }
    else {
      $('.xo_scroll_percent').slideUp();
      $('.xo_scroll_s').slideDown();
    }
  });

  $('#wpop_pages_method').click(function() {
    if ($(this).is(':checked')) {
      $('.xo_pages_h select, .xo_pages_s select').prop('disabled', false);
      $('select').formSelect();
    }
    else {
      $('.xo_pages_h select, .xo_pages_s select').prop('disabled', true);
      $('select').formSelect();
    }
  });

  if ($('#wpop_pages_method').is(':checked')) {
    $('.xo_pages_h select, .xo_pages_s select').prop('disabled', false);
    $('select').formSelect();
  }
  else {
    $('.xo_pages_h select, .xo_pages_s select').prop('disabled', true);
    $('select').formSelect();

  }

  $('#wpop_posts_method').click(function() {
    if ($(this).is(':checked')) {
      $('.xo_posts_h select, .xo_posts_s select').prop('disabled', false);
      $('select').formSelect();

    }
    else {
      $('.xo_posts_h select, .xo_posts_s select').prop('disabled', true);
      $('select').formSelect();
    }
  });

  if ($('#wpop_posts_method').is(':checked')) {
    $('.xo_posts_h select, .xo_posts_s select').prop('disabled', false);
    $('select').formSelect();
  }
  else {
    $('.xo_posts_h select, .xo_posts_s select').prop('disabled', true);
    $('select').formSelect();
  }

  $('#wpop_tags_method').click(function() {
    if ($(this).is(':checked')) {
      $('.xo_tags_h select, .xo_tags_s select').prop('disabled', false);
      $('select').formSelect();
    }
    else {
      $('.xo_tags_h select, .xo_tags_s select').prop('disabled', true);
      $('select').formSelect();
    }
  });

  if ($('#wpop_tags_method').is(':checked')) {
    $('.xo_tags_h select, .xo_tags_s select').prop('disabled', false);
    $('select').formSelect();
  }
  else {
    $('.xo_tags_h select, .xo_tags_s select').prop('disabled', true);
    $('select').formSelect();
  }

  $('#wpop_cats_method').click(function() {
    if ($(this).is(':checked')) {
      $('.xo_cats_h select, .xo_cats_s select').prop('disabled', false);
      $('select').formSelect();
    }
    else {
      $('.xo_cats_h select, .xo_cats_s select').prop('disabled', true);
      $('select').formSelect();
    }
  });

  if ($('#wpop_cats_method').is(':checked')) {
    $('.xo_cats_h select, .xo_cats_s select').prop('disabled', false);
    $('select').formSelect();
  }
  else {
    $('.xo_cats_h select, .xo_cats_s select').prop('disabled', true);
    $('select').formSelect();
  }

  jQuery(document).on('click', '#wpop_show_all', function($) {
    if (jQuery(this).is(':checked')) {
      jQuery('.xo_check_all').fadeOut('slow');
      jQuery('.xo_display_fields_wrap').fadeOut('slow');
    }
    else {
      jQuery('.xo_check_all').fadeIn('slow');
      jQuery('.xo_display_fields_wrap').fadeIn('slow');
    }
  });

  if ($('#wpop_show_all').is(':checked')) {
    jQuery('.xo_check_all').slideUp('slow');
    jQuery('.xo_display_fields_wrap').slideUp('slow');
  }
  else {
    jQuery('.xo_check_all').slideDown('slow');
    jQuery('.xo_display_fields_wrap').slideDown('slow');
  }

  if ($('#wpop_vistor_method').is(':checked')) {
    $('#wpop_vistor_not').removeAttr('checked');
    $('#wpop_vistor_not').attr('disabled', true);
  }
  else if ($('#wpop_vistor_not').is(':checked')) {
    $('#wpop_vistor_method').removeAttr('checked');
    $('#wpop_vistor_method').attr('disabled', true);
  }

  $('#wpop_vistor_method').click(function() {

    if ($(this).is(':checked')) {
      $('#wpop_vistor_not').removeAttr('checked');
      $('#wpop_vistor_not').attr('disabled', true);

    }
    else {
      $('#wpop_vistor_not').removeAttr('disabled');

    }

  });

  $('#wpop_vistor_not').click(function() {

    if ($(this).is(':checked')) {
      $('#wpop_vistor_method').removeAttr('checked');
      $('#wpop_vistor_method').attr('disabled', true);

    }
    else {
      $('#wpop_vistor_method').removeAttr('disabled');

    }

  });

  if ($('#wpop_device_method').is(':checked')) {
    $('#wpop_device_not').removeAttr('checked');
    $('#wpop_device_not').attr('disabled', true);
  }
  else if ($('#wpop_device_not').is(':checked')) {
    $('#wpop_device_method').removeAttr('checked');
    $('#wpop_device_method').attr('disabled', true);
  }

  $('#wpop_device_method').click(function() {

    if ($(this).is(':checked')) {
      $('#wpop_device_not').removeAttr('checked');
      $('#wpop_device_not').attr('disabled', true);

    }
    else {
      $('#wpop_device_not').removeAttr('disabled');

    }

  });

  $('#wpop_device_not').click(function() {

    if ($(this).is(':checked')) {
      $('#wpop_device_method').removeAttr('checked');
      $('#wpop_device_method').attr('disabled', true);

    }
    else {
      $('#wpop_device_method').removeAttr('disabled');

    }

  });

  $('.xo_toggle_tr h2').click(function() {
    $(this).parent().toggleClass('xo_chng_pos');
    $('.xo_tr_holder').slideToggle('slow');
  });

  $('.xo_toggle_c h2').click(function() {
    $(this).parent().toggleClass('xo_chng_pos');
    $('.xo_c_holder').slideToggle('slow');
  });

  $('.xo_toggle_d h2').click(function() {
    $(this).parent().toggleClass('xo_chng_pos');
    $('.xo_d_holder').slideToggle('slow');
  });

  /**
   * Updates the donut chart's percent number and the CSS positioning of the
   * progress bar. Also allows you to set if it is a donut or pie chart
   * @param  {string}  el      The selector for the donut to update. '#thing'
   * @param  {number}  percent Passing in 22.3 will make the chart show 22%
   * @param  {boolean} donut   True shows donut, false shows pie
   */
  function updateDonutChart(el, percent, donut) {
    percent = Math.round(percent);
    if (percent > 100) {
      percent = 100;
    }
    else if (percent < 0) {
      percent = 0;
    }
    var deg = Math.round(360 * (percent / 100));

    if (percent > 50) {
      $(el + ' .pie').css('clip', 'rect(auto, auto, auto, auto)');
      $(el + ' .right-side').css('transform', 'rotate(180deg)');
    }
    else {
      $(el + ' .pie').css('clip', 'rect(0, 1em, 1em, 0.5em)');
      $(el + ' .right-side').css('transform', 'rotate(0deg)');
    }
    if (donut) {
      $(el + ' .right-side').css('border-width', '0.1em');
      $(el + ' .left-side').css('border-width', '0.1em');
      $(el + ' .shadow').css('border-width', '0.1em');
    }
    else {
      $(el + ' .right-side').css('border-width', '0.5em');
      $(el + ' .left-side').css('border-width', '0.5em');
      $(el + ' .shadow').css('border-width', '0.5em');
    }
    $(el + ' .num').text(percent);
    $(el + ' .left-side').css('transform', 'rotate(' + deg + 'deg)');
  }

  var overview_perc = $('#overview_perc').data('chart_val');
  updateDonutChart('#overview_perc', overview_perc, true);

  if (typeof (wpoptin.stats) != 'undefined' && wpoptin.stats !== null) {
    // Show Chart
    google.charts.load('current', {
      'packages': ['bar'],
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date');
      data.addColumn('number', 'Views');
      data.addColumn('number', 'Conversions');

      for (var k in wpoptin.stats.views) {
        if (wpoptin.stats.views.hasOwnProperty(k)) {
          data.addRows([
            [k, wpoptin.stats.views[k], wpoptin.stats.conversions[k]],
          ]);
        }
      }

      var options = {
        colors: ['#54bce7', '#7FC6A6'],
        animation: {
          startup: true,
          duration: 2000,
          easing: 'in',
        },
        bars: 'vertical', // Required for Material Bar Charts.
      };

      var chart = new google.charts.Bar(
          document.getElementById('xo_chart_holder'));

      chart.draw(data, google.charts.Bar.convertOptions(options));
    }
  }
});