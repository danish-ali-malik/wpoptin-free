<?php
/**
 * Admin View: Tab - Design
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

$current_link = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

/* Getting selected Skin ID. */
$selected_skin_id = $WPOptins->wpop_get_settings( $wpoptin_id, 'skin_id', false, false, $is_child );

// echo "<pre>"; print_r($selected_skin_id);exit();

/* Getting wpoptin demo page id. */
$wpop_demopage_id = get_option( 'wpop_page_id', false );

if ( ! isset( $wpoptin_id ) && ! isset( $goal ) ) {
	$design_dis = 'class="disabled  mdl-tabs__tab"';
}

/* Getting permalink of wpopption demo page. */
$wpop_url_c = get_permalink( $wpop_demopage_id );

$wpop_url_c = urlencode( $wpop_url_c );

$finalUrl = esc_url( admin_url( 'customize.php?wpop_optin_id=' . $wpoptin_id . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $goal . '&type=' . $type . '&wpop_skin_id=' . $selected_skin_id . '' ) );

global $wpoptins_skins;

if( isset( $selected_skin_id ) ){
	$wpoptin_skin = $wpoptins_skins[ $selected_skin_id ];
}else{
	$wpoptin_skin = [];
}


$feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';

?>
<div class="tab-content fadeIn" id="design-panel">
    <div id="xo_all_skins_wrap" class="xo_all_skins_wraper">
        <div class="row">
            <div class="card xo_skin_main_holder xo_offer_goal xo_skin_<?php echo $selected_skin_id ?> col s3 wpop_show_selected_label"
                 id="<?php echo $selected_skin_id ?>">
                <div class="card-image">
                    <img src="<?php echo $feat_html ?>">
                    <span class="selected_skin">
            <?php esc_html_e( "Selected", 'wpoptin' ); ?>
          </span>
                </div>
                <div class="card-content">
                    <h4>
						<?php echo $wpoptin_skin['title'] ?>
                    </h4>
                    <p>
						<?php echo $wpoptin_skin['description'] ?>
                    </p>
                </div>
                <div class="wpop-skin-actions">
                    <button type="button"
                            data-skin_id="<?php echo $selected_skin_id ?>"
                            class="btn waves-effect waves-light xo_skin_select disabled">
						<?php echo __( 'Select', 'wpoptin' ) ?>
                    </button>
                    <a href="<?php echo $finalUrl ?>"
                       class="btn waves-effect waves-light xo_customize_skin right">
						<?php esc_html_e( "Customize", 'wpoptin' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>