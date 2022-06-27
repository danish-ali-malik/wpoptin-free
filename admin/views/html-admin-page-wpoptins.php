<?php

/**
 * Admin View: Page - Wpoptins
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $wpoptins ;
global  $wpoptins_accounts ;
?>
<div class="xo_wrap z-depth-1">
  <span class="xo_loader_holder">
    <img class="loader_img fadeIn"
         src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpoptin-logo.png">
  </span>
    <div class="xo_header fadeIn">
        <div class="xo_sliders_wrap">
            <img src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpop-menu-icon.png"/>
        </div>
        <div class="xo-addnew-right">
			<?php 

if ( !empty($wpoptins_accounts) ) {
    ?>
                <a href="<?php 
    echo  esc_url( admin_url( 'admin.php?page=wpop_accounts' ) ) ;
    ?>"
                   id="accounts_li" class="ox-accounts-li wpop-tooltipped"
                   data-position="bottom"
                   data-tooltip="<?php 
    esc_html_e( "Accounts", 'wpoptin' );
    ?>">
                    <i class="material-icons">account_circle
                    </i>
                </a>
			<?php 
}


if ( !empty($wpoptins) ) {
    ?>
                <a href="<?php 
    echo  esc_url( admin_url( 'admin.php?page=wpop_overview' ) ) ;
    ?>"
                   id="xo_stats" class="xo_stats wpop-tooltipped"
                   data-position="bottom"
                   data-tooltip="<?php 
    esc_html_e( 'Analytics', 'wpoptin' );
    ?>">
                    <i class="material-icons">insert_chart
                    </i>
                </a>
			<?php 
}

?>
            <a href="<?php 
echo  esc_url( admin_url( 'admin.php?page=wpop_new' ) ) ;
?>"
               class="ox-add-new wpop-tooltipped" data-position="bottom"
               data-tooltip="<?php 
esc_html_e( 'Add New', 'wpoptin' );
?>">
                <i class="material-icons">add
                </i>
                </i>
            </a>
        </div>
    </div>
	<?php 

if ( !empty($wpoptins) ) {
    ?>
        <div class="xo-settings-genral-wrapper">
            <table class="xo_offers_table xo_table_layout responsive-table highlight">
                <thead>
                <tr>
                    <th>
						<?php 
    esc_html_e( 'Name', 'wpoptin' );
    ?>
                    </th>
                    <th>
						<?php 
    esc_html_e( 'Type', 'wpoptin' );
    ?>
                    </th>
                    <th>

						<?php 
    esc_html_e( 'View(s)', 'wpoptin' );
    ?>

                    </th>
                    <th>
						<?php 
    esc_html_e( 'Conversion(s)', 'wpoptin' );
    ?>
                            <a href="<?php 
    echo  wpop_fs()->get_upgrade_url() ;
    ?>"
                               class="wpop-upgrade-link"><?php 
    esc_html_e( "Pro", 'wpoptin' );
    ?></a>
						<?php 
    ?>

                    </th>
                    <th>
						<?php 
    esc_html_e( 'Percentage', 'wpoptin' );
    ?>
                            <a href="<?php 
    echo  wpop_fs()->get_upgrade_url() ;
    ?>"
                               class="wpop-upgrade-link"><?php 
    esc_html_e( "Pro", 'wpoptin' );
    ?></a>
						<?php 
    ?>
                    </th>
                    <th>
						<?php 
    esc_html_e( 'Status', 'wpoptin' );
    ?>
                    </th>
                    <th>
						<?php 
    esc_html_e( 'Actions', 'wpoptin' );
    ?>
                    </th>
                </tr>
                </thead>
                <tbody>
				<?php 
    $i = null;
    if ( isset( $wpoptins ) ) {
        foreach ( $wpoptins as $wpoptin ) {
            $wop_goal = $wpoptin['goal']['0'];
            switch ( $wop_goal ) {
                case 'phone_calls':
                    $wpop_type_icon = 'phone';
                    $wpop_type_label = __( 'Phone Calls', 'wpoptin' );
                    break;
                case 'optin':
                    $wpop_type_icon = 'email';
                    $wpop_type_label = __( 'Collect emails', 'wpoptin' );
                    break;
                case 'announcement':
                    $wpop_type_icon = 'announcement';
                    $wpop_type_label = __( 'Announcement', 'wpoptin' );
                    break;
                case 'social_traffic':
                    $wpop_type_icon = 'comment';
                    $wpop_type_label = __( 'Social Traffic', 'wpoptin' );
                    break;
                case 'custom':
                    $wpop_type_icon = 'mode_edit';
                    $wpop_type_label = __( 'Custom', 'wpoptin' );
                    break;
                default:
                    $wpop_type_icon = 'timer';
                    $wpop_type_label = __( 'Sales, promotions and special offers', 'wpoptin' );
                    break;
            }
            // echo "<pre>"; print_r($wpoptins);exit();
            $i++;
            $type = $wpoptin['type'];
            /* Getting xoption demo page id. */
            $wpop_demopage_id = get_option( 'wpop_page_id', true );
            /* Getting permalink of wpopption demo page. */
            $wpop_url_c = get_permalink( $wpop_demopage_id );
            /* Encoding the url to enhance the awesomeness. */
            $wpop_url_c = urlencode( $wpop_url_c );
            $response_data = esc_url( admin_url( 'admin.php?page=wpop_new&wpop_optin_id=' . $wpoptin['ID'] . '&goal=' . $wpoptin['goal']['0'] . '&type=' . $type . '' ) );
            if ( isset( $wpoptin['account_id'] ) && empty($wpoptin['account_id']) && $wpoptin['goal']['0'] == 'optin' ) {
                $response_data .= '#accounts-panel';
            }
            
            if ( $wpoptin['has_varint'] ) {
                $has_varint = 1;
            } else {
                $has_varint = 0;
            }
            
            $views = $wpoptin['bar_views'];
            $conversions = '<i class="material-icons wpop-tooltipped" data-position="right" data-tooltip="' . __( 'Not available in your plan', 'wpoptin' ) . '">lock_outline</i>';
            $percent = '<i class="material-icons wpop-tooltipped" data-position="right" data-tooltip="' . __( 'Not available in your plan', 'wpoptin' ) . '">lock_outline</i>';
            if ( $wop_goal == 'announcement' || $wop_goal == 'social_traffic' ) {
                $conversions = "NA";
            }
            ?>
                        <tr class="row_<?php 
            echo  $wpoptin['ID'] ;
            
            if ( isset( $wpoptin['parent_id'] ) ) {
                ?>
                 xo_child_row xo_child_of_<?php 
                echo  $wpoptin['parent_id'] ;
                ?>
                 <?php 
            }
            
            ?>">
                            <td>
								<?php 
            
            if ( isset( $wpoptin['account_id'] ) && empty($wpoptin['account_id']) && $wpoptin['goal']['0'] == 'optin' ) {
                ?>
                                    <a href="#" id="xo_report<?php 
                echo  $i ;
                ?>"
                                       class="wpop-tooltipped wpop-account-deleted"
                                       data-position="bottom"
                                       data-tooltip="<?php 
                esc_html_e( 'Account Deleted', 'wpoptin' );
                ?>">
                                        <i class="material-icons dp48">error
                                        </i>
                                    </a>
								<?php 
            }
            
            ?>
								<?php 
            echo  $wpoptin['title'] ;
            ?>
                            </td>
                            <td>
                                <i class="material-icons wpop-tooltipped"
                                   data-position="right"
                                   data-tooltip="<?php 
            echo  $wpop_type_label ;
            ?>">
									<?php 
            echo  $wpop_type_icon ;
            ?>
                                </i>
                            </td>
                            <td>
								<?php 
            echo  $views ;
            ?>
                            </td>
                            <td>
								<?php 
            echo  $conversions ;
            ?>
                            </td>
                            <td>
								<?php 
            echo  $percent ;
            ?>
                            </td>
                            <td>
                                <div class="switch"
                                     for="xo_active_switch<?php 
            echo  $wpoptin['ID'] ;
            ?>">
                                    <label>
                                        <input type="checkbox"
											<?php 
            if ( isset( $wpoptin['account_id'] ) && empty($wpoptin['account_id']) && $wpoptin['goal']['0'] == 'optin' ) {
                ?>
                                                disabled
											<?php 
            }
            ?>
											<?php 
            echo  checked( 'active', $wpoptin['status'], false ) ;
            ?>
                                               id="xo_active_switch<?php 
            echo  $wpoptin['ID'] ;
            ?>"
                                               value="active"
                                               class="mdl-switch__input xo_status_switch"
                                               data-id="<?php 
            echo  $wpoptin['ID'] ;
            ?>"
                                               data-status="<?php 
            echo  $wpoptin['status'] ;
            ?>">
                                        <span class="lever">
              </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="action_holder">
									<?php 
            ?>

                                        <a href="#wpop-upgrade-ab-testing"
                                           class="wpop-tooltipped modal-trigger"
                                           data-position="left"
                                           data-tooltip="<?php 
            esc_html_e( 'A/B Testing', 'wpoptin' );
            ?>">
                                            <i class="material-icons">swap_vert
                                            </i>
                                        </a>

                                        <div id="wpop-upgrade-ab-testing"
                                             class="modal wpop-upgrade-modal">
                                            <div class="modal-content">
                                                <div class="wpop-modal-content">
                                                    <span class="wpop-lock-icon"><i
                                                                class="material-icons">lock_outline</i> </span>
                                                    <h5><?php 
            esc_html_e( "Premium Feature", 'wpoptin' );
            ?></h5>
                                                    <p><?php 
            esc_html_e( "We're sorry, A/B testing is not included in your plan. Please upgrade to the premium plan to unlock this and all other cool features. Keep increase conversion by 10x while testing new ideas.", 'wpoptin' );
            ?></p>
                                                    <hr>
                                                    <p><?php 
            esc_html_e( "Use following coupon to get 27% discount for limited time", 'wpoptin' );
            ?>
                                                        <br><code>BFCMOP</code>
                                                    </p>
                                                    <a href="<?php 
            echo  wpop_fs()->get_upgrade_url() ;
            ?>"
                                                       class="waves-effect waves-light btn z-depth-3"><i
                                                                class="material-icons right">lock_open</i><?php 
            esc_html_e( "Upgrade to pro", 'wpoptin' );
            ?>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>

									<?php 
            ?>

                                    <a href="<?php 
            echo  $response_data ;
            ?>"
                                       id="tt_edit<?php 
            echo  $wpoptin['ID'] ;
            ?>"
                                       class="wpop-tooltipped"
                                       data-position="bottom"
                                       data-tooltip="<?php 
            esc_html_e( 'Edit', 'wpoptin' );
            ?>">
                                        <i class="material-icons">edit
                                        </i>
                                    </a>
                                    <a href="javascript:void(0);"
                                       id="xo_remove<?php 
            echo  $wpoptin['ID'] ;
            ?>"
                                       data-varint_id="<?php 
            if ( isset( $wpoptin['varint_id'] ) ) {
                echo  $wpoptin['varint_id'] ;
            }
            ?>"
                                       data-remove_id="<?php 
            echo  $wpoptin['ID'] ;
            ?>"
                                       class="wpop_remove_db wpop-tooltipped"
                                       data-position="right"
                                       data-tooltip="<?php 
            esc_html_e( 'Delete', 'wpoptin' );
            ?>">
                                        <i class="material-icons">delete
                                        </i>
                                    </a>
                                </div>
                            </td>
                        </tr>

						<?php 
        }
    }
    ?>
                </tbody>
            </table>
        </div>
	<?php 
}

