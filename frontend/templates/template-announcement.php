<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The Template for displaying Announcement
 *
 * This template can be overridden by copying it to
 * wpoptin/frontend/templates/template-announcement.php
 *
 */
//======================================================================
// Announcement Html
//======================================================================

if ( is_customize_preview() ) {
    $attr = 'data-trigger_method="auto"';
    $attr = 'data-auto_method="im"';
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

if ( wpop_fs()->is_free_plan() ) {
    $wpop_license_class = 'wpop_is_free';
} else {
    $wpop_license_class = '';
}

?>

<div id="xo_wrapper">
    <div class="xo_bar_wrap xo_not_visible <?php 
echo  $feat_img_class ;
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
         data-module="<?php 
echo  $optin_goal ;
?>" data-type="<?php 
echo  $type ;
?>"
	    <?php 
?>
         id="<?php 
echo  $xoptin_id ;
?>"
         data-id="<?php 
echo  $xoptin_id ;
?>" <?php 
echo  $attr ;
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
        do_action( 'wpop_announcement_popup_before', $instance );
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
            do_action( 'wpop_announcement_popup_before_image', $instance );
            ?>

                            <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_announcement_popup_image_url', $feat_img, $instance ) ) ;
            ?>"/>

							<?php 
            do_action( 'wpop_announcement_popup_after_image', $instance );
            ?>
                        </div>

					<?php 
        }
        
        ?>
                    <div class="wpop-popup-content-holder">
                        <div class="xo_front_c">
                            <div class="xo_offer_c_wrap">
								<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                                    <div class="xo_offer_c">

										<?php 
            do_action( 'wpop_announcement_popup_before_content', $instance );
            ?>

                                        <p><?php 
            echo  apply_filters( 'wpop_announcement_popup_content', $content, $instance ) ;
            ?></p>

										<?php 
            do_action( 'wpop_announcement_popup_after_content', $instance );
            ?>

                                    </div>

								<?php 
        }
        
        ?>
                            </div>
                        </div>
                    </div>
                </div>

				<?php 
        do_action( 'wpop_announcement_popup_after', $instance );
        break;
    case 'wellcome_matt':
        ?>

                <div class="wppop-wellcome-matt-container">
                    <div class="wppop-wellcome-matt-inner">

						<?php 
        do_action( 'wpop_announcement_wellcome_matt_before', $instance );
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
            do_action( 'wpop_announcement_wellcome_matt_before_image', $instance );
            ?>

                                <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_announcement_wellcome_matt_image_url', $feat_img, $instance ) ) ;
            ?>"/>

								<?php 
            do_action( 'wpop_announcement_wellcome_matt_after_image', $instance );
            ?>
                            </div>

						<?php 
        }
        
        ?>

                        <div class="xo_front_c">
                            <div class="xo_offer_c_wrap">
								<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                                    <div class="xo_offer_c">

										<?php 
            do_action( 'wpop_announcement_wellcome_matt_before_content', $instance );
            ?>

                                        <p><?php 
            echo  apply_filters( 'wpop_announcement_wellcome_matt_content', $content, $instance ) ;
            ?></p>

										<?php 
            do_action( 'wpop_announcement_wellcome_matt_after_content', $instance );
            ?>

                                    </div>

								<?php 
        }
        
        ?>
                            </div>
                        </div>

						<?php 
        do_action( 'wpop_announcement_wellcome_matt_after', $instance );
        ?>
                    </div>
                </div>

				<?php 
        break;
    case 'slide_in':
        ?>


				<?php 
        do_action( 'wpop_announcement_slide_in_before', $instance );
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
            do_action( 'wpop_announcement_slide_in_before_image', $instance );
            ?>

                        <img src="<?php 
            echo  esc_url( apply_filters( 'wpop_announcement_slide_in_image_url', $feat_img, $instance ) ) ;
            ?>"/>

						<?php 
            do_action( 'wpop_announcement_slide_in_after_image', $instance );
            ?>
                    </div>

				<?php 
        }
        
        ?>

                <div class="xo_front_c">
                    <div class="xo_offer_c_wrap">
						<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                            <div class="xo_offer_c">

								<?php 
            do_action( 'wpop_announcement_slide_in_before_content', $instance );
            ?>

                                <p><?php 
            echo  apply_filters( 'wpop_announcement_slide_in_content', $content, $instance ) ;
            ?></p>

								<?php 
            do_action( 'wpop_announcement_slide_in_after_content', $instance );
            ?>

                            </div>

						<?php 
        }
        
        ?>
                    </div>
                    <div id="xo_close"><i class="icon mt-times"></i></div>
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
                </div>

				<?php 
        do_action( 'wpop_announcement_slide_in_after', $instance );
        ?>

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

                    <div class="xo_offer_c_wrap">

						<?php 
        
        if ( isset( $content ) && !empty($content) ) {
            ?>

                            <div class="xo_offer_c">

								<?php 
            do_action( 'wpop_announcement_bar_before_content', $instance );
            ?>

                                <p><?php 
            echo  apply_filters( 'wpop_announcement_bar_content', $content, $instance ) ;
            ?></p>

								<?php 
            do_action( 'wpop_announcement_bar_after_content', $instance );
            ?>

                            </div>
						<?php 
        }
        
        ?>
                    </div>
                    <div id="xo_close"><i class="icon mt-times"></i></div>
                </div>

				<?php 
        break;
}
?>


    </div>
</div>