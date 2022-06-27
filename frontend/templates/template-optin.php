<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The Template for displaying Optin
 *
 * This template can be overridden by copying it to
 * wpoptin/frontend/templates/template-optin.php
 *
 */
//======================================================================
// Optin Template
//======================================================================
$attr2 = null;
/* Sending triggers in js. */
switch ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) ) {
    case 'auto':
        $attr2 .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
        $attr2 .= 'data-auto_method ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_auto_method' ) . '"';
        if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_auto_method' ) == 'sec' ) {
            $attr2 .= 'data-auto_sec ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_sec_value' ) . '"';
        }
        break;
    case 'scroll':
        $attr2 .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
        $attr2 .= 'data-scroll_method ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scroll_method' ) . '"';
        
        if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scroll_method' ) == 'scroll_perc' ) {
            $attr2 .= 'data-scroll_percent ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scrol_perc_value' ) . '"';
        } else {
            $attr2 .= 'data-scroll_selector ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scrol_slect_value' ) . '"';
        }
        
        break;
    case 'click':
        $attr2 .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
        $attr2 .= 'data-click_class ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_click_class' ) . '"';
        break;
    case 'exit':
        $attr2 .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
        break;
    default:
        $attr2 .= 'data-trigger_method="auto"';
        break;
}
/* Geting placeholder. */
$placeholder = $WPOptins->wpop_get_settings( $xoptin_id, 'newsletter-placeholder' );
/* Geting submit value. */
$btn_text = $WPOptins->wpop_get_settings( $xoptin_id, 'btn-text' );

if ( is_customize_preview() ) {
    $attr2 = 'data-trigger_method="auto"';
    $attr2 = 'data-auto_method="im"';
}

$feat_img = $WPOptins->wpop_get_settings(
    $xoptin_id,
    'feat_img',
    false,
    false,
    $is_child
);

if ( $feat_img ) {
    $feat_img_class = 'wpop-has-feat-img';
} else {
    $feat_img_class = 'wpop-no-feat-img';
}

$position = '';
$wpop_timer_enabled = '';

if ( wpop_fs()->is_free_plan() ) {
    $wpop_license_class = 'wpop_is_free';
} else {
    $wpop_license_class = '';
}

?>

<div id="xo_wrapper">
    <div class="xo_bar_wrap xo_not_visible xo_optin <?php 
echo  $feat_img_class ;
?> <?php 
echo  $wpop_timer_enabled ;
?> xo_<?php 
echo  $xoptin_id ;
?> xo_is_<?php 
echo  $type ;
?> xo_is_<?php 
echo  $optin_goal ;
?> <?php 
echo  $position ;
?> <?php 
echo  $wpop_license_class ;
?>"
         data-timer_date="<?php 
echo  $WPOptins->wpop_get_settings(
    $xoptin_id,
    'timer-enddate',
    false,
    false,
    $is_child
) ;
?>"
         data-timer_time="<?php 
echo  $WPOptins->wpop_get_settings(
    $xoptin_id,
    'timer-endtime',
    false,
    false,
    $is_child
) ;
?>"
         data-auto_deactivate="<?php 
echo  $WPOptins->wpop_get_settings(
    $xoptin_id,
    'timer-auto-deactivate',
    false,
    false,
    $is_child
) ;
?>"
         id="<?php 
echo  $xoptin_id ;
?>" data-module="<?php 
echo  $optin_goal ;
?>"
         data-type="<?php 
echo  $type ;
?>"
         <?php 
?>
         data-id="<?php 
echo  $xoptin_id ;
?>" <?php 
echo  $attr2 ;
?>>

		<?php 
/*
 * Populate HTML according to type
 */
