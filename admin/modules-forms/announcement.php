<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//======================================================================
// Announcement Fields
//======================================================================

if ( ! empty( $WPOptins->wpop_get_settings( $wpoptin_id, 'text', false, false, $is_child ) ) ):
	$text_value = $WPOptins->wpop_get_settings( $wpoptin_id, 'text', false, false, $is_child );
else: $text_value = __( "Flash Sale: 20% Off Sitewide, Enter Code: 20savings on Checkout!", 'wpoptin' );
endif;
?>
<div class="input-field col s12">
    <input id="wpop_new_content" type="text" value="<?php echo $text_value ?>"
           name="wpop_new_content" required>
    <label for="wpop_new_content">
		<?php esc_html_e( "Content", 'wpoptin' ); ?>
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