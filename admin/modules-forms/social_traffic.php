<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Social Traffic Fields
//======================================================================

if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'text',
    false,
    false,
    $is_child
)) ) {
    $text_value = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'text',
        false,
        false,
        $is_child
    );
} else {
    $text_value = __( 'Like us on Facebook!', 'wpoptin' );
}

if ( isset( $_GET['type'] ) && !empty($_GET['type']) ) {
    $type = $_GET['type'];
}
$url_type = $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'url_type',
    false,
    false,
    $is_child
);
?>
<div class="input-field col s12">
    <input id="wpop_new_content" type="text" value="<?php 
echo  $text_value ;
?>"
           name="wpop_new_content" required>
    <label for="wpop_new_content">
		<?php 
esc_html_e( "Content", 'wpoptin' );
?>
    </label>
</div>
<p>
	<?php 
?>

    <label>
        <input type="checkbox" class="filled-in modal-trigger"
               href="#wpop-upgrade-current-link"/>
        <span><?php 
esc_html_e( "Like the URL my visitor is on", 'wpoptin' );
?></span>
    </label>

	<?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-current-link", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, the ability to like current page user is on is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'wpoptin' ) );
}
?>


<?php 
?>
</p>

<?php 

if ( 'bar' != $type ) {
    ?>

    <div class="input-field col s12">
        <select name="wpop_url_type">
            <option value="custom" <?php 
    selected( $url_type, 'custom' );
    ?>><?php 
    esc_html_e( "Custom", 'wpoptin' );
    ?></option>
            <option value="fb_likebox" <?php 
    selected( $url_type, 'fb_likebox' );
    ?>><?php 
    esc_html_e( "Facebook likebox", 'wpoptin' );
    ?></option>
            <option value="fb_like_button" <?php 
    selected( $url_type, 'fb_like_button' );
    ?>><?php 
    esc_html_e( "Facebook like button", 'wpoptin' );
    ?></option>
        </select>
        <label><?php 
    esc_html_e( "URL type", 'wpoptin' );
    ?></label>
    </div>

<?php 
}

?>

<div class="input-field col s12 wpop_new_social_url_wrap">

    <input id="wpop_new_social_url" type="text"
           placeholder="<?php 
esc_html_e( "Example", 'wpoptin' );
?>: https://www.facebook.com/wpoptin"
           value="<?php 
echo  $WPOptins->wpop_get_settings(
    $wpoptin_id,
    'social-url',
    false,
    false,
    $is_child
) ;
?>"
           name="wpop_new_social_url">

    <label for="wpop_new_social_url">
		<?php 
esc_html_e( "URL to like", 'wpoptin' );
?>
    </label>

</div>
<?php 

if ( 'bar' != $type ) {
    ?>

    <div class="input-field col s12 wpop_feat_img_wraper">
        <input id="wpop_feat_img" type="text" id="wpop_feat_img"
               placeholder="(optional)"
               value="<?php 
    echo  $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'feat_img',
        false,
        false,
        $is_child
    ) ;
    ?>"
               name="wpop_feat_img">

        <span class="wpop-delete-feat-img tooltipped  <?php 
    if ( !$WPOptins->wpop_get_settings(
        $wpoptin_id,
        'feat_img',
        false,
        false,
        $is_child
    ) ) {
        ?> wpop-hide <?php 
    }
    ?>"
              data-id="<?php 
    echo  $wpoptin_id ;
    ?>" data-position="left"
              data-tooltip="<?php 
    esc_html_e( "Remove Image", 'wpoptin' );
    ?>"><i
                    class="material-icons">delete</i></span>

        <label for="wpop_feat_img"
               class=""><?php 
    esc_html_e( "Image", 'wpoptin' );
    ?></label>

        <i class="btn waves-effect waves-light waves-input-wrapper wpop_feat_img_btn_wraper">
            <input type="button" class=""
                   value="<?php 
    esc_html_e( "Upload Image", 'wpoptin' );
    ?>"
                   id="wpop_feat_img_btn">
            <i class="material-icons left">file_upload</i>
        </i>
    </div>

<?php 
}
