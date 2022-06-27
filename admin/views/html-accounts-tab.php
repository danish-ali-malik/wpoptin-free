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
}else{
	$wpoptin_id = '';
}

if ( isset( $_GET['goal'] ) && ! empty( $_GET['goal'] ) ) {
	$goal = (string) sanitize_text_field( $_GET['goal'] );

}

if ( isset( $_GET['type'] ) && ! empty( $_GET['type'] ) ) {
	$type = (string) sanitize_text_field( $_GET['type'] );
}

if ( isset( $_GET['is_child'] ) && $_GET['is_child'] == 'true' ) {
	$is_child = true;
}else{
    $is_child = false;
}

$current_url = urlencode_deep( admin_url( 'admin.php?page=wpop_new&goal=' . $goal . '&type=' . $type . '' ) );
?>
    <div class="tab-content fadeIn" id="accounts-panel">
        <div class="xo_optin_accounts">

            <div class="row">

                <div class="col s9">

                    <h4>
						<?php esc_html_e( "Integrate service provider", 'wpoptin' ); ?>
                    </h4>
                    <ul>
                        <li class="xo_account_provider_li">
                            <label class="label">
								<?php esc_html_e( "Select email provider", 'wpoptin' ); ?>
                            </label>
                            <select class="xo_account_provider">
                                <option value="0"><?php esc_html_e( "Select one", 'wpoptin' ); ?>
                                </option>
								<?php
								$terms = get_terms( 'wpop_accounts_tax', [ 'hide_empty' => false ] );
								if ( $terms ) {
									foreach ( $terms as $term ) { ?>
                                        <option value="<?php echo $term->slug ?>"
											<?php echo selected( $term->slug, $WPOptins->wpop_get_settings( $wpoptin_id, 'service_provider', false, false, $is_child ), false ) ?> ><?php echo $term->name ?>
                                        </option>
									<?php }
								} ?>
                            </select>
                        </li>

                        <div class="xo_lists_holder">
							<?php
							if ( isset( $wpoptin_id ) and isset( $goal ) ) {

								$service_name = $WPOptins->wpop_get_settings( $wpoptin_id, 'service_provider', false, false, $is_child );
								/*
								* Arguments for WP_Query().
								*/
								$wpOptins = [
									'posts_per_page' => '500',
									'post_type' => 'wpop_accounts',
									'post_status' => 'publish',
									'meta_key' => '_wpop_service_provider',
									'meta_value' => $service_name,
									'no_found_rows' => false,
									'update_post_meta_cache' => false,
									'update_post_term_cache' => false,
								];

								/*
								* Quering all active wpOptins.
								* WP_Query() object of wp will be used.
								*/
								$wpOptins = new WP_Query( $wpOptins );

								if ( $wpOptins->have_posts() ) { ?>

                                    <li class="xo_accounts_li xo_accounts_li_<?php echo $service_name ?>">
                                        <label class="label"><?php esc_html_e( "Select account name", 'wpoptin' ); ?></label>
                                        <select class="xo_account_name">
                                            <option value="0"><?php esc_html_e( "--Select one--", 'wpoptin' ); ?></option>
											<?php while ( $wpOptins->have_posts() ): $wpOptins->the_post(); ?>
                                                <option value="<?php echo get_the_ID() ?>"<?php echo selected( get_the_ID(), $WPOptins->wpop_get_settings( $wpoptin_id, 'account_id', false, false, $is_child ), false ) ?>><?php echo get_the_title() ?>
                                                </option>
											<?php endwhile; ?>
                                            <option value="new"> <?php esc_html_e( "Add account", 'wpoptin' ); ?></option>
                                        </select>
                                    </li>
								<?php }

								$acount_id = $WPOptins->wpop_get_settings( $wpoptin_id, 'account_id', false, false, $is_child );

								$data = get_post( $acount_id, 'ARRAY_A' );

								if ( isset( $data ) ) {
									$data = maybe_unserialize( $data['post_content'] ); ?>
                                    <li class="xo_account_list_li xo_account_list_li_<?php echo $acount_id ?>">
                                        <label class="label"><?php esc_html_e( "Select mailing list", 'wpoptin' ); ?>
                                        </label>
                                        <select name="xo_account_list"
                                                class="xo_account_list">
                                            <option value="0"><?php echo __( '--Select One--', 'wpoptin' ) ?>
                                            </option>
											<?php if ( isset( $data['lists'] ) ) {
												foreach ( $data['lists'] as $key => $value ) { ?>
                                                    <option <?php echo selected( $key, $WPOptins->wpop_get_settings( $wpoptin_id, 'list_id', false, false, $is_child ), false ) ?>
                                                            value="<?php echo $key ?>"><?php echo $value ?></option>
												<?php }
											} ?>
                                        </select>
                                    </li>
								<?php }

							} ?>
                        </div>
                        <div class="xo_account_add_holder xo_account_add_holder_mailchimp">

                            <a href="https://login.mailchimp.com/oauth2/authorize?response_type=code&client_id=544239805685&redirect_uri=https://wpxoptin.com/mc-vZEhNb7e8PFA/index.php&state=<?php echo $current_url ?>"
                               class="btn waves-effect waves-light xo_acount_auth_page">
								<?php esc_html_e( "Authenticate", 'wpoptin' ); ?>
                                <i class="material-icons right">vpn_key
                                </i>
                            </a>

                        </div>
                        <div class="xo_account_add_holder xo_account_add_holder_constant_contact">

							<?php

							$cc_client_ids = [
								'hx3xc86m93xmvspzcyzgw6ad',
								'pmrc28fx79vtruyz5axhhjm9',
								'sjz26qf8nq5ny9xkd6dux5w8',
							];

							$cc_client_id = array_rand( $cc_client_ids );

							$cc_client_id = $cc_client_ids[ $cc_client_id ];

							$cc_current_url = urlencode_deep( admin_url( 'admin.php?page=wpop_new&goal=' . $goal . '&type=' . $type . '&client_id=' . $cc_client_id . '' ) );

							?>

                            <a href="https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize?response_type=code&client_id=<?php echo $cc_client_id ?>&redirect_uri=http://wpxoptin.com/optins-integrations-vZEhNb7e8PFA/cc-6WvtkhasGyJ5FNWj/index.php&state=<?php echo $cc_current_url ?>"
                               class="btn waves-effect waves-light xo_acount_auth_page">
								<?php esc_html_e( "Authenticate", 'wpoptin' ); ?>
                                <i class="material-icons right">vpn_key
                                </i>
                            </a>

                        </div>
                        <div class="xo_account_add_holder xo_account_add_holder_campaign_monitor">

                            <a href="https://api.createsend.com/oauth?type=web_server&client_id=120333&redirect_uri=http://wpxoptin.com/optins-integrations-vZEhNb7e8PFA/cm-wYChvuR2wvj5/index.php&scope=ManageLists&state=<?php echo $current_url ?>"
                               class="btn waves-effect waves-light xo_acount_auth_page">
								<?php esc_html_e( "Authenticate", 'wpoptin' ); ?>
                                <i class="material-icons right">vpn_key
                                </i>
                            </a>

                        </div>
                        <div class="xo_account_add_holder xo_account_add_holder_mad_mimi">
                            <li>
                                <div class="input-field col s12">
                                    <input id="mm_acount_name" type="text"
                                           name="mm_acount_name">
                                    <label for="mm_acount_name">
										<?php esc_html_e( "Account name", 'wpoptin' ); ?>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="input-field col s12">
                                    <input id="mm_user_name" type="text"
                                           name="mm_user_name">
                                    <label for="mm_user_name">
										<?php esc_html_e( "User Name", 'wpoptin' ); ?>
                                    </label>
                                </div>
                            </li>
                            <li class="xo_apikey_li">
                                <div class="input-field col s12">
                                    <input id="mm_acount_api" type="password"
                                           name="mm_acount_api">
                                    <label for="mm_acount_api">
										<?php esc_html_e( "Api key", 'wpoptin' ); ?>
                                    </label>
                                </div>
                            </li>
                            <li class="xo_authBtn_li">
                                <button class="btn waves-effect waves-light xo_acount_auth_mad_mimi">
									<?php esc_html_e( "Authenticate", 'wpoptin' ); ?>
                                    <i class="material-icons right">vpn_key
                                    </i>
                                </button>
                            </li>
                        </div>
                        <div class="xo_account_add_holder xo_account_add_holder_mailpoet">
                            <li>
                                <div class="input-field col s12">
                                    <input id="mp_acount_name" type="text"
                                           name="mp_acount_name">
                                    <label for="mp_acount_name">
										<?php esc_html_e( "Account name", 'wpoptin' ); ?>
                                    </label>
                                </div>
                            </li>
                            <li class="xo_authBtn_li">
                                <button class="btn waves-effect waves-light xo_acount_auth_mailpoet">
									<?php esc_html_e( "Authenticate", 'wpoptin' ); ?>
                                    <i class="material-icons right">vpn_key
                                    </i>
                                </button>
                            </li>
                        </div>
                    </ul>

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
                                       href="https://wpxoptin.com/documentation/how-to-create-opt-in"
                                       class="btn waves-effect waves-light">
										<?php esc_html_e( "Check this out", 'wpoptin' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Submit button html starts here. -->
            <input type="hidden" value="<?php echo $wpoptin_id ?>"
                   id="xo_hidden_id">
            <input type="hidden" value="<?php echo $goal ?>"
                   id="xo_account_goal_hidden">
            <input type="hidden" value="<?php echo $type ?>"
                   id="xo_account_type_hidden">
            <span class="xo_sub_holder">
      <button data-snc="false"
              class="btn waves-effect waves-light xo_submit_btn">
        <?php esc_html_e( "Save", 'wpoptin' ); ?>
      </button>	
      <button data-snc="true"
              class="btn waves-effect waves-light xo_submit_btn xo_right">
        <?php esc_html_e( "Save & continue", 'wpoptin' ); ?>
      </button>
    </span>
            <!-- Submit button html ends here. -->


        </div>
    </div>

<?php if ( wpop_fs()->is_free_plan() ) { ?>

    <div id="wpop-upgrade-mail-poet" class="modal wpop-upgrade-modal">
        <div class="modal-content">
            <div class="wpop-modal-content">
                <span class="wpop-lock-icon"><i class="material-icons">lock_outline</i> </span>
                <h5><?php esc_html_e( "Premium Feature", 'wpoptin' ); ?></h5>
                <p><?php esc_html_e( "We're sorry, Mailpoet integration is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Keep increase conversion by 10x while testing new ideas.", 'wpoptin' ); ?></p>
                <hr>
                <p><?php esc_html_e( "Use following coupon to get 27% discount for limited time", 'wpoptin' ); ?>
                    <br><code>BFCMOP</code></p>
                <a href="<?php echo wpop_fs()->get_upgrade_url() ?>"
                   class="waves-effect waves-light btn z-depth-3"><i
                            class="material-icons right">lock_open</i><?php esc_html_e( "Upgrade to pro", 'wpoptin' ); ?>
                </a>

            </div>
        </div>
    </div>

<?php } ?>