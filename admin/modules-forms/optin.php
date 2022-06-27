<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Optin Fields
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
    $text_value = __( 'Join our mailing list to stay up to date on our upcoming events', 'wpoptin' );
}


if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'newsletter-placeholder',
    false,
    false,
    $is_child
)) ) {
    $newsletter_placeholder = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'newsletter-placeholder',
        false,
        false,
        $is_child
    );
} else {
    $newsletter_placeholder = __( 'Enter your email address', 'wpoptin' );
}


if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'btn-text',
    false,
    false,
    $is_child
)) ) {
    $btn_text = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'btn-text',
        false,
        false,
        $is_child
    );
} else {
    $btn_text = __( 'Subscribe', 'wpoptin' );
}


if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'success-message',
    false,
    false,
    $is_child
)) ) {
    $success_message = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'success-message',
        false,
        false,
        $is_child
    );
} else {
    $success_message = __( 'Successfully subscribed', 'wpoptin' );
}


if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'error-message',
    false,
    false,
    $is_child
)) ) {
    $error_message = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'error-message',
        false,
        false,
        $is_child
    );
} else {
    $error_message = __( 'Something went wrong', 'wpoptin' );
}


if ( !empty($WPOptins->wpop_get_settings(
    $wpoptin_id,
    'timer-text',
    false,
    false,
    $is_child
)) ) {
    $timer_text = $timer_text = $WPOptins->wpop_get_settings(
        $wpoptin_id,
        'timer-text',
        false,
        false,
        $is_child
    );
} else {
    $timer_text = __( 'Hurry Up', 'wpoptin' );
}

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

<div class="input-field col s12">
    <input id="wpop_new_newsletter_placeholder" type="text"
           value="<?php 
echo  $newsletter_placeholder ;
?>"
           name="wpop_new_newsletter_placeholder">
    <label for="wpop_new_newsletter_placeholder">
		<?php 
esc_html_e( "Email field label", 'wpoptin' );
?>
    </label>
</div>

<div class="input-field col s12">
    <input id="wpop_new_newsletter_btn_text" type="text"
           value="<?php 
echo  $btn_text ;
?>" name="wpop_new_newsletter_btn_text">
    <label for="wpop_new_newsletter_btn_text">
		<?php 
esc_html_e( "Button label", 'wpoptin' );
?>
    </label>
</div>


<div class="input-field col s12">
    <input id="wpop_new_newsletter_sucess_msg" type="text"
           value="<?php 
echo  $success_message ;
?>"
           name="wpop_new_newsletter_sucess_msg">
    <label for="wpop_new_newsletter_sucess_msg">
		<?php 
esc_html_e( "Success Message", 'wpoptin' );
?>
    </label>
</div>

<div class="input-field col s12">
    <input id="wpop_new_newsletter_error_msg" type="text"
           value="<?php 
echo  $error_message ;
?>"
           name="wpop_new_newsletter_error_msg">
    <label for="wpop_new_newsletter_error_msg">
		<?php 
esc_html_e( "Error Message", 'wpoptin' );
?>
    </label>
</div>

<p>
	<?php 
?>
    <label>
        <input type="checkbox" class="filled-in modal-trigger"
               href="#wpop-upgrade-timer" id="wpop_enable_timer"/>
        <span><?php 
esc_html_e( "Enable timer", 'wpoptin' );
?></span>
    </label>

    <?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-timer", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, Timer is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Perfect way to increase leads by 10X with count down timer.", 'wpoptin' ) );
}
?>

<?php 
?>
</p>

<div class="wpop_new_timer_wrap">
    <p>
		<?php 
?>
        <label>
            <input type="checkbox" class="filled-in modal-trigger"
                   href="#wpop-upgrade-timer-title"
                   id="wpop_enable_timer_title"/>
            <span><?php 
