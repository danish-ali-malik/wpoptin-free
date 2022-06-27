<?php
/**
 * Admin View: Tab - Accounts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Getting Main class. */
$WPOptins = new WPOptins();

$goal = 'offer_bar';

if ( isset( $_GET['wpop_optin_id'] ) && ! empty( $_GET['wpop_optin_id'] ) ) {
	$wpoptin_id = (int) sanitize_text_field( $_GET['wpop_optin_id'] );
}

if ( isset( $_GET['goal'] ) && ! empty( $_GET['goal'] ) ) {
	$goal = (string) sanitize_text_field( $_GET['goal'] );
}

if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) {
	$type = (string) sanitize_text_field( $_GET['type'] );
}

if ( isset( $_GET['is_child'] ) && $_GET['is_child'] == 'true' ) {
	$is_child = true;
}

?>
<div class="tab-content fadeIn active" id="content-panel">
    <div class="row">
        <div class="col s9">
            <form class="xo_addNewform">
                <div class="input-field col s12">
                    <input id="wpop_new_title" type="text"
                           value="<?php echo $WPOptins->wpop_get_settings( $wpoptin_id, 'title', false, false, $is_child ) ?>"
                           name="wpop_new_title">
                    <label for="wpop_new_title">
						<?php esc_html_e( "Campaign name for your own reference (optional)", 'wpoptin' ); ?>
                    </label>
                </div>
				<?php require WPOP_DIR . 'admin/modules-forms/' . $goal . '.php'; ?>
                <input type="hidden" value="<?php echo $goal ?>"
                       name="wpop_new_goal" id="wpop_new_goal">
                <input type="hidden" value="<?php echo $wpoptin_id ?>"
                       name="wpop_edit_id" id="wpop_edit_id">
                <input type="hidden" value="<?php echo $type ?>"
                       name="wpop_new_type" id="wpop_new_type">
                <input name="xo_new_sub" data-snc="false" type="submit"
                       class="xo_submit xo_new_sub"
                       value="<?php esc_html_e( "Save", 'wpoptin' ); ?>">
                <input name="xo_new_sub" data-snc="true" type="submit"
                       class="xo_submit xo_new_sub xo_right"
                       value="<?php esc_html_e( "Save & continue", 'wpoptin' ); ?>">
            </form>
        </div>
        <div class="col s3">
            <div class="xo_optin_accounts_right">
                <div class="xo_lost_holder">
                    <div class="card">
                        <div class="wpop-card-icon">
                            <i class="material-icons dp48">sentiment_very_dissatisfied
                            </i>
                        </div>
                        <h4>
							<?php esc_html_e( "Feeling Lost?", 'wpoptin' ); ?>
                        </h4>
                        <div class="card-content">
                            <a target="_blank"
                               href="<?php echo $this->wpop_doc_url( $goal ); ?>"
                               class="btn waves-effect waves-light">
								<?php esc_html_e( "Check this out", 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>