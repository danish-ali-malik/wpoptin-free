<?php
/**
 * Admin Notices
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'install_plugins' ) ) {
	return;
}

$install_date = get_option( 'wpop_installDate' );

$display_date = date( 'Y-m-d h:i:s' );

$datetime1 = new DateTime( $install_date );

$datetime2 = new DateTime( $display_date );

$diff_intrval = round( ( $datetime2->format( 'U' ) - $datetime1->format( 'U' ) ) / ( 60 * 60 * 24 ) );

/* If plugin is installed from one week the show admin notice. */
if ( $diff_intrval >= 7 && get_site_option( 'wpop_support' ) != "yes" ) { ?>

    <div class="update-nag wpop_msg">
        <p><?php esc_html_e( "Awesome, you have been using", 'wpoptin' ); ?>
            <b><?php esc_html_e( "WPOptin", 'wpoptin' ); ?></b> <?php esc_html_e( "for more than 1 week.", 'wpoptin' ); ?>
        </p>
        <p><?php esc_html_e( "May i ask you to give it a ", 'wpoptin' ); ?>
            <b><?php esc_html_e( "5-star", 'wpoptin' ); ?></b> <?php esc_html_e( "rating on Wordpress?", 'wpoptin' ); ?>
        </p>
        <p><?php esc_html_e( "This will help to spread its popularity and to make this plugin a better one.", 'wpoptin' ); ?></p>

        <p><?php esc_html_e( "Your help is much appreciated. Thank you very much.", 'wpoptin' ); ?></p>
        <p style="margin-left:5px;">~Danish Ali Malik (@danish-ali)</p>
        <div class="wpop_support_btns">
            <a href="https://wordpress.org/support/plugin/wpoptin/reviews/?filter=5#new-post"
               class="wpop-hide-week-notice-action button button-primary wpop-rate-now-btn"
               target="_blank">
				<?php esc_html_e( "Rate it now", 'wpoptin' ); ?> <span
                        class="stars">★ ★ ★ ★ ★ </span>
            </a>
            <a href="javascript:void(0);"
               class="wpop-hide-week-notice button wpop-hide-week-notice-action">
				<?php esc_html_e( "I already rated it", 'wpoptin' ); ?>
            </a>

            <div class="wpop-rating-dismiss wpop-hide-week-notice-action">
                <span><?php esc_html_e( "Dismiss", 'wpoptin' ); ?></span> (X)
            </div>
        </div>
    </div>

    <style type="text/css">
        .wpop_msg {
            position: relative;
            padding-right: 80px;
            border-color: #e96460;
            letter-spacing: .5px;
            background: #fff;
            box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
            -moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
            -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
        }

        .wpop_support_btns .button.button-primary.wpop-rate-now-btn {
            margin-top: 10px;
            height: auto;
            width: auto;
            padding: 11px 25px;
            border-radius: 20px;
            text-transform: capitalize;
            font-size: 14px;
            line-height: 22px;
            background: #ed6d62;
            background: -moz-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            background: -webkit-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            background: linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
            border: none;
        }

        .wpop_support_btns .button.button-primary.wpop-rate-now-btn:hover {
            color: #fff;
            background: #ed6d62;
            background: -moz-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            background: -webkit-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            background: linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
        }

        .wpop_support_btns .button.wpop-hide-week-notice {
            margin-top: 18px;
            margin-left: 10px;
            font-size: 11px;
            line-height: 2.3;
            min-height: 28px;
        }

        .wpop-rating-dismiss {
            position: absolute;
            right: 5px;
            cursor: pointer;
            top: 5px;
            color: #029be4;
        }

        .wpop-rating-dismiss .dashicons.dashicons-no-alt {
            margin-left: 2px;
        }
    </style>
    <script>
      jQuery(document).ready(function($) {

        jQuery('.wpop-hide-week-notice-action').click(function() {
          var data = {'action': 'wpop_supported'};
          jQuery.ajax({

            url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            type: 'post',
            data: data,
            dataType: 'json',
            async: !0,
            success: function(e) {

              if (e == 'success') {
                jQuery('.wpop_msg').slideUp('fast');

              }
            },
          });
        });

      });
    </script>

<?php }

