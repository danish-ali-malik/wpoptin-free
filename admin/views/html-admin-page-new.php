<?php
/**
 * Admin View: Page - New
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wpoptins;
$WPOptins = new WPOptins();
$campaign_publish_text = 'Publish';
$status = 'draft';

if ( isset( $_GET['wpop_optin_id'] ) ) {
	$wpoptin_id = (int) sanitize_text_field( $_GET['wpop_optin_id'] );
	$status = get_post_status ( $wpoptin_id );
	if ( $status == 'publish' ) {
		$campaign_publish_text = __( 'Switch to draft', 'wpoptin' );
	}else{
		$campaign_publish_text = __( 'Publish', 'wpoptin' );
	}
}else{
    $wpoptin_id = null;
}

if ( isset( $_GET['goal'] ) ) {
	$goal = (string) sanitize_text_field( $_GET['goal'] );
}

if ( isset( $_GET['is_child'] ) && $_GET['is_child'] == 'true' ) {
	$is_child = true;
}

if ( ! isset( $wpoptin_id ) and ! isset( $goal ) ) {
	$new_class = 'xo_new_wrap';
	$fulls = '';
} else {
	$new_class = 'xo_edit_wrap';
	$fulls     = 'xo_fullscreen';
}

?>
<div class="xo_wrap  xo_wraper_new_page <?php echo $new_class; ?> <?php echo $fulls; ?>">
  <span class="xo_loader_holder">
    <img class="loader_img fadeIn"
         src="<?php echo WPOP_URL; ?>/assets/images/wpoptin-logo.png">
  </span>
    <div class="xo_header fadeIn">
        <div class="xo_sliders_wrap">
            <img src="<?php echo WPOP_URL; ?>/assets/images/wpop-menu-icon.png"/>
        </div>
        <div class="xo-addnew-right">
            <button class="wpop-publish-campaign <?php if( isset( $wpoptin_id ) && !empty( $wpoptin_id ) ){  ?> wpop-publish-campaign-show <?php } ?>" data-id="<?php esc_attr_e( $wpoptin_id ); ?>" data-status="<?php esc_attr_e( $status ); ?>">
                <?php esc_html_e( $campaign_publish_text ); ?>
            </button>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpoptin' ) ) ?>"
               class="xo-close wpop-tooltipped" data-position="bottom"
               data-tooltip="<?php esc_html_e( 'Close', 'wpoptin' ); ?>">
                <i class="material-icons">close
                </i>
            </a>
        </div>
    </div>
    <div class="xo-settings fadeIn">
        <!-- Froms content starts. -->
        <div class="xo_new_holder <?php echo $goal; ?>">
            <div class="mdl-tabs__tab-bar">
                <ul id="sortable-units" class="xo-tabs tabs" style="">
                    <li class="xo-tab  xo-tab-parent tab_1">
                        <a href="#" class="xo-tab-link">
							<?php esc_html_e( 'WPoptin Configuration', 'wpoptin' ); ?>
                        </a>
                    </li>
                    <li class="xo-tab tab tab_2 xo_acount_tab">
                        <a href="#accounts-panel" class="mdl-tabs__tab">
                            <i class="material-icons" aria-hidden="true">account_circle
                            </i>
							<?php esc_html_e( "Accounts", 'wpoptin' ); ?>
                        </a>
                    </li>
                    <li class="xo-tab tab tab_2 xo_content_tab">
                        <a href="#content-panel" class="mdl-tabs__tab active">
                            <i class="material-icons" aria-hidden="true">assignment
                            </i>
							<?php esc_html_e( "Content", 'wpoptin' ); ?>
                        </a>
                    </li>
                    <li class="xo-tab tab tab_3 xo_triggers_tab">
                        <a href="#triggers-panel" class="mdl-tabs__tab">
                            <i class="material-icons" aria-hidden="true">device_hub
                            </i>
							<?php esc_html_e( "Triggers & Conditions", 'wpoptin' ); ?>
                        </a>
                    </li>
                    <li class="xo-tab tab tab_4" id="xo_design_tab">
                        <a href="#design-panel"
                           class="mdl-tabs__tab <?php if ( ! isset( $wpoptin_id ) ): ?> disabled <?php endif; ?>">
                            <i class="material-icons" aria-hidden="true">brush
                            </i>
							<?php esc_html_e( "Design", 'wpoptin' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="xo-tab-content">
				<?php include_once WPOP_DIR . 'admin/views/html-accounts-tab.php'; ?>
				<?php include_once WPOP_DIR . 'admin/views/html-content-tab.php'; ?>
				<?php include_once WPOP_DIR . 'admin/views/html-design-tab.php'; ?>
				<?php include_once WPOP_DIR . 'admin/views/html-triggers-conditions-tab.php'; ?>
            </div>
        </div>
    </div>
	<?php if ( ! isset( $_GET['wpop_optin_id'] ) and ! isset( $_GET['goal'] ) ){ ?>
    <div class="wpop_new_goals">
        <div class="wpop_new_goals_wrap">
            <div class="row wpop-goals-holder">
                <h4>
					<?php esc_html_e( 'What’s your goal?', 'wpoptin' ); ?>
                </h4>

                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Collect emails', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Display optin form to collect emails to grow your subscribers list by 10x.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="optin">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Get Phone Calls', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Display call now button to get calls/leads by 10x than usual call now button.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="phone_calls">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col s12 m3 wpop-goal-holder xo_goal_holder xo_offer_goal">
                    <div class="card">

                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Sales, promotions and discount offers', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Promote the special offers on sales occasions like HalloWeen or New Year by giving discounts and perks.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="offer_bar">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">

                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Announcement', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Display important announcements and latest news.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="announcement">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row wpop-goals-holder">
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">

                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Social Traffic', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Get 10x more likes for site page or Facebook fan page by displaying the Facebook like button.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="social_traffic">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">

                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Custom', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Not sure? Use our custom module according to your requirements and imagination. ', 'wpoptin' ); ?>
                            </p>

                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="custom">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Goals Html ends here-->
            <div class="wpop-types-holder row wpop-goals-holder">
                <h4>
					<?php esc_html_e( 'Choose Type', 'wpoptin' ); ?>
                </h4>
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?php echo WPOP_URL; ?>/assets/images/wpop-bar-icon.svg">
                        </div>
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Bar', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'A scrolling bar at the top of your site to increase conversion.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-type-new"
                               data-href="<?php echo admin_url( 'admin.php?page=wpop_new&goal=' ) ?>"
                               data-type="bar">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?php echo WPOP_URL; ?>/assets/images/wpop-popup-icon.svg">
                        </div>
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Lightbox popup', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'A pop up or modal window in the center of your site. Very popular and very effective.', 'wpoptin' ); ?>
                            </p>

                            <a class="waves-effect waves-light btn wpop-select-type-new"
                               data-href="<?php echo admin_url( 'admin.php?page=wpop_new&goal=' ) ?>"
                               data-type="popup">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?php echo WPOP_URL; ?>/assets/images/wpop-slidein-icon.svg">
                        </div>
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Slide In', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'Alternative to PopUp and less distracting but still highly converting slide in box in the corner of your site.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-type-new"
                               data-href="<?php echo admin_url( 'admin.php?page=wpop_new&goal=' ) ?>"
                               data-type="slide_in">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-image">
                            <img src="<?php echo WPOP_URL; ?>/assets/images/wpop-welcome-matt-icon.svg">
                        </div>
                        <div class="card-content">
                            <h5>
								<?php esc_html_e( 'Welcome Mat', 'wpoptin' ); ?>
                            </h5>
                            <p>
								<?php esc_html_e( 'A full site screen take over with your selected goal which can’t be ignored.', 'wpoptin' ); ?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-type-new"
                               data-href="<?php echo admin_url( 'admin.php?page=wpop_new&goal=' ) ?>"
                               data-type="wellcome_matt">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php esc_html_e( 'Select', 'wpoptin' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="wpop-selected-goal" value=""/>
            </div>
            <!-- Types Html ends here-->
        </div>
    </div>
</div>
<?php } ?>
</div>
</div>