esc_html_e( "Enable timer title", 'wpoptin' );
?></span>
        </label>
	    <?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-timer-title", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, Disabling timer title is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Perfect way to increase conversion by 10X with count down timer.", 'wpoptin' ) );
}
?>

<?php 
?>
</p>
<div class="input-field col s12 wpop-timer-title">
	<?php 
?>

        <input id="wpop_new_timer_text" type="text"
               value="<?php 
esc_html_e( "Hurry Up", 'wpoptin' );
?>" disabled>
	<?php 
?>
    <label for="wpop_new_timer_text">
		<?php 
esc_html_e( "Timer title", 'wpoptin' );
?>
    </label>
</div>

<p>
	<?php 
?>
    <label>
        <input type="checkbox" class="filled-in modal-trigger"
               href="#wpop-upgrade-fomo-countdown"
               id="wpop_enable_fomo_countdown"/>
        <span><?php 
esc_html_e( "Loop the timer for selected time duration", 'wpoptin' );
?></span>
    </label>

	<?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-fomo-countdown", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, Loop the timer for selected time duration is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Perfect way to increase conversion by 10X with fear not to miss count down timer.", 'wpoptin' ) );
}
?>

<?php 
?>
</p>
<div class="input-field col s12 wpop-fomo-countdown-wrap">
	<?php 
?>

        <input id="wpop_new_timer_fomo_unit" type="number"
               value="<?php 
esc_html_e( "4", 'wpoptin' );
?>" disabled>
	<?php 
?>
    <label for="wpop_new_timer_fomo_unit">
		<?php 
esc_html_e( "Time Unit", 'wpoptin' );
?>
    </label>

	<?php 
?>
		<?php 
esc_html_e( "Time Duration", 'wpoptin' );
?>
        <select  disabled>
            <option value="minutes"><?php 
esc_html_e( "Minute(s)", 'wpoptin' );
?></option>
            <option value="hours"><?php 
esc_html_e( "Hour(s)", 'wpoptin' );
?></option>
            <option value="days"><?php 
esc_html_e( "Day(s)", 'wpoptin' );
?></option>
        </select>

	<?php 
?>
</div>


<div class="input-field col s12 wpop-new-timer-default-wrap">
	<?php 
?>
        <input type="text" value="2020-02-29" disabled>
	<?php 
?>
    <label for="wpop_new_timer">
		<?php 
esc_html_e( "Timer end date", 'wpoptin' );
?>
    </label>
</div>

<div class="input-field col s12 wpop-new-timer-default-wrap">
	<?php 
?>
        <input type="text" value="12:00 AM" disabled>
	<?php 
?>
    <label for="wpop_timer_end_time">
		<?php 
esc_html_e( "Timer end time", 'wpoptin' );
?>
    </label>
</div>


<p class="wpop-new-timer-default-wrap">
	<?php 
?>
    <label>
        <input type="checkbox" class="filled-in modal-trigger"
               href="#wpop-upgrade-deactivate-timer" id="wpop_auto_deactivate"/>
        <span><?php 
esc_html_e( "Auto deactivate when time ends", 'wpoptin' );
?></span>
    </label>
	<?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-deactivate-timer", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, Auto deactivate when time ends is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features.", 'wpoptin' ) );
}
?>

<?php 
?>
</p>

</div>

<p>
	<?php 
?>
    <label>
        <input type="checkbox" class="filled-in modal-trigger"
               href="#wpop-upgrade-hide-user-optin"/>
        <span><?php 
esc_html_e( "Never show if user has already subscribed", 'wpoptin' );
?></span>
    </label>
	<?php 
if ( wpop_fs()->is_free_plan() ) {
    $this->wpop_get_premium_modal_html( "wpop-upgrade-hide-user-optin", esc_html__( "Premium Feature", 'wpoptin' ), esc_html__( "We're sorry, Never show if user has already subscribed is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features.", 'wpoptin' ) );
}
?>
</p>

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

?>
      