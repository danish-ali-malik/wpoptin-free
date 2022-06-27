/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 */
var xo_skin_id = wpop_customizer_object.wpop_skin_id;
var wpop_id = wpop_customizer_object.wpop_id;

(function($) {

  //======================================================================
  // Content section
  //======================================================================

  wp.customize('wpop_data_' + xo_skin_id + '[design][sticky-bar]',
      function(value) {

        value.bind(function(newval) {

         if( newval ){
           $('.xo_bar_wrap.xo_' + wpop_id + '').css('position', 'fixed');
         }else{
           $('.xo_bar_wrap.xo_' + wpop_id + '').css('position', 'absolute');
         }

        });
      });

  

  //Update Bg color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[content][timer-method]',
      function(value) {

        value.bind(function(newval) {

          if (newval == false) {
            newval = 'none';
          }
          else {
            newval = 'block';
          }
          $('.xo_timer_wrap').css('display', newval);
        });
      });

  //Update Bg color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[content][cupon-code]',
      function(value) {

        value.bind(function(newval) {

          if (newval) {
            newval = 'block';

          }
          else {
            newval = 'none';
          }
          $('.xo_cupon_wrap').css('display', newval);
        });
      });
  //======================================================================
  // Colours section
  //======================================================================

  wp.customize('wpop_data_' + xo_skin_id + '[design][background-color]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').css('background-color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][color]', function(value) {
    value.bind(function(newval) {
      $('.xo_bar_wrap.xo_' + wpop_id + ' p, .xo_bar_wrap.xo_' + wpop_id + '').
          css('color', newval);
    });
  });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][link-color]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' a').css('color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][counterbg-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_timer_wrap').css('background-color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][counter-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_timer_wrap, .xo_timer_wrap p').css('color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][cuponbg-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_cupon_wrap').css('background-color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][cupon-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_cupon_wrap').css('color', newval);
        });
      });
  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][border-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' ').css('border-color', newval);
        });
      });
  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][cuponborder-color]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' .xo_cupon_wrap').
              css('border-color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][btnbgcolor]',
      function(value) {

        value.bind(function(newval) {

          $('.xo_newsletter_form .xo_newsletter_sub, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_offer_cta').css('background-color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][btncolor]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_newsletter_form .xo_newsletter_sub, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_offer_cta').css('color', newval);
        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][btnbgcolor-hover]',
      function(value) {

        value.bind(function(newval) {
          $('<style>.xo_newsletter_form .xo_newsletter_sub:hover, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_offer_btn .xo_offer_cta:hover{background-color:' +
              newval + '!important}</style>').appendTo('head');

        });
      });

  //Update color in real time...
  wp.customize('wpop_data_' + xo_skin_id + '[design][btncolor-hover]',
      function(value) {

        value.bind(function(newval) {
          $('<style>.xo_newsletter_form .xo_newsletter_sub:hover, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_offer_btn .xo_offer_cta:hover{color:' + newval +
              ' !important}</style>').appendTo('head');

        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[design][emailfield-bgcolor]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_email').
              css('background-color', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[design][emailfield-color]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_email').
              css('color', newval);
        });
      });
  wp.customize('wpop_data_' + xo_skin_id + '[design][emailfield-bordercolor]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_email').
              css('border-color', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[design][btnbordercolor]',
      function(value) {
        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_sub, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_front_c .xo_offer_btn .xo_offer_cta').
              css('border-color', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[design][btnbordercolor-hover]',
      function(value) {
        value.bind(function(newval) {
          $('<style>.xo_newsletter_form .xo_newsletter_sub:hover, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_offer_btn .xo_offer_cta:hover{border-color:' +
              newval + ' !important}</style>').appendTo('head');

        });
      });

  //======================================================================
  // Typography
  //======================================================================

  //Update color in real time...
  // wp.customize( 'wpop_data_'+xo_skin_id+'[typography][wrap-size]', function(
  // value ) {

  // 	value.bind( function( newval ) {
  // 		$('.xo_bar_wrap.xo_'+wpop_id+', .xo_offer_c p  ').css('font-size',
  // newval + 'px'); } ); } );

  // //Update color in real time...
  // wp.customize( 'wpop_data_'+xo_skin_id+'[typography][counter-size]',
  // function( value ) {

  // 	value.bind( function( newval ) {
  // 		$('.xo_bar_wrap.xo_'+wpop_id+' .xo_timer_wrap p,
  // .xo_bar_wrap.xo_'+wpop_id+' .xo_timer_wrap span').css('font-size', newval
  // + 'px'); } ); } );

  // //Update color in real time...
  // wp.customize( 'wpop_data_'+xo_skin_id+'[typography][cupon-size]',
  // function( value ) {

  // 	value.bind( function( newval ) {
  // 		$('.xo_bar_wrap.xo_'+wpop_id+' .xo_cupon_wrap').css('font-size',
  // newval + 'px'); } ); } );

  //======================================================================
  // Borders
  //======================================================================

  wp.customize('wpop_data_' + xo_skin_id + '[border][border-wrap-top]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').
              css('border-top-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][border-wrap-bottom]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').
              css('border-bottom-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][border-wrap-left]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').
              css('border-left-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][border-wrap-right]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').
              css('border-right-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][wrapborder-style]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').css('border-style', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][cuponborder-style]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' .xo_cupon_wrap').
              css('border-style', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][cuponborder-size]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' .xo_cupon_wrap').
              css('border-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[typography][wrap-font]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + '').css('font-family', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[typography][counter-font]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' .xo_timer_wrap').
              css('font-family', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[typography][cupon-font]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_bar_wrap.xo_' + wpop_id + ' .xo_cupon_wrap').
              css('font-family', newval);
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][emailfield-size]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_email').
              css('border-width', newval + 'px');
        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[border][btn-size]',
      function(value) {

        value.bind(function(newval) {
          $('.xo_optin .xo_newsletter_Wrap .xo_newsletter_sub, .xo_bar_wrap.xo_' +
              wpop_id + ' .xo_front_c .xo_offer_btn .xo_offer_cta').
              css('border-width', newval + 'px');
        });
      });

  //======================================================================
  //  Position
  //======================================================================

  wp.customize('wpop_data_' + xo_skin_id + '[design][position]',
      function(value) {

        value.bind(function(newval) {
          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              hasClass('wpop_slidein_left')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
                removeClass('wpop_slidein_left');
          }

          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              hasClass('wpop_slidein_right')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
                removeClass('wpop_slidein_right');
          }

          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              hasClass('wpop_slidein_bottom_center')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
                removeClass('wpop_slidein_bottom_center');
          }

          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              hasClass('wpop_slidein_left_center')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
                removeClass('wpop_slidein_left_center');
          }

          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              hasClass('wpop_slidein_right_center')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
                removeClass('wpop_slidein_right_center');
          }

          $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_slide_in').
              addClass('wpop_slidein_' + newval);

        });
      });

  wp.customize('wpop_data_' + xo_skin_id + '[design][bar_position]',
      function(value) {

        value.bind(function(newval) {
          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_bar').
              hasClass('wpop_bar_top')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_bar').
                removeClass('wpop_bar_top');
            jQuery('body').animate({
              marginTop: '0',
            }, 500);
          }

          if ($('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_bar').
              hasClass('wpop_bar_bottom')) {

            $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_bar').
                removeClass('wpop_bar_bottom');

            const wrapper_height = jQuery('.xo_' + wpop_id).outerHeight(true);
            jQuery('body').animate({
              marginTop: wrapper_height,
            }, 500);
          }

          $('.xo_bar_wrap.xo_' + wpop_id + '.xo_is_bar').
              addClass('wpop_bar_' + newval);

        });
      });

})(jQuery);