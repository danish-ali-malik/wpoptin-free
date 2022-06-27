<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//======================================================================
// Phone Calls Fields
//======================================================================

if ( ! empty( $WPOptins->wpop_get_settings( $wpoptin_id, 'offer-btn-text', false, false, $is_child ) ) ):
	$btn_text = $WPOptins->wpop_get_settings( $wpoptin_id, 'offer-btn-text', false, false, $is_child );
else: $btn_text = __( 'Call Now', 'wpoptin' );
endif;

if ( ! empty( $WPOptins->wpop_get_settings( $wpoptin_id, 'text', false, false, $is_child ) ) ):
	$text_value = $WPOptins->wpop_get_settings( $wpoptin_id, 'text', false, false, $is_child );
else: $text_value = __( 'Talk to us to find out more', 'wpoptin' );
endif;

?>
<div class="input-field col s12">
    <input id="wpop_new_content" type="text" value="<?php echo $text_value ?>"
           name="wpop_new_content" required>
    <label for="wpop_new_content">
		<?php esc_html_e( "Content", 'wpoptin' ); ?>
    </label>
</div>
<div class="input-field col s12">
    <input id="wpop_new_offer_btn_text" type="text"
           value="<?php echo $btn_text ?> " name="wpop_new_offer_btn_text"
           required>
    <label for="wpop_new_offer_btn_text">
		<?php esc_html_e( "Button Label", 'wpoptin' ); ?>
    </label>
</div>

<div class="input-field col s12">

    <select id="wpop_calls_country" name="wpop_country_code">

		<?php

		$country_code = $WPOptins->wpop_get_settings( $wpoptin_id, 'country-code', false, false, $is_child );

		if ( ! $country_code ) {

			$country_code = '1';
		}

		$wpop_countries_list = wpop_get_countries_list();

		foreach ( $wpop_countries_list as $code => $country ) {

			$countryName = ucwords( strtolower( $country["name"] ) ); ?>
            <option value="<?php echo $country['code']; ?>" <?php if ( $country['code'] == $country_code ): ?> selected <?php endif; ?> > <?php echo $countryName; ?>

            </option>
		<?php }


		?>

    </select>
    <label><?php esc_html_e( "Country", 'wpoptin' ); ?></label>
</div>

<div class="input-field col s12">
    <input id="wpop_new_offer_btn_url" type="text"
           value="<?php echo $WPOptins->wpop_get_settings( $wpoptin_id, 'offer-btn-url', false, false, $is_child ) ?>"
           name="wpop_new_offer_btn_url" placeholder="(555) 555-5555" required>
    <label for="wpop_new_offer_btn_url">
		<?php esc_html_e( "Phone number", 'wpoptin' ); ?>
    </label>
</div>

<?php if ( 'bar' != $type ): ?>

    <div class="input-field col s12 wpop_feat_img_wraper">
        <input id="wpop_feat_img" type="text" id="wpop_feat_img"
               placeholder="(optional)"
               value="<?php echo $WPOptins->wpop_get_settings( $wpoptin_id, 'feat_img', false, false, $is_child ) ?>"
               name="wpop_feat_img">

        <span class="wpop-delete-feat-img tooltipped  <?php if ( ! $WPOptins->wpop_get_settings( $wpoptin_id, 'feat_img', false, false, $is_child ) ) { ?> wpop-hide <?php } ?>"
              data-id="<?php echo $wpoptin_id; ?>" data-position="left"
              data-tooltip="<?php esc_html_e( "Remove Image", 'wpoptin' ); ?>"><i
                    class="material-icons">delete</i></span>

        <label for="wpop_feat_img"
               class=""><?php esc_html_e( "Image", 'wpoptin' ); ?></label>

        <i class="btn waves-effect waves-light waves-input-wrapper wpop_feat_img_btn_wraper">
            <input type="button" class=""
                   value="<?php esc_html_e( "Upload Image", 'wpoptin' ); ?>"
                   id="wpop_feat_img_btn">
            <i class="material-icons left">file_upload</i>
        </i>
    </div>

<?php endif; ?>             