switch ( $type ) {
    case 'popup':
        ?>

                <div class="wpop-popup-content-wrapper">

					<?php 
        do_action( 'wpop_optin_popup_before', $instance );
        $feat_img = $WPOptins->wpop_get_settings(
            $xoptin_id,
            'feat_img',
            false,
            false,
            $is_child
        );
        
        if ( isset( $feat_img ) && !empty($feat_img) ) {
            ?>

                        <div class="wpop-popup-feat-img">

							<?php 
            do_action( 'wpop_optin_popup_before_image', $instance );
            ?>

                            <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_optin_popup_image_url', $feat_img, $instance ) ) ;
            ?>"/>

							<?php 
            do_action( 'wpop_optin_popup_after_image', $instance );
            ?>

                        </div>
					<?php 
        }
        
        ?>
                    <div class="wpop-popup-content-holder">
                        <div class="xo_front_c">

							<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                                <div class="xo_offer_c">

									<?php 
            do_action( 'wpop_optin_popup_before_content', $instance );
            ?>

                                    <p><?php 
            echo  apply_filters( 'wpop_optin_popup_content', $content, $instance ) ;
            ?></p>

									<?php 
            do_action( 'wpop_optin_popup_after_content', $instance );
            ?>

                                </div>
							<?php 
        }
        
        ?>

                            <div class="xo_newsletter_Wrap">
                                <form class="xo_newsletter_form"
                                      name="xo_newsletter_form" method="post">

									<?php 
        do_action( 'wpop_optin_popup_before_email_field', $instance );
        ?>

                                    <input type="email"
                                           name="xo_newsletter_email" required
                                           class="xo_newsletter_email"
                                           placeholder="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_popup_email_placeholder', $placeholder, $instance ) ) ;
        ?>">
                                    </input>

									<?php 
        do_action( 'wpop_optin_popup_after_email_field', $instance );
        ?>

                                    <input type="hidden" name="xo_newsletter_id"
                                           value="<?php 
        echo  $xoptin_id ;
        ?>"/>
                                    <input type="hidden"
                                           name="xo_newsletter_is_child"
                                           value="<?php 
        echo  $is_child ;
        ?>"/>
                                    <input type="hidden"
                                           name="xo_newsletter_service"
                                           value="<?php 
        echo  $WPOptins->wpop_get_settings(
            $xoptin_id,
            'service_provider',
            false,
            false,
            $is_child
        ) ;
        ?>"/>

									<?php 
        do_action( 'wpop_optin_popup_before_submit_button', $instance );
        ?>

                                    <input type="submit"
                                           value="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_popup_button_text', $btn_text, $instance ) ) ;
        ?>"
                                           class="xo_newsletter_sub">

									<?php 
        do_action( 'wpop_optin_popup_after_submit_button', $instance );
        ?>

                                </form>
                            </div>
                        </div>
                    </div>

					<?php 
        do_action( 'wpop_optin_popup_after', $instance );
        ?>

                </div>

				<?php 
        break;
    case 'wellcome_matt':
        ?>

                <div class="wppop-wellcome-matt-container">
                    <div class="wppop-wellcome-matt-inner">

						<?php 
        do_action( 'wpop_optin_wellcome_matt_before', $instance );
        $feat_img = $WPOptins->wpop_get_settings(
            $xoptin_id,
            'feat_img',
            false,
            false,
            $is_child
        );
        
        if ( isset( $feat_img ) && !empty($feat_img) ) {
            ?>

                            <div class="wpop-wellcome-matt-feat-img">

								<?php 
            do_action( 'wpop_optin_wellcome_matt_before_image', $instance );
            ?>

                                <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_optin_wellcome_matt_image_url', $feat_img, $instance ) ) ;
            ?>"/>

								<?php 
            do_action( 'wpop_optin_wellcome_matt_after_image', $instance );
            ?>

                            </div>
						<?php 
        }
        
        ?>
                        <div class="wpop-wellcome_matt-content-holder">
                            <div class="xo_front_c">

								<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                                    <div class="xo_offer_c">

										<?php 
            do_action( 'wpop_optin_wellcome_matt_before_content', $instance );
            ?>

                                        <p><?php 
            echo  apply_filters( 'wpop_optin_wellcome_matt_content', $content, $instance ) ;
            ?></p>

										<?php 
            do_action( 'wpop_optin_wellcome_matt_after_content', $instance );
            ?>

                                    </div>
								<?php 
        }
        
        ?>

                                <div class="xo_newsletter_Wrap">
                                    <form class="xo_newsletter_form"
                                          name="xo_newsletter_form"
                                          method="post">

										<?php 
        do_action( 'wpop_optin_wellcome_matt_before_email_field', $instance );
        ?>

                                        <input type="email"
                                               name="xo_newsletter_email"
                                               required
                                               class="xo_newsletter_email"
                                               placeholder="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_wellcome_matt_email_placeholder', $placeholder, $instance ) ) ;
        ?>">
                                        </input>

										<?php 
        do_action( 'wpop_optin_wellcome_matt_after_email_field', $instance );
        ?>

                                        <input type="hidden"
                                               name="xo_newsletter_id"
                                               value="<?php 
        echo  $xoptin_id ;
        ?>"/>
                                        <input type="hidden"
                                               name="xo_newsletter_is_child"
                                               value="<?php 
        echo  $is_child ;
        ?>"/>
                                        <input type="hidden"
                                               name="xo_newsletter_service"
                                               value="<?php 
        echo  $WPOptins->wpop_get_settings(
            $xoptin_id,
            'service_provider',
            false,
            false,
            $is_child
        ) ;
        ?>"/>

										<?php 
        do_action( 'wpop_optin_wellcome_matt_before_submit_button', $instance );
        ?>

                                        <input type="submit"
                                               value="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_wellcome_matt_button_text', $btn_text, $instance ) ) ;
        ?>"
                                               class="xo_newsletter_sub">

										<?php 
        do_action( 'wpop_optin_wellcome_matt_after_submit_button', $instance );
        ?>

                                    </form>
                                </div>
                            </div>
                        </div>

						<?php 
        do_action( 'wpop_optin_wellcome_matt_after', $instance );
        ?>

                    </div>
                </div>

				<?php 
        break;
    case 'slide_in':
        $feat_img = $WPOptins->wpop_get_settings(
            $xoptin_id,
            'feat_img',
            false,
            false,
            $is_child
        );
        
        if ( isset( $feat_img ) && !empty($feat_img) ) {
            ?>

                    <div class="wpop-slidein-feat-img">

						<?php 
            do_action( 'wpop_optin_slide_in_before_image', $instance );
            ?>

                        <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_optin_slide_in_image_url', $feat_img, $instance ) ) ;
            ?>"/>

						<?php 
            do_action( 'wpop_optin_slide_in_after_image', $instance );
            ?>

                    </div>
				<?php 
        }
        
        ?>

                <div class="xo_front_c">

					<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>
                        <div class="xo_offer_c">

							<?php 
            do_action( 'wpop_optin_slide_in_before_content', $instance );
            ?>

                            <p><?php 
            echo  apply_filters( 'wpop_optin_slide_in_content', $content, $instance ) ;
            ?></p>

							<?php 
            do_action( 'wpop_optin_slide_in_after_content', $instance );
            ?>

                        </div>
					<?php 
        }
        
        ?>

                    <div class="xo_newsletter_Wrap">
                        <form class="xo_newsletter_form"
                              name="xo_newsletter_form" method="post">

							<?php 
        do_action( 'wpop_optin_slide_in_before_email_field', $instance );
        ?>

                            <input type="email" name="xo_newsletter_email"
                                   required class="xo_newsletter_email"
                                   placeholder="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_slide_in_email_placeholder', $placeholder, $instance ) ) ;
        ?>">
                            </input>

							<?php 
        do_action( 'wpop_optin_slide_in_after_email_field', $instance );
        ?>

                            <input type="hidden" name="xo_newsletter_id"
                                   value="<?php 
        echo  $xoptin_id ;
        ?>"/>
                            <input type="hidden" name="xo_newsletter_is_child"
                                   value="<?php 
        echo  $is_child ;
        ?>"/>
                            <input type="hidden" name="xo_newsletter_service"
                                   value="<?php 
        echo  $WPOptins->wpop_get_settings(
            $xoptin_id,
            'service_provider',
            false,
            false,
            $is_child
        ) ;
        ?>"/>

							<?php 
        do_action( 'wpop_optin_slide_in_before_submit_button', $instance );
        ?>

                            <input type="submit"
                                   value="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_slide_in_button_text', $btn_text, $instance ) ) ;
        ?>"
                                   class="xo_newsletter_sub">

							<?php 
        do_action( 'wpop_optin_slide_in_after_submit_button', $instance );
        ?>

                        </form>
                    </div>
					<?php 
        
        if ( wpop_fs()->is_free_plan() ) {
            ?>
                        <div title="<?php 
            echo  __( 'Powered by WPOptin', 'wpoptin' ) ;
            ?>"
                             style="cursor: pointer;opacity: .8 !important;display: block !important; z-index: 999999 !important; position: absolute;left: auto;right: auto;text-align: center;margin-left: auto;margin-right: auto;bottom: -45px;left: 50%;-webkit-transform: translate(-50%, 0);-ms-transform: translate(-50%, 0); transform: translate(-50%, 0);"
                             data-class="wpop_redirect_home"
                             data-id="wpop_bar_branding"
                             data-wpop-inverted-img="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpoptin-logo.png"
                             data-wpop-real-img="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpoptin-logo.png">
                            <img style="width: 35px;opacity: 1 !important;display: block !important;z-index: 999999 !important;"
                                 src="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpoptin-logo.png"/>
                        </div>
					<?php 
        }
        
        ?>
                    <div id="xo_close"><i class="icon mt-times"></i></div>
                </div>


				<?php 
        break;
    default:
        ?>

                <div class="xo_front_c">

					<?php 
        
        if ( wpop_fs()->is_free_plan() ) {
            ?>
                        <div title="<?php 
            echo  __( 'Powered by WPOptin', 'wpoptin' ) ;
            ?>"
                             style="cursor: pointer;opacity: .8 !important;display: block !important; z-index: 999999 !important; position: absolute;left: 25px;top: 50%;transform: translate(0, -50%);transform: translate(0, -50%); -webkit-transform:translate(0, -50%);-ms-transform:translate(0, -50%);"
                             data-class="wpop_redirect_home"
                             data-id="wpop_bar_branding"
                             data-wpop-inverted-img="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpoptin-dark-gray-logo.png"
                             data-wpop-real-img="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpop-gray-logo.png">
                            <img style="width: 35px;opacity: 1 !important;display: block !important;z-index: 999999 !important;"
                                 src="<?php 
            echo  WPOP_URL ;
            ?>/assets/images/wpop-gray-logo.png"/>
                        </div>
					<?php 
        }
        
        ?>

					<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>
                        <div class="xo_offer_c">

							<?php 
            do_action( 'wpop_optin_bar_before_content', $instance );
            ?>

                            <p><?php 
            echo  apply_filters( 'wpop_optin_bar_content', $content, $instance ) ;
            ?></p>

							<?php 
            do_action( 'wpop_optin_bar_after_content', $instance );
            ?>

                        </div>
					<?php 
        }
        
        ?>

                    <div class="xo_newsletter_Wrap">
                        <form class="xo_newsletter_form"
                              name="xo_newsletter_form" method="post">

							<?php 
        do_action( 'wpop_optin_bar_before_email_field', $instance );
        ?>

                            <input type="email" name="xo_newsletter_email"
                                   required class="xo_newsletter_email"
                                   placeholder="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_bar_email_placeholder', $placeholder, $instance ) ) ;
        ?>">
                            </input>

							<?php 
        do_action( 'wpop_optin_bar_after_email_field', $instance );
        ?>

                            <input type="hidden" name="xo_newsletter_id"
                                   value="<?php 
        echo  $xoptin_id ;
        ?>"/>
                            <input type="hidden" name="xo_newsletter_is_child"
                                   value="<?php 
        echo  $is_child ;
        ?>"/>
                            <input type="hidden" name="xo_newsletter_service"
                                   value="<?php 
        echo  $WPOptins->wpop_get_settings(
            $xoptin_id,
            'service_provider',
            false,
            false,
            $is_child
        ) ;
        ?>"/>

							<?php 
        do_action( 'wpop_optin_bar_before_submit_button', $instance );
        ?>

                            <input type="submit"
                                   value="<?php 
        echo  esc_html( apply_filters( 'wpop_optin_bar_button_text', $btn_text, $instance ) ) ;
        ?>"
                                   class="xo_newsletter_sub">

							<?php 
        do_action( 'wpop_optin_bar_after_submit_button', $instance );
        ?>

                        </form>
                    </div>
                </div>
                <div id="xo_close"><i class="icon mt-times"></i></div>

				<?php 
        break;
}
?>

    </div>
</div>