/*
* First campaign notice
*/
if ( get_site_option( 'wpop_hide_campaign_notice' ) != "yes" ) {

	global $wpoptins;

	if ( $wpoptins && ! empty( $wpoptins ) ) {

		$wpop_is_active = false;

		$wpop_active_id = false;

		foreach ( $wpoptins as $wpoptin ) {

			if ( $wpoptin['status'] == 'active' ) {

				$wpop_is_active = true;

				$wpop_active_id = $wpoptin['ID'];

			}
		}

		if ( $wpop_is_active && $wpop_active_id ) {

			$wpop_instance = $wpoptins[ $wpop_active_id ];

			?>

            <div class="update-nag wpop_campaign_msg">
                <p><?php esc_html_e( "Hey, I noticed you created", 'wpoptin' ); ?>
                    <b><?php echo $wpop_instance['goal'][0]; ?></b> <?php esc_html_e( "campaign", 'wpoptin' ); ?> <?php esc_html_e( "that’s awesome!", 'wpoptin' ); ?>
                </p>
                <p><?php esc_html_e( "Could you please do me a BIG favor and give it a ", 'wpoptin' ); ?>
                    <b><?php esc_html_e( "5-star", 'wpoptin' ); ?></b> <?php esc_html_e( "rating on WordPress to help us spread the word and boost our motivation?", 'wpoptin' ); ?>
                </p>
                <p>~Danish Ali Malik (@danish-ali)</p>
                <div class="wpop_support_btns">
                    <a href="https://wordpress.org/support/plugin/wpoptin/reviews/?filter=5#new-post"
                       class="button button-primary wpop-hide-campaign-notice-action wpop-rate-now-btn"
                       target="_blank">
						<?php esc_html_e( "Rate it now", 'wpoptin' ); ?> <span
                                class="stars">★ ★ ★ ★ ★ </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="wpop-hide-campaign-notice wpop-hide-campaign-notice-action button">
						<?php esc_html_e( "I already rated it", 'wpoptin' ); ?>
                    </a>
                    <div class="wpop-rating-dismiss wpop-hide-campaign-notice-action">
                        <span><?php esc_html_e( "Dismiss", 'wpoptin' ); ?></span>
                        (X)
                    </div>
                </div>
            </div>

            <style type="text/css">
                .wpop_campaign_msg {
                    position: relative;
                    padding-right: 80px;
                    border-color: #e96460;
                    letter-spacing: .5px;
                    background: #fff;
                    box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                    -moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                    -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                }

                .wpop_support_btns .button.button-primary.wpop-rate-now-btn {
                    margin-top: 10px;
                    height: auto;
                    width: auto;
                    padding: 11px 25px;
                    border-radius: 20px;
                    text-transform: capitalize;
                    font-size: 14px;
                    line-height: 22px;
                    background: #ed6d62;
                    background: -moz-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: -webkit-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
                    border: none;
                }

                .wpop_support_btns .button.button-primary.wpop-rate-now-btn:hover {
                    color: #fff;
                    background: #ed6d62;
                    background: -moz-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: -webkit-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
                }

                .wpop_support_btns .button.wpop-hide-campaign-notice {
                    margin-top: 18px;
                    margin-left: 10px;
                    font-size: 11px;
                    line-height: 2.3;
                    min-height: 28px;
                }

                .wpop-rating-dismiss {
                    position: absolute;
                    right: 5px;
                    cursor: pointer;
                    top: 5px;
                    color: #029be4;
                }

                .wpop-rating-dismiss .dashicons.dashicons-no-alt {
                    margin-left: 2px;
                }
            </style>

            <script>

              jQuery(document).ready(function($) {

                jQuery('.wpop-hide-campaign-notice-action').click(function() {
                  var data = {'action': 'wpop_hide_campaign_notice'};
                  jQuery.ajax({

                    url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    async: !0,
                    success: function(e) {

                      if (e == 'success') {
                        jQuery('.wpop_campaign_msg').slideUp('fast');

                      }
                    },
                  });
                });

              });
            </script>


		<?php }

	}

}

