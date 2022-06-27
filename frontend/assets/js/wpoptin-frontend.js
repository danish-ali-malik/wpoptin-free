jQuery(document).ready(function($) {
  var wpop_loader = '<div class="wpop-newsletter-loader"><div></div><div></div><div></div><div></div></div>';
  $('.xo_bar_wrap p:empty').remove();

  jQuery('body').on('click', '#xo_close', function(event) {

    $(this).parents('.xo_bar_wrap').slideUp('slow', function() {
      $(this).remove();
    });

    if (!($('.xo_bar_wrap').length > 1)) {

      jQuery('body').animate({
        marginTop: '0px',
      }, 'slow');
    }

    var data = {
      action: 'wpop_hide',
      id: $(this).parents('.xo_bar_wrap').data('id'),
    };
    jQuery.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

      },

    });

  });

  jQuery('body').on('click', '.xo_bar_wrap.fancybox-content .xo_offer_cta', function(event) {
    var data = {
      action: 'wpop_hide',
      id: jQuery(this).data('id'),
    };
    jQuery.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {
        var btn_url = jQuery(
            '.xo_bar_wrap#' + id + ' .xo_offer_cta').
            attr('href');

        if (typeof btn_url !== 'undefined') {

          window.location.href = btn_url;

        }

      },
    });
  });
  jQuery(document).
      on('click', 'div[data-class=\'wpop_redirect_home\']', function(event) {
        window.open(
            'https://wpxoptin.com/?utm_campaign=powered-by-link&utm_medium=link&utm_source=plugin',
            '_blank');
      });

  /* Change logos on bar branding.*/
  jQuery(document).on({
    mouseenter: function() {
      var inverted_img = jQuery(this).data('wpop-inverted-img');

      jQuery(this).animate({opacity: 0.3}, 1000);

      jQuery(this).children('img').attr('src', inverted_img);

    },
    mouseleave: function() {
      var real_img = jQuery(this).data('wpop-real-img');

      jQuery(this).animate({opacity: 1}, 1000);

      jQuery(this).children('img').attr('src', real_img);

    },
  }, '[data-id=\'wpop_bar_branding\']');

  /* Adding conversion on CTA.*/
  jQuery('body').
      on('click', '.xo_bar_wrap .xo_offer_btn .xo_offer_cta', function(event) {
        var id = $(this).parents('.xo_bar_wrap').data('id');
        var xo_type = $(this).parents('.xo_bar_wrap').data('type');
        var xo_module = $(this).parents('.xo_bar_wrap').data('module');

        var data = {
          action: 'wpop_conversion',
          id: id,
          xo_type: xo_type,
          module: xo_module,
        };
        jQuery.ajax({
          url: wpoptin.ajax_url,
          type: 'post',
          data: data,
          success: function(response) {
          },

        });

      });
  /* Submit Accounts values in database.*/
  jQuery(document).on('click', '.xo_newsletter_sub', function(event) {

    jQuery('.xo_msg').remove();
    event.preventDefault();

    var wpop_email = $(this).siblings('.xo_newsletter_email').val();

    if (!wpop_email) {
      jQuery('.xo_msg').hide();
      jQuery('.xo_newsletter_form').
          append('<p class="xo_error xo_msg">' + wpoptin.email_empty + '</p>');
      jQuery('.xo_msg').slideDown('slow', 'linear');
      jQuery('.xo_msg').delay('2000').slideUp('slow');
      return;
    }

    var data = {
      action: 'wpop_newsletter',
      email: $(this).parent('.xo_newsletter_form').serialize(),
    };

    var before_this = $(this).parent('.xo_newsletter_form');
    var old_btn = jQuery(this).val();

    jQuery(wpop_loader).insertAfter(this);

    jQuery.ajax({
      url: wpoptin.ajax_url,
      type: 'post',
      data: data,
      success: function(response) {

        jQuery(before_this).
            find('.wpop-newsletter-loader').
            fadeOut(100, function() {
              jQuery(before_this).
                  find('.wpop-newsletter-loader').
                  remove();
            });

        if (response.success) {

          jQuery('.xo_newsletter_form').
              append('<p class="xo_sucess xo_msg">' + response.data + '</p>');
          jQuery('.xo_msg').hide();
          jQuery('.xo_msg').slideDown('slow', 'linear');
          jQuery('.xo_msg').delay('9000').slideUp('slow');

          /* Adding conversion.*/
          var id = jQuery(before_this).parents('.xo_bar_wrap').data('id');
          var xo_type = jQuery(before_this).
              parents('.xo_bar_wrap').
              data('type');
          var xo_module = jQuery(before_this).
              parents('.xo_bar_wrap').
              data('module');

          var data = {
            action: 'wpop_conversion',
            id: id,
            xo_type: xo_type,
            module: xo_module,
          };
          jQuery.ajax({
            url: wpoptin.ajax_url,
            type: 'post',
            data: data,
            success: function(response) {

            },

          });

        }
        else {
          jQuery('.xo_newsletter_form').
              append('<p class="xo_error xo_msg">' + response.data + '</p>');
          jQuery('.xo_msg').hide();
          jQuery('.xo_msg').slideDown('slow', 'linear');
          jQuery('.xo_msg').delay('9000').slideUp('slow');
        }
      },

    });
  });

});
(function(factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD (Register as an anonymous module)
    define(['jquery'], factory);
  }
  else if (typeof exports === 'object') {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  }
  else {
    // Browser globals
    factory(jQuery);
  }
}(function($) {

  var pluses = /\+/g;

  function encode(s) {
    return config.raw ? s : encodeURIComponent(s);
  }

  function decode(s) {
    return config.raw ? s : decodeURIComponent(s);
  }

  function stringifyCookieValue(value) {
    return encode(config.json ? JSON.stringify(value) : String(value));
  }

  function parseCookieValue(s) {
    if (s.indexOf('"') === 0) {
      // This is a quoted cookie as according to RFC2068, unescape...
      s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
    }

    try {
      // Replace server-side written pluses with spaces.
      // If we can't decode the cookie, ignore it, it's unusable.
      // If we can't parse the cookie, ignore it, it's unusable.
      s = decodeURIComponent(s.replace(pluses, ' '));
      return config.json ? JSON.parse(s) : s;
    }
    catch (e) {}
  }

  function read(s, converter) {
    var value = config.raw ? s : parseCookieValue(s);
    return $.isFunction(converter) ? converter(value) : value;
  }

  var config = $.cookie = function(key, value, options) {

    // Write

    if (arguments.length > 1 && !$.isFunction(value)) {
      options = $.extend({}, config.defaults, options);

      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
      }

      return (document.cookie = [
        encode(key),
        '=',
        stringifyCookieValue(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use
                                                                             // expires
                                                                             // attribute,
                                                                             // max-age
                                                                             // is
                                                                             // not
                                                                             // supported
                                                                             // by
                                                                             // IE
        options.path ? '; path=' + options.path : '',
        options.domain ? '; domain=' + options.domain : '',
        options.secure ? '; secure' : '',
      ].join(''));
    }

    // Read

    var result = key ? undefined : {},
        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        cookies = document.cookie ? document.cookie.split('; ') : [],
        i = 0,
        l = cookies.length;

    for (; i < l; i++) {
      var parts = cookies[i].split('='),
          name = decode(parts.shift()),
          cookie = parts.join('=');

      if (key === name) {
        // If second argument (value) is a function it's a converter...
        result = read(cookie, value);
        break;
      }

      // Prevent storing a cookie that we couldn't decode.
      if (!key && (cookie = read(cookie)) !== undefined) {
        result[name] = cookie;
      }
    }

    return result;
  };

  config.defaults = {};

  $.removeCookie = function(key, options) {
    // Must not alter options, thus extending a fresh object...
    $.cookie(key, '', $.extend({}, options, {expires: -1}));
    return !$.cookie(key);
  };

}));
(function($) {
  var timer;

  function trackLeave(ev) {
    if (ev.pageY > 0) {
      return;
    }

    if (timer) {
      clearTimeout(timer);
    }

    if ($.exitIntent.settings.sensitivity <= 0) {
      $.event.trigger('exitintent');
      return;
    }

    timer = setTimeout(
        function() {
          timer = null;
          $.event.trigger('exitintent');
        }, $.exitIntent.settings.sensitivity);
  }

  function trackEnter() {
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
  }

  $.exitIntent = function(enable, options) {
    $.exitIntent.settings = $.extend($.exitIntent.settings, options);

    if (enable == 'enable') {
      $(window).mouseleave(trackLeave);
      $(window).mouseenter(trackEnter);
    }
    else if (enable == 'disable') {
      trackEnter(); // Turn off any outstanding timer
      $(window).unbind('mouseleave', trackLeave);
      $(window).unbind('mouseenter', trackEnter);
    }
    else {
      throw 'Invalid parameter to jQuery.exitIntent -- should be \'enable\'/\'disable\'';
    }
  };

  $.exitIntent.settings = {
    'sensitivity': 300,
  };

})(jQuery);