?>
    <div class="no_xo_optins">
        <div class="n_x_o_top">
            <div class="n_x_o_leftc">
                <h4>
					<?php 
esc_html_e( "Let’s get you subscribers, leads and engage the visitors with style", 'wpoptin' );
?>
                </h4>
                <p>
					<?php 
esc_html_e( "Congratulations! You’ve successfully installed WPoptin, hands-down the easiest and simplest to use email opt-in, leads generation and promotions WordPress plugin with offer bar (the scroll bar at top of your site).", 'wpoptin' );
?>
                </p>
                <p>
					<?php 
esc_html_e( "Cool? Let's start the business!", 'wpoptin' );
?>
                </p>
            </div>
            <div class="n_x_o_righticon">
                <img class="img_logo"
                     src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpoptin-logo.png">
            </div>
        </div>
        <div class="n_x_o_bottom">
            <div class="row wpop-goals-holder">
                <h4>
					<?php 
esc_html_e( "What’s your goal?", 'wpoptin' );
?>
                </h4>
                <div class="col s3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Collect emails", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Display optin form to collect emails to grow your subscribers list by 10x.", 'wpoptin' );
?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="optin">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col s3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Get Phone Calls", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Display call now button to get calls/leads by 10x than usual call now button.", 'wpoptin' );
?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="phone_calls">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col s3 wpop-goal-holder xo_goal_holder xo_offer_goal">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Sales, promotions and discount offers", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Promote the special offers on sales occasions like HalloWeen or New Year by giving discounts and perks.", 'wpoptin' );
?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="offer_bar">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Announcement", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Display important announcements and latest news.", 'wpoptin' );
?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="announcement">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wpop-goals-holder">
                <div class="col s3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Social Traffic", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Get 10x more likes for site page or Facebook fan page by displaying the Facebook like button.", 'wpoptin' );