/*
* Conversions notice
*/
if ( get_site_option( 'wpop_hide_conversion_notice' ) != "yes" ) {

	global $wpoptins;

	if ( $wpoptins && ! empty( $wpoptins ) ) {

		$wpop_all_conversions = false;

		foreach ( $wpoptins as $wpoptin ) {

			$wpop_all_conversions += $wpoptin['bar_conversions'];

		}

		if ( $wpop_all_conversions && $wpop_all_conversions >= 30 ) {

			?>

            <div class="update-nag wpop_conversion_msg">
                <p><?php esc_html_e( "Congratulations on", 'wpoptin' ); ?>
                    <b><?php echo $wpop_all_conversions; ?>
                        +</b> <?php esc_html_e( "conversions using WPOptin", 'wpoptin' ); ?>
                    - <?php esc_html_e( "that’s awesome!", 'wpoptin' ); ?></p>
                <p><?php esc_html_e( "Could you please do me a BIG favor and give it a ", 'wpoptin' ); ?>
                    <b><?php esc_html_e( "5-star", 'wpoptin' ); ?></b> <?php esc_html_e( "rating on WordPress to help us spread the word and boost our motivation?", 'wpoptin' ); ?>
                </p>
                <p>~Danish Ali Malik (@danish-ali)</p>
                <div class="wpop_support_btns">
                    <a href="https://wordpress.org/support/plugin/wpoptin/reviews/?filter=5#new-post"
                       class="button button-primary wpop-hide-conversion-notice-action wpop-rate-now-btn"
                       target="_blank">
						<?php esc_html_e( "Rate the plugin", 'wpoptin' ); ?>
                        <span class="stars">★ ★ ★ ★ ★ </span>
                    </a>
                    <a href="javascript:void(0);"
                       class="wpop-hide-conversion-notice wpop-hide-conversion-notice-action button">
						<?php esc_html_e( "I already rated it", 'wpoptin' ); ?>
                    </a>
                    <div class="wpop-rating-dismiss wpop-hide-conversion-notice-action">
                        <span><?php esc_html_e( "Dismiss", 'wpoptin' ); ?></span>
                        (X)
                    </div>
                </div>
            </div>

            <style type="text/css">
                .wpop_conversion_msg {
                    position: relative;
                    padding-right: 80px;
                    border-color: #e96460;
                    letter-spacing: .5px;
                    background: #fff;
                    box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                    -moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                    -webkit-box-shadow: 0 0 10px 0 rgba(0,0,0,0.15);
                }

                .wpop_support_btns .button.button-primary.wpop-rate-now-btn {
                    margin-top: 10px;
                    height: auto;
                    width: auto;
                    padding: 11px 25px;
                    border-radius: 20px;
                    text-transform: capitalize;
                    font-size: 14px;
                    line-height: 22px;
                    background: #ed6d62;
                    background: -moz-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: -webkit-linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: linear-gradient(282deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
                    border: none;
                }

                .wpop_support_btns .button.button-primary.wpop-rate-now-btn:hover {
                    color: #fff;
                    background: #ed6d62;
                    background: -moz-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: -webkit-linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    background: linear-gradient(124deg, #ed6d62 0%, rgba(201, 36, 89, 0.800157563) 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ed6d62", endColorstr="#c92459", GradientType=1);
                }

                .wpop_support_btns .button.wpop-hide-conversion-notice {
                    margin-top: 18px;
                    margin-left: 10px;
                    font-size: 11px;
                    line-height: 2.3;
                    min-height: 28px;
                }

                .wpop-rating-dismiss {
                    position: absolute;
                    right: 5px;
                    cursor: pointer;
                    top: 5px;
                    color: #029be4;
                }

                .wpop-rating-dismiss .dashicons.dashicons-no-alt {
                    margin-left: 2px;
                }
            </style>

            <script>

              jQuery(document).ready(function($) {

                jQuery('.wpop-hide-conversion-notice-action').click(function() {
                  var data = {'action': 'wpop_hide_conversion_notice'};
                  jQuery.ajax({

                    url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    async: !0,
                    success: function(e) {

                      if (e == 'success') {
                        jQuery('.wpop_conversion_msg').slideUp('fast');

                      }
                    },
                  });
                });

              });
            </script>


		<?php }

	}

}

?>