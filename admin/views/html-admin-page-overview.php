<?php

/**
 * Admin View: Page - Overview
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
global  $wpoptins ;
global  $wpoptins_accounts ;
global  $WPOptins ;
$i = 10;
$total_conversions = wp_rand( 1, 200 );
$total_views = wp_rand( $total_conversions, 300 );
$percent = $total_conversions / $total_views;
$percent = number_format( $percent * 100, 2 );
$percent = round( $percent );
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

?>
            <a href="<?php 
echo  esc_url( admin_url( 'admin.php?page=wpop_new' ) ) ;
?>"
               class="ox-add-new wpop-tooltipped" data-position="bottom"
               data-tooltip="<?php 
esc_html_e( "Add New", 'wpoptin' );
?>">
                <i class="material-icons">add
                </i>
            </a>
        </div>
    </div>
    <div class="xo-settings-genral-wrapper">
        <div class="xo_stats_wrap">
            <h2>
				<?php 
esc_html_e( "Overview", 'wpoptin' );
?>
                <i class="fa fa-list xo_right" aria-hidden="true">
                </i>
            </h2>
            <div class="xo_stats_overview_sec row">
				<?php 

if ( wpop_fs()->is_free_plan() ) {
    ?>
                    <div class="wpop-content-locked">
                        <a href="#wpop-upgrade-analytics" class="modal-trigger">
                            <i class="material-icons">lock_outline</i> </a>
                    </div>

                    <div id="wpop-upgrade-analytics"
                         class="modal wpop-upgrade-modal">
                        <div class="modal-content">
                            <div class="wpop-modal-content">
                                <span class="wpop-lock-icon"><i
                                            class="material-icons">lock_outline</i> </span>
                                <h5><?php 
    esc_html_e( "Premium Feature", 'wpoptin' );
    ?></h5>
                                <p><?php 
    esc_html_e( "We're sorry, Analytics is not included in your plan. Please upgrade to the premium plan to unlock this and to make data-driven decisions in order to increase subscribers and sales by 10x.", 'wpoptin' );
    ?></p>
                                <hr>
                                <p><?php 
    esc_html_e( "Use following coupon to get 27% discount for limited time", 'wpoptin' );
    ?>
                                    <br><code>BFCMOP</code></p>
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
}

?>
                <div class="xo_stats_overview_inner xo_stats_overview_active col s3">
                    <div id="overview_active" class="donut-size"
                         data-chart_val="100">
                        <div class="pie-wrapper">
              <span class="label">
                <span class="num">
                  <?php 
echo  $i + $total_active_child ;
?>
                </span>
              </span>
                            <div class="pie">
                                <div class="left-side half-circle">
                                </div>
                                <div class="right-side half-circle">
                                </div>
                            </div>
                            <div class="shadow">
                            </div>
                        </div>
                    </div>
                    <span class="conv_title">
            <?php 
esc_html_e( "Active", 'wpoptin' );
?>
          </span>
                </div>
                <div class="xo_stats_overview_inner xo_stats_overview_views col s3">
                    <div id="overview_active" class="donut-size"
                         data-chart_val="100">
                        <div class="pie-wrapper">
              <span class="label">
                <span class="num">
                  <?php 
echo  $total_views ;
?>
                </span>
              </span>
                            <div class="pie">
                                <div class="left-side half-circle">
                                </div>
                                <div class="right-side half-circle">
                                </div>
                            </div>
                            <div class="shadow">
                            </div>
                        </div>
                    </div>
                    <span class="conv_title">
            <?php 
esc_html_e( "Total Views", 'wpoptin' );
?>
          </span>
                </div>
                <div class="xo_stats_overview_inner xo_stats_overview_conv col s3">
                    <div id="overview_active" class="donut-size"
                         data-chart_val="100">
                        <div class="pie-wrapper">
              <span class="label">
                <span class="num">
                  <?php 
echo  $total_conversions ;
?>
                </span>
              </span>
                            <div class="pie">
                                <div class="left-side half-circle">
                                </div>
                                <div class="right-side half-circle">
                                </div>
                            </div>
                            <div class="shadow">
                            </div>
                        </div>
                    </div>
                    <span class="conv_title">
            <?php 
esc_html_e( "Total Conversions", 'wpoptin' );
?>
          </span>
                </div>
                <div class="xo_stats_overview_inner xo_stats_overview_perc col s3">
                    <div id="overview_perc" class="donut-size"
                         data-chart_val="<?php 
echo  $percent ;
?>">
                        <div class="pie-wrapper">
              <span class="label">
                <span class="num">
                  <?php 
echo  $percent ;
?>
                </span>
                <span class="smaller">%
                </span>
              </span>
                            <div class="pie">
                                <div class="left-side half-circle">
                                </div>
                                <div class="right-side half-circle">
                                </div>
                            </div>
                            <div class="shadow">
                            </div>
                        </div>
                    </div>
                    <span class="conv_title">
            <?php 
esc_html_e( "Total Percentage", 'wpoptin' );
?>
          </span>
                </div>
            </div>
			<?php 

if ( $i > 0 ) {
    ?>
                <div class="xo_chart_sec mdl-cell mdl-cell--12-col mdl-cell--12-col-desktop">
                    <h2>
						<?php 
    esc_html_e( "Graphical Representation", 'wpoptin' );
    ?>
                        <i class="fa fa-bar-chart xo_right" aria-hidden="true">
                        </i>
                    </h2>
                    <div id="xo_chart_holder">

						<?php 
    
    if ( wpop_fs()->is_free_plan() ) {
        ?>
                            <div class="wpop-content-locked">
                                <a href="#wpop-upgrade-analytics"
                                   class="modal-trigger"> <i
                                            class="material-icons">lock_outline</i>
                                </a>
                            </div>
                            <img src="<?php 
        echo  WPOP_URL ;
        ?>/assets/images/wpop-free-graph.png">
						<?php 
    }
    
    ?>
                    </div>
                </div>
			<?php 
}

?>
        </div>
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