jQuery(window).load(function() {
  /* Lets play with triggers.*/

  jQuery('.xo_bar_wrap').each(function(index, element) {

    var id = jQuery(this).data('id');
    var check_div = '.xo_bar_wrap#' + id + ' .xo_timer_wrap';

    if (jQuery(check_div).length == 0) {
      jQuery('.xo_bar_wrap.xo_is_bar#' + id).css('padding', '15px 15px');
    }

    jQuery('#xo_wrapper').hide();
    var content = jQuery('#xo_wrapper').html();
    jQuery('#xo_wrapper').remove();
    var type = jQuery(this).data('type');

    if ('popup' === type || 'slide_in' === type) {
      jQuery(content).appendTo('body');
      jQuery(this).hide();
    }
    else {
      jQuery(content).prependTo('body');
      jQuery(this).hide();
    }

    if (jQuery('#wpadminbar').length > 0) {
      var adminBar_height = jQuery('#wpadminbar').outerHeight(true);
    }

    /* Shows when seconds selected */
    if (jQuery(this).data('auto_sec')) {
      var sec = jQuery(this).data('auto_sec');
      sec = sec + '000';

      switch (type) {

        case 'popup':
        case 'wellcome_matt':

          var wpop_base_class = '';

          if (type == 'wellcome_matt') {
            wpop_powered_by_logo = wpoptin.logo;
            wpop_base_class = 'wpop-wellcome-matt-base-container';

          }
          else {
            wpop_powered_by_logo = wpoptin.inverted_logo;
            wpop_base_class = 'wpop-popup-base-container';
          }

          if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {

            jQuery('.xo_not_visible.xo_bar_wrap#' + id).
                delay(sec).
                slideDown('slow');

            var xo_type = jQuery(this).data('type');
            var module = jQuery(this).data('module');

            var data = {
              action: 'wpop_view',
              id: id,
              xo_type: xo_type,
              module: module,
            };

            jQuery.ajax({
              url: wpoptin.ajax_url,
              type: 'post',
              data: data,
              success: function(response) {
              },

            });

            setTimeout(function() {
              jQuery.fancybox.open({
                src: jQuery('.xo_bar_wrap#' + id),
                type: 'inline',
                animationEffect: 'zoom-in-out',
                modal: true,
                animationDuration: 50000,
                opts: {
                  afterShow: function(instance, slide) {
                    jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');
                    if (wpoptin.free_plan) {
                      var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                          wpoptin.logo + '"><img title="' + wpoptin.powered_by +
                          '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                          wpop_powered_by_logo + '" /></div>';
                      jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                    }
                  },
                  afterClose: function(instance, slide) {
                    var data = {
                      action: 'wpop_hide',
                      id: id,
                    };
                    jQuery.ajax({
                      url: wpoptin.ajax_url,
                      type: 'post',
                      data: data,
                      success: function(response) {
                        var btn_url = jQuery(
                            '.xo_bar_wrap#' + id + ' .xo_offer_cta').
                            attr('href');

                        if (typeof btn_url !== 'undefined') {

                          window.location.href = btn_url;

                        }

                      },
                    });
                  },
                },
              }, {
                baseClass: wpop_base_class,
              });
            }, sec);

          }

          break;

        default:

          jQuery('.xo_not_visible.xo_bar_wrap#' + id).
              delay(sec).
              slideDown('slow', 'linear', function() {

                jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');

                if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {

                  jQuery('.xo_bar_wrap#' + id).animate({
                    marginTop: adminBar_height,
                  }, 500);
                }

                var xo_type = jQuery(this).data('type');
                var module = jQuery(this).data('module');

                var data = {
                  action: 'wpop_view',
                  id: id,
                  xo_type: xo_type,
                  module: module,
                };

                jQuery.ajax({
                  url: wpoptin.ajax_url,
                  type: 'post',
                  data: data,
                  success: function(response) {
                  },

                });

                if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {

                  var wrapper_height = jQuery('.xo_' + id).outerHeight(true);
                  jQuery('body').animate({
                    marginTop: wrapper_height,
                  }, 500);
                }

              });
      }

    }

    /* Shows when exit intent detected. */
    if (jQuery(this).data('trigger_method') == 'exit') {

      jQuery.exitIntent('enable');
      jQuery(document).bind('exitintent', function() {

        switch (type) {

          case 'popup':
          case 'wellcome_matt':

            var wpop_base_class = '';

            if (type == 'wellcome_matt') {
              wpop_powered_by_logo = wpoptin.logo;
              wpop_base_class = 'wpop-wellcome-matt-base-container';

            }
            else {
              wpop_powered_by_logo = wpoptin.inverted_logo;
              wpop_base_class = 'wpop-popup-base-container';
            }

            if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {

              jQuery('.xo_not_visible.xo_bar_wrap#' + id).slideDown('slow');

              var xo_type = jQuery(this).data('type');
              var module = jQuery(this).data('module');
              var data = {
                action: 'wpop_view',
                id: id,
                xo_type: xo_type,
                module: module,
              };

              jQuery.ajax({
                url: wpoptin.ajax_url,
                type: 'post',
                data: data,
                success: function(response) {
                },

              });
              if( jQuery('.xo_bar_wrap#' + id).length ){
                jQuery.fancybox.open({
                  src: jQuery('.xo_bar_wrap#' + id),
                  type: 'inline',
                  opts: {
                    afterShow: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');
                      if (wpoptin.free_plan) {
                        var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                            wpoptin.logo + '"><img title="' + wpoptin.powered_by +
                            '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                            wpop_powered_by_logo + '" /></div>';
                        jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                      }
                    },
                    afterClose: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).remove();
                      var data = {
                        action: 'wpop_hide',
                        id: id,
                      };
                      jQuery.ajax({
                        url: wpoptin.ajax_url,
                        type: 'post',
                        data: data,
                        success: function(response) {

                          var btn_url = jQuery(
                              '.xo_bar_wrap#' + id + ' .xo_offer_cta').
                              attr('href');

                          if (typeof btn_url !== 'undefined') {
                            window.location.href = btn_url;
                          }
                        },
                      });
                    },
                  },
                }, {
                  baseClass: wpop_base_class,
                });

             }
            }

            break;

          default:

            jQuery('.xo_not_visible.xo_bar_wrap#' + id).
                slideDown('slow', 'linear', function() {

                  jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');
                  if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {

                    jQuery('.xo_bar_wrap#' + id).animate({
                      marginTop: adminBar_height,
                    }, 500);

                  }

                  var xo_type = jQuery(this).data('type');
                  var module = jQuery(this).data('module');
                  var data = {
                    action: 'wpop_view',
                    id: id,
                    xo_type: xo_type,
                    module: module,
                  };

                  jQuery.ajax({
                    url: wpoptin.ajax_url,
                    type: 'post',
                    data: data,
                    success: function(response) {
                    },

                  });

                  var wrapper_height = jQuery('.xo_' + id).outerHeight(true);

                  if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                    jQuery('body').animate({
                      marginTop: wrapper_height,
                    }, 500);
                  }

                });
        }
      });

    }

    /* Shows when click class selected */
    if (jQuery(this).data('click_class')) {
      var click_class = jQuery(this).data('click_class');
      var outerthis = jQuery(this);
      jQuery(click_class).click(function() {

        switch (type) {

          case 'popup':
          case 'wellcome_matt':

            var wpop_base_class = '';

            if (type == 'wellcome_matt') {
              wpop_powered_by_logo = wpoptin.logo;
              wpop_base_class = 'wpop-wellcome-matt-base-container';

            }
            else {
              wpop_powered_by_logo = wpoptin.inverted_logo;
              wpop_base_class = 'wpop-popup-base-container';
            }
            if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {
              jQuery('.xo_not_visible.xo_bar_wrap#' + id).slideDown('slow');

              var xo_type = jQuery(this).data('type');
              var module = jQuery(this).data('module');
              var data = {
                action: 'wpop_view',
                id: id,
                xo_type: xo_type,
                module: module,
              };

              jQuery.ajax({
                url: wpoptin.ajax_url,
                type: 'post',
                data: data,
                success: function(response) {
                },

              });

              jQuery.fancybox.open({
                src: jQuery('.xo_bar_wrap#' + id),
                type: 'inline',
                opts: {
                  afterShow: function(instance, slide) {
                    jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');
                    if (wpoptin.free_plan) {
                      var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                          wpoptin.logo + '"><img title="' + wpoptin.powered_by +
                          '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                          wpop_powered_by_logo + '" /></div>';
                      jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                    }
                  },
                  afterClose: function(instance, slide) {
                    jQuery('.xo_bar_wrap#' + id).remove();
                    var data = {
                      action: 'wpop_hide',
                      id: id,
                    };
                    jQuery.ajax({
                      url: wpoptin.ajax_url,
                      type: 'post',
                      data: data,
                      success: function(response) {

                        var btn_url = jQuery(
                            '.xo_bar_wrap#' + id + ' .xo_offer_cta').
                            attr('href');

                        if (typeof btn_url !== 'undefined') {

                          window.location.href = btn_url;

                        }
                      },
                    });
                  },
                },
              }, {
                baseClass: wpop_base_class,
              });
            }

            break;

          default:

            jQuery('.xo_not_visible.xo_bar_wrap#' + id).
                slideDown('slow', 'linear', function() {
                  jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');

                  if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                    jQuery('.xo_bar_wrap#' + id).animate({
                      marginTop: adminBar_height,
                    }, 500);
                  }

                  var xo_type = jQuery(this).data('type');
                  var module = jQuery(this).data('module');
                  var data = {
                    action: 'wpop_view',
                    id: id,
                    xo_type: xo_type,
                    module: module,
                  };

                  jQuery.ajax({
                    url: wpoptin.ajax_url,
                    type: 'post',
                    data: data,
                    success: function(response) {
                    },

                  });

                  var wrapper_height = jQuery('.xo_' + id).outerHeight(true);

                  if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                    jQuery('body').animate({
                      marginTop: wrapper_height,
                    }, 500);
                  }

                });
        }
      });
    }

    /* Shows when after scroll selected */
    if (jQuery(this).data('scroll_selector')) {
      var scroll_class = jQuery(this).data('scroll_selector');

      var element = jQuery(scroll_class);
      var topOfElement = element.offset().top;
      var bottomOfElement = element.offset().top + element.outerHeight(true);
      jQuery(window).bind('scroll', function() {
        var scrollTopPosition = jQuery(window).scrollTop() +
            jQuery(window).height();
        var windowScrollTop = jQuery(window).scrollTop();

        if (scrollTopPosition > topOfElement) {

          if (!jQuery('.xo_not_visible.xo_bar_wrap#' + id).is(':visible')) {

            var xo_type = jQuery('.xo_bar_wrap#' + id).data('type');
            var module = jQuery('.xo_bar_wrap#' + id).data('module');
            var data = {
              action: 'wpop_view',
              id: id,
              xo_type: xo_type,
              module: module,
            };

            jQuery.ajax({
              url: wpoptin.ajax_url,
              type: 'post',
              data: data,
              success: function(response) {
              },

            });
          }

          switch (type) {

            case 'popup':
            case 'wellcome_matt':

              var wpop_base_class = '';

              if (type == 'wellcome_matt') {
                wpop_powered_by_logo = wpoptin.logo;
                wpop_base_class = 'wpop-wellcome-matt-base-container';

              }
              else {
                wpop_powered_by_logo = wpoptin.inverted_logo;
                wpop_base_class = 'wpop-popup-base-container';
              }

              if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {

                jQuery('.xo_not_visible.xo_bar_wrap#' + id).slideDown('slow');

                jQuery.fancybox.open({
                  src: jQuery('.xo_bar_wrap#' + id),
                  type: 'inline',
                  opts: {
                    afterShow: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).
                          removeClass('xo_not_visible');
                      if (wpoptin.free_plan) {
                        var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                            wpoptin.logo + '"><img title="' +
                            wpoptin.powered_by +
                            '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                            wpop_powered_by_logo + '" /></div>';
                        jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                      }
                    },
                    afterClose: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).remove();
                      var data = {
                        action: 'wpop_hide',
                        id: id,
                      };
                      jQuery.ajax({
                        url: wpoptin.ajax_url,
                        type: 'post',
                        data: data,
                        success: function(response) {

                          var btn_url = jQuery(
                              '.xo_bar_wrap#' + id + ' .xo_offer_cta').
                              attr('href');

                          if (typeof btn_url !== 'undefined') {

                            window.location.href = btn_url;

                          }
                        },
                      });
                    },
                  },
                }, {
                  baseClass: wpop_base_class,
                });
              }

              break;

            default:
              jQuery('.xo_not_visible.xo_bar_wrap#' + id).
                  slideDown('slow', 'linear', function() {
                    jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');

                    if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {

                      jQuery('.xo_bar_wrap#' + id).animate({
                        marginTop: adminBar_height,
                      }, 500);
                    }

                    var wrapper_height = jQuery('.xo_' + id).outerHeight(true);

                    if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                      jQuery('body').animate({
                        marginTop: wrapper_height,
                      }, 500);
                    }

                  });
          }
        }
      });
    }

    /* Shows when click percent class */
    if (jQuery(this).data('scroll_percent')) {
      var percentscroll = jQuery(this).data('scroll_percent');

      jQuery(window).scroll(function($) {
        var scrollTop = jQuery(window).scrollTop();
        var docHeight = jQuery(document).height();
        var winHeight = jQuery(window).height();
        var scrollPercent = (scrollTop) / (docHeight - winHeight);
        var scrollPercentRounded = Math.round(scrollPercent * 100);
        if (scrollPercentRounded > percentscroll) {
          if (!jQuery('.xo_not_visible.xo_bar_wrap#' + id).is(':visible')) {

            var xo_type = jQuery('.xo_bar_wrap#' + id).data('type');
            var module = jQuery('.xo_bar_wrap#' + id).data('module');
            var data = {
              action: 'wpop_view',
              id: id,
              xo_type: xo_type,
              module: module,
            };

            jQuery.ajax({
              url: wpoptin.ajax_url,
              type: 'post',
              data: data,
              success: function(response) {

              },

            });
          }

          switch (type) {

            case 'popup':
            case 'wellcome_matt':

              var wpop_base_class = '';

              if (type == 'wellcome_matt') {
                wpop_powered_by_logo = wpoptin.logo;
                wpop_base_class = 'wpop-wellcome-matt-base-container';

              }
              else {
                wpop_powered_by_logo = wpoptin.inverted_logo;
                wpop_base_class = 'wpop-popup-base-container';
              }

              if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {

                jQuery('.xo_not_visible.xo_bar_wrap#' + id).slideDown('slow');

                jQuery.fancybox.open({
                  src: jQuery('.xo_bar_wrap#' + id),
                  type: 'inline',
                  opts: {
                    afterShow: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).
                          removeClass('xo_not_visible');
                      if (wpoptin.free_plan) {
                        var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                            wpoptin.logo + '"><img title="' +
                            wpoptin.powered_by +
                            '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                            wpop_powered_by_logo + '" /></div>';
                        jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                      }
                    },
                    afterClose: function(instance, slide) {
                      jQuery('.xo_bar_wrap#' + id).remove();
                      var data = {
                        action: 'wpop_hide',
                        id: id,
                      };
                      jQuery.ajax({
                        url: wpoptin.ajax_url,
                        type: 'post',
                        data: data,
                        success: function(response) {

                          var btn_url = jQuery(
                              '.xo_bar_wrap#' + id + ' .xo_offer_cta').
                              attr('href');

                          if (typeof btn_url !== 'undefined') {

                            window.location.href = btn_url;

                          }
                        },
                      });
                    },
                  },
                }, {
                  baseClass: wpop_base_class,
                });

              }

              break;

            default:

              jQuery('.xo_not_visible.xo_bar_wrap#' + id).
                  slideDown('slow', 'linear', function() {
                    jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');

                    if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                      jQuery('.xo_bar_wrap#' + id).animate({
                        marginTop: adminBar_height,
                      }, 500);

                      var wrapper_height = jQuery('.xo_' + id).
                          outerHeight(true);
                      jQuery('body').animate({
                        marginTop: wrapper_height,
                      }, 500);
                    }

                  });
          }
        }
        else {
          // jQuery(".xo_bar_wrap#"+id).slideUp("slow");
        }
      });
    }

    /* Shows when immediately selected */
    if (jQuery(this).data('auto_method') == 'im') {

      switch (type) {

        case 'popup':
        case 'wellcome_matt':

          var wpop_base_class = '';

          if (type == 'wellcome_matt') {
            wpop_powered_by_logo = wpoptin.logo;
            wpop_base_class = 'wpop-wellcome-matt-base-container';

          }
          else {
            wpop_powered_by_logo = wpoptin.inverted_logo;
            wpop_base_class = 'wpop-popup-base-container';
          }

          if (!jQuery('.xo_bar_wrap#' + id).is(':visible')) {

            jQuery('.xo_not_visible.xo_bar_wrap#' + id).slideDown('slow');
            var xo_type = jQuery(this).data('type');
            var module = jQuery(this).data('module');

            jQuery.fancybox.open({
              src: jQuery('.xo_bar_wrap#' + id),
              type: 'inline',
              opts: {
                afterShow: function(instance, slide) {
                  jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');
                  if (wpoptin.free_plan) {
                    var powered_by_html = '<div style="opacity:.8; display:block!important;z-index:999999!important;position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -40px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0); wpop-popup-powered-by" data-id="wpop-popup-powered-by" data-class="wpop_redirect_home" data-logo="' +
                        wpoptin.logo + '"><img title="' + wpoptin.powered_by +
                        '" data-id="wpop-popup-powered-by-img" style="width: 30px;float: left; display:block!important;z-index:999999!important;" src="' +
                        wpop_powered_by_logo + '" /></div>';
                    jQuery('.xo_bar_wrap#' + id).append(powered_by_html);
                  }
                },
                afterClose: function(instance, slide) {
                  var data = {
                    action: 'wpop_hide',
                    id: id,
                  };
                  jQuery.ajax({
                    url: wpoptin.ajax_url,
                    type: 'post',
                    data: data,
                    success: function(response) {

                      var btn_url = jQuery(
                          '.xo_bar_wrap#' + id + ' .xo_offer_cta').attr('href');

                      if (typeof btn_url !== 'undefined') {

                        window.location.href = btn_url;

                      }
                    },
                  });
                },
              },
            }, {
              baseClass: wpop_base_class,
            });

            var data = {
              action: 'wpop_view',
              id: id,
              xo_type: xo_type,
              module: module,
            };

            jQuery.ajax({
              url: wpoptin.ajax_url,
              type: 'post',
              data: data,
              success: function(response) {

              },

            });

          }
          break;

        default:
          jQuery('.xo_not_visible.xo_bar_wrap#' + id).
              slideDown('slow', 'linear', function() {

                jQuery('.xo_bar_wrap#' + id).removeClass('xo_not_visible');

                if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {

                  jQuery('.xo_bar_wrap#' + id).animate({
                    marginTop: adminBar_height,
                  }, 500);
                }

                var xo_type = jQuery(this).data('type');
                var module = jQuery(this).data('module');

                var data = {
                  action: 'wpop_view',
                  id: id,
                  xo_type: xo_type,
                  module: module,
                };

                jQuery.ajax({
                  url: wpoptin.ajax_url,
                  type: 'post',
                  data: data,
                  success: function(response) {
                  },

                });

                var wrapper_height = jQuery('.xo_' + id).outerHeight(true);

                if (type != 'slide_in' && !jQuery(".xo_bar_wrap#" + id).hasClass('wpop_bar_bottom')) {
                  jQuery('body').animate({
                    marginTop: wrapper_height,
                  }, 500);
                }

              });
      }

    }

    if (jQuery('.xo_bar_wrap#' + id).data('module') == 'offer-bar' ||
        jQuery('.xo_bar_wrap#' + id).data('module') == 'custom' ||
        jQuery('.xo_bar_wrap#' + id).data('module') == 'optin') {
      var timer_date = jQuery('.xo_bar_wrap#' + id).data('timer_date');

      var timer_time = jQuery('.xo_bar_wrap#' + id).data('timer_time');

      if (timer_time) {
        timer_date = timer_date + ' ' + timer_time;
      }
      

      var $clock = jQuery('.xo_bar_wrap#' + id +' .wopop_timer_clock').
          on('update.countdown', function(event) {
            var format = '%H:%M:%S';
            if (event.offset.totalDays > 0) {
              format = '<span class="xo_time_holder"><span class="xo_time">%d</span><span class="xo_place">' +
                  wpoptin.day +
                  '%!d</span> </span><span class="xo_time_holder"><span class="xo_time">%H</span> <span class="xo_place">' +
                  wpoptin.hours +
                  '</span> </span><span class="xo_time_holder"><span class="xo_time">%M</span> <span class="xo_place">' +
                  wpoptin.minutes +
                  '</span> </span><span class="xo_time_holder"><span class="xo_time">%S</span><span class="xo_place">' +
                  wpoptin.seconds + '</span> </span>';
            }
            if (event.offset.weeks > 0) {
              format = '<span class="xo_time_holder"><span class="xo_time">%w</span><span class="xo_place">' +
                  wpoptin.week +
                  '%!w</span> </span><span class="xo_time_holder"><span class="xo_time">%d</span><span class="xo_place">' +
                  wpoptin.day +
                  '%!d</span> </span><span class="xo_time_holder"><span class="xo_time">%H</span> <span class="xo_place">' +
                  wpoptin.hours +
                  '</span> </span><span class="xo_time_holder"><span class="xo_time">%M</span> <span class="xo_place">' +
                  wpoptin.minutes +
                  '</span> </span><span class="xo_time_holder"><span class="xo_time">%S</span><span class="xo_place">' +
                  wpoptin.seconds + '</span> </span>';
            }
            if (event.offset.totalDays < 1) {
              format = '<span class="xo_time_holder"><span class="xo_time">%H</span> <span class="xo_place">' +
                  wpoptin.hours +
                  '%!H</span> </span><span class="xo_time_holder"><span class="xo_time">%M</span> <span class="xo_place">' +
                  wpoptin.minutes +
                  '</span> </span><span class="xo_time_holder"><span class="xo_time">%S</span><span class="xo_place">' +
                  wpoptin.seconds + '</span> </span>';
            }
            jQuery(this).html(event.strftime(format));
          }).
          on('finish.countdown', function(event) {
            
            jQuery(this).
                parent().
                addClass('disabled').
                html('<span class="wpop_timer_expired">' +
                    wpoptin.offer_expired_text + '</span>');
          });
      $clock.countdown(timer_date);
    }

  });/* Each ends here */

  /* Triggers ends here.*/
});



