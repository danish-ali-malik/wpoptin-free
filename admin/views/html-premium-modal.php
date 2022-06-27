<?php
/**
 * Premium Modal View
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
 <div id="<?php esc_attr_e( $id ); ?>" class="modal wpop-upgrade-modal">
        <div class="modal-content">
            <div class="wpop-modal-content">
                <?php do_action('wpop_admin_before_premium_modal', $id ); ?>
                <span class="wpop-lock-icon"><i class="material-icons">lock_outline</i> </span>
	            <?php do_action('wpop_admin_after_premium_modal_lock', $id ); ?>
                <h5><?php esc_html_e( $title ); ?></h5>
	            <?php do_action('wpop_admin_after_premium_modal_title', $id ); ?>
                <p><?php esc_html_e( $description ); ?></p>
	            <?php do_action('wpop_admin_after_premium_modal_description', $id ); ?>
                <?php if( isset( $wpop_banner_info['discount-text'] ) ){ ?>
                    <p><?php esc_html_e( $wpop_banner_info['discount-text'] ); ?>
	                    <?php do_action('wpop_admin_after_premium_modal_coupon_text', $id ); ?>
                <?php }
                if( isset( $wpop_banner_info['coupon'] ) ){ ?>
                  <code><?php esc_html_e( $wpop_banner_info['coupon'] ); ?></code></p>
                    <?php do_action('wpop_admin_after_premium_modal_coupon_code', $id ); ?>
                <?php }
                do_action( 'wpop_admin_before_premium_modal_button', $id );
                if( isset( $wpop_banner_info['button-text'] ) ){?>
                    <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"  class="waves-effect waves-light btn z-depth-3"><i class="material-icons right">lock_open</i><?php esc_html_e( $wpop_banner_info['button-text'] ); ?></a>
                 <?php } ?>
	            <?php do_action('wpop_admin_after_premium_modal', $id ); ?>
            </div>
        </div>
</div>