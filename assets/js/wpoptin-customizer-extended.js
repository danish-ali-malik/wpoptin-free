/*
* Show upgrade notice on position change
*/
(function(wp, $) {
  wp.customize.control('wpoptin_position_upgrade', function(control) {
    control.container.on('click', '.wopop-position-upgrade-input',
        function(event) {
          event.stopPropagation();

          jQuery('.wpop-position-upgrade-wrap').slideUp();
          jQuery(this).
              next().
              next('.wpop-position-upgrade-wrap').
              slideDown('slow');
        });
  });

  wp.customize.control('wpoptin_background_image_upgrade', function(control) {
    control.container.on('click', '.wpop-bg-image-upload', function(event) {
      event.stopPropagation();

      jQuery('.wpop-bg-image-upgrade-wrap').slideToggle();

    });
  });
})(wp, jQuery);