?>
                            </p>
                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="social_traffic">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col s3 wpop-goal-holder">
                    <div class="card">
                        <div class="card-content">
                            <h5>
								<?php 
esc_html_e( "Custom", 'wpoptin' );
?>
                            </h5>
                            <p>
								<?php 
esc_html_e( "Not sure? Use our custom module according to your requirements and imagination.", 'wpoptin' );
?>
                            </p>

                            <a class="waves-effect waves-light btn wpop-select-goal"
                               data-goal="custom">
                                <i class="material-icons right">chevron_right
                                </i>
								<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Goals Html ends here-->
        <div class="wpop-types-holder row wpop-goals-holder">
            <h4>
				<?php 
esc_html_e( "Choose type", 'wpoptin' );
?>
            </h4>
            <div class="col s12 m3 wpop-goal-holder">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpop-bar-icon.svg">
                    </div>
                    <div class="card-content">
                        <h5>
							<?php 
esc_html_e( "Bar", 'wpoptin' );
?>
                        </h5>
                        <p>
							<?php 
esc_html_e( "A scrolling bar at the top of your site to increase conversion.", 'wpoptin' );
?>
                        </p>
                        <a class="waves-effect waves-light btn wpop-select-type"
                           data-href="<?php 
echo  esc_url( admin_url( 'admin.php?page=wpop_new&goal=' ) ) ;
?>"
                           data-type="bar">
                            <i class="material-icons right">chevron_right
                            </i>
							<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col s12 m3 wpop-goal-holder">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpop-popup-icon.svg">
                    </div>
                    <div class="card-content">
                        <h5>
							<?php 
esc_html_e( "Lightbox popup", 'wpoptin' );
?>
                        </h5>
                        <p>
							<?php 
esc_html_e( "A pop up or modal window in the center of your site. Very popular and very effective.", 'wpoptin' );
?>
                        </p>
                        <a class="waves-effect waves-light btn wpop-select-type"
                           data-href="<?php 
echo  esc_url( admin_url( 'admin.php?page=wpop_new&goal=' ) ) ;
?>"
                           data-type="popup">
                            <i class="material-icons right">chevron_right
                            </i>
							<?php 
esc_html_e( "Select", 'wpoptin' );
?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col s12 m3 wpop-goal-holder">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpop-slidein-icon.svg">
                    </div>
                    <div class="card-content">
                        <h5>
							<?php 
esc_html_e( "Slide In", 'wpoptin' );
?>
                        </h5>
                        <p>
							<?php 
esc_html_e( "Alternative to PopUp and less distracting but still highly converting slide in box in the corner of your site..", 'wpoptin' );
?>
                        </p>
                        <a class="waves-effect waves-light btn wpop-select-type-new"
                           data-href="<?php 
echo  admin_url( 'admin.php?page=wpop_new&goal=' ) ;
?>"
                           data-type="slide_in">
                            <i class="material-icons right">chevron_right
                            </i>
							<?php 
esc_html_e( 'Select', 'wpoptin' );
?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col s12 m3 wpop-goal-holder">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php 
echo  WPOP_URL ;
?>/assets/images/wpop-welcome-matt-icon.svg">
                    </div>
                    <div class="card-content">
                        <h5>
							<?php 
esc_html_e( "Wellcome Matt", 'wpoptin' );
?>
                        </h5>
                        <p>
							<?php 
esc_html_e( "A full site screen take over with your selected goal which can’t be ignored.", 'wpoptin' );
?>
                        </p>
                        <a class="waves-effect waves-light btn wpop-select-type-new"
                           data-href="<?php 
echo  admin_url( 'admin.php?page=wpop_new&goal=' ) ;
?>"
                           data-type="wellcome_matt">
                            <i class="material-icons right">chevron_right
                            </i>
							<?php 
esc_html_e( 'Select', 'wpoptin' );
?>
                        </a>
                    </div>
                </div>
            </div>
            <input type="hidden" id="wpop-selected-goal" value=""/>
        </div>
        <!-- Types Html ends here-->
    </div>
</div>

<?php 

if ( wpop_fs()->is_free_plan() ) {
    $banner_info = $this->wpop_upgrade_banner();
    
    if ( $banner_info ) {
        ?>

    <div class="wpoptin-upgrade-wrapper z-depth-2">
        <h2><?php 
        if ( isset( $banner_info['name'] ) ) {
            esc_html_e( $banner_info['name'] );
        }
        ?>
            <b><?php 
        if ( isset( $banner_info['bold'] ) ) {
            esc_html_e( $banner_info['bold'] );
        }
        ?></b></h2>
        <p><?php 
        if ( isset( $banner_info['description'] ) ) {
            esc_html_e( $banner_info['description'] );
        }
        ?></p>
        <p><?php 
        if ( isset( $banner_info['discount-text'] ) ) {
            esc_html_e( $banner_info['discount-text'] );
        }
        ?>
            <code><?php 
        if ( isset( $banner_info['coupon'] ) ) {
            esc_html_e( $banner_info['coupon'] );
        }
        ?></code>
        </p>
        <a href="<?php 
        echo  esc_url( wpop_fs()->get_upgrade_url() ) ;
        ?>"
           class="waves-effect waves-light btn"><i class="material-icons right">lock_open</i><?php 
        if ( isset( $banner_info['button-text'] ) ) {
            esc_html_e( $banner_info['button-text'] );
        }
        ?>
        </a>
    </div>

<?php 
    }

}
