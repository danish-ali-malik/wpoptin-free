<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Admin Area Of WPOptin
//======================================================================

if ( !class_exists( 'WPOptins_Admin' ) ) {
    class WPOptins_Admin
    {
        /*
         * __construct initialize all function of this class.
         * Returns nothing.
         * Used action_hooks to get things sequentially.
         */
        function __construct()
        {
            add_action( 'admin_menu', [ $this, 'wpop_menu' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'wpop_admin_style' ] );
            add_action( 'admin_notices', [ $this, 'wpop_admin_notices' ] );
            add_action( 'wp_ajax_wpop_save_offer_bar', [ $this, 'wpop_save_offer_bar' ] );
            add_action( 'wp_ajax_wpop_save_phone_calls', [ $this, 'wpop_save_phone_calls' ] );
            add_action( 'wp_ajax_wpop_save_announcement', [ $this, 'wpop_save_announcement' ] );
            add_action( 'wp_ajax_wpop_save_social_traffic', [ $this, 'wpop_save_social_traffic' ] );
            add_action( 'wp_ajax_wpop_save_custom', [ $this, 'wpop_save_custom' ] );
            add_action( 'wp_ajax_wpop_save_optin', [ $this, 'wpop_save_optin' ] );
            add_action( 'wp_ajax_wpop_delete_entity', [ $this, 'wpop_delete_entity' ] );
            add_action( 'wp_ajax_wpop_save_triggers_and_conditions', [ $this, 'wpop_save_triggers_and_conditions' ] );
            add_action( 'wp_ajax_wpop_mad_mimi_authentication', [ $this, 'wpop_mad_mimi_authentication' ] );
            add_action( 'wp_ajax_xo_mailpoet_auth_save', [ $this, 'xo_mailpoet_auth_save__premium_only' ] );
            add_action( 'wp_ajax_xo_acount_data_page', [ $this, 'xo_acount_data_page_func' ] );
            add_action( 'wp_ajax_xo_get_accountLists', [ $this, 'xo_get_accountLists_func' ] );
            add_action( 'wp_ajax_wpop_get_account', [ $this, 'wpop_get_account' ] );
            add_action( 'wp_ajax_wpop_save_acounts_data', [ $this, 'wpop_save_acounts_data' ] );
            add_action( 'wp_ajax_xo_acount_data_page', [ $this, 'xo_acount_data_page_func' ] );
            add_action( 'wp_ajax_wpop_change_status', [ $this, 'wpop_change_status' ] );
            add_action( 'wp_ajax_wpop_remove_featured_img', [ $this, 'wpop_remove_featured_img' ] );
            add_action( 'wp_ajax_wpop_create_skin', [ $this, 'wpop_create_skin' ] );
            add_action( 'wp_ajax_wpop_delete_skin', [ $this, 'wpop_delete_skin' ] );
            add_action( 'wp_ajax_wpop_save_skin_id', [ $this, 'wpop_save_skin_id' ] );
            add_action( 'wp_ajax_wpop_customize_skin', [ $this, 'wpop_customize_skin' ] );
            add_action( 'wp_ajax_wpop_edit_skin', [ $this, 'wpop_edit_skin' ] );
            add_action( 'wp_ajax_wpop_supported', [ $this, 'wpop_supported_func' ] );
            add_action( 'wp_ajax_wpop_hide_campaign_notice', [ $this, 'wpop_hide_campaign_notice' ] );
            add_action( 'wp_ajax_wpop_hide_conversion_notice', [ $this, 'wpop_hide_conversion_notice' ] );
            add_action( 'wp_ajax_wpop_authenticate_access_token', [ $this, 'wpop_authenticate_access_token' ] );
            add_action( 'wp_ajax_wpop_publish_campaign', [ $this, 'publish_campaign' ] );
            add_action( 'admin_head', [ $this, 'wpop_hide_notices' ] );
            add_action( 'pre_get_posts', [ $this, 'wpop_exclude_demo_page' ] );
            add_action( 'wp', [ $this, 'wpop_redirect_demo_page_frontend' ] );
            add_filter( 'admin_footer_text', [ $this, '_wpop_admin_footer_text' ] );
            add_action( 'admin_footer', [ $this, '_wpop_admin_footer' ] );
        }
        
        public function wpop_hide_notices()
        {
            echo  "<script>function WPOPremoveURLParameter(url, parameter) {\r\n    //prefer to use l.search if you have a location/link object\r\n    var urlparts= url.split('?');   \r\n    if (urlparts.length>=2) {\r\n\r\n        var prefix= encodeURIComponent(parameter)+'=';\r\n        var pars= urlparts[1].split(/[&;]/g);\r\n\r\n        //reverse iteration as may be destructive\r\n        for (var i= pars.length; i-- > 0;) {    \r\n            //idiom for string.startsWith\r\n            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  \r\n                pars.splice(i, 1);\r\n            }\r\n        }\r\n\r\n        url= urlparts[0]+'?'+pars.join('&');\r\n        return url;\r\n    } else {\r\n        return url;\r\n    }\r\n} </script>" ;
            $screen = get_current_screen();
            if ( $screen->base == 'wpoptin_page_wpop_new' ) {
                remove_all_actions( 'admin_notices' );
            }
            /* Checks if access token exists in URL and save it*/
            $this->wpop_save_access_token();
            echo  "<style>.toplevel_page_wpoptin .wp-menu-image img{width: 19px;padding-top: 8px!important;}</style>" ;
        }
        
        /*
         * wpop_menu will add admin page.
         * Returns nothing.
         */
        public function wpop_menu()
        {
            /*
             * add_menu_page will add menu into the page.
             * string $page_title
             * string $menu_title
             * string $capability
             * string $menu_slug
             * callable $function
             */
            $icon_url = WPOP_URL . '/assets/images/wpop-menu-icon.png';
            add_menu_page(
                'WPOptin',
                'WPOptin',
                'administrator',
                'wpoptin',
                [ $this, 'wpop_page_cb' ],
                $icon_url
            );
            add_submenu_page(
                'wpoptin',
                'Add New',
                'Add New',
                'administrator',
                'wpop_new',
                [ $this, 'wpop_new_cb' ]
            );
            add_submenu_page(
                'wpoptin',
                'Analytics',
                'Analytics',
                'administrator',
                'wpop_overview',
                [ $this, 'wpop_overview_cb' ]
            );
            add_submenu_page(
                'wpoptin',
                'Accounts',
                'Accounts',
                'administrator',
                'wpop_accounts',
                [ $this, 'wpop_accounts_cb' ]
            );
            // add_submenu_page('wpoptin', 'Skins', 'Skins',  'administrator', 'wpop_skins', array($this,'wpop_skins_cb'));
        }
        
        /* wpop_menu Method ends here. */
        /*
         * wpop_page_cb contains the html/markup of the page.
         * Returns nothing.
         */
        public function wpop_page_cb()
        {
            /**
             * wpoptins page view.
             */
            include_once WPOP_DIR . 'admin/views/html-admin-page-wpoptins.php';
        }
        
        /*
         * wpop_new_cb contains the html/markup of the new page.
         * Returns nothing.
         */
        public function wpop_new_cb()
        {
            /**
             * wpoptins page view.
             */
            include_once WPOP_DIR . 'admin/views/html-admin-page-new.php';
        }
        
        /* wpop_new_cb Method ends here. */
        /*
         * xo_overview_cb starts here.
         * Return html of stats.
         */
        function wpop_overview_cb()
        {
            /**
             * Overview page view.
             */
            include_once WPOP_DIR . 'admin/views/html-admin-page-overview.php';
            wp_enqueue_script( 'wpoptin-admin' );
        }
        
        /*
         * wpop_accounts_cb starts here.
         * Return html of Accounts.
         */
        public function wpop_accounts_cb()
        {
            /**
             * Overview page view.
             */
            include_once WPOP_DIR . 'admin/views/html-admin-page-accounts.php';
        }
        
        /* xo_accounts_cb Method ends here. */
        /*
         * wpop_admin_style will enqueue style and js files.
         * Returns hook name of the current page in admin.
         * $hook will contain the hook name.
         */
        public function wpop_admin_style( $hook )
        {
            /*
             * Determine if it's our page then enqueue files.
             */
            
            if ( in_array( $hook, $this->wpop_pages_hooks() ) ) {
                /*
                 * Base css file for admin area.
                 */
                wp_enqueue_style(
                    'wpoptin-fonts',
                    WPOP_URL . '/assets/css/wpoptin-fonts.css',
                    [],
                    WPOP_VERSION
                );
                /*
                 * Base css file for admin area.
                 */
                wp_enqueue_style(
                    'materialize.min',
                    WPOP_URL . '/assets/css/materialize.min.css',
                    [],
                    WPOP_VERSION
                );
                /*
                 * Css file for admin area.
                 */
                wp_enqueue_style(
                    'wpoptin-admin',
                    WPOP_URL . '/assets/css/wpoptin-admin.css',
                    [],
                    WPOP_VERSION
                );
                /*
                 * Jquery File For Charts.
                 */
                wp_enqueue_script(
                    'wpoptin-chart',
                    WPOP_URL . '/assets/js/wpoptin-chart.js',
                    [ 'jquery' ],
                    WPOP_VERSION
                );
                /*
                 * Base script file for admin area.
                 */
                wp_enqueue_script(
                    'materialize.min',
                    WPOP_URL . '/assets/js/materialize.min.js',
                    [ 'jquery' ],
                    WPOP_VERSION
                );
                wp_enqueue_script( 'thickbox' );
                wp_enqueue_style( 'thickbox' );
                wp_enqueue_script( 'media-upload' );
                wp_enqueue_media();
            }
            
            /* If cond ends here. */
            
            if ( $hook == 'toplevel_page_wpoptin' or $hook == 'wpoptin_page_wpop_new' or $hook == 'wpoptin_page_wpop_accounts' or $hook == 'wpoptin_page_wpop_skins' ) {
                $returner = '';
                /*
                 * Custom scripts file for admin area.
                 */
                wp_enqueue_script(
                    'wpoptin-admin',
                    WPOP_URL . '/assets/js/wpoptin-admin.js',
                    [ 'jquery', 'materialize.min' ],
                    WPOP_VERSION
                );
                $wpop_demopage_id = get_option( 'wpop_page_id', true );
                /* Getting permalink of xoption demo page. */
                $wpop_url_c = get_permalink( $wpop_demopage_id );
                $current_link = urlencode( (( isset( $_SERVER['HTTPS'] ) ? "https" : "http" )) . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" );
                /* Encoding the url to enhance the awesomeness. */
                $wpop_url_c = urlencode( $wpop_url_c );
                $finalUrl = admin_url( 'customize.php?url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&return=' . $current_link . '' );
                /*
                 * Localizing script to get admin-ajax url dynamically.
                 */
                wp_localize_script( 'wpoptin-admin', 'wpoptin', [
                    'ajax_url'       => admin_url( 'admin-ajax.php' ),
                    'modal_data'     => $returner,
                    'customizer_url' => $finalUrl,
                    'logo_url'       => WPOP_URL . '/assets/images/wpoptin-logo.png',
                    'nonce'          => wp_create_nonce( 'wpop-ajax-nonce' ),
                    'feat_img_title' => __( 'Use this image', 'wpoptin' ),
                    'updating'       => __( 'Updating', 'wpoptin' ),
                    'publishing'     => __( 'Publishing', 'wpoptin' ),
                    'SavingAsDraft'  => __( 'Saving as draft', 'wpoptin' ),
                    'SwitchToDraft'  => __( 'Switch to draft', 'wpoptin' ),
                    'creating'       => __( 'Creating', 'wpoptin' ),
                    'deleting'       => __( 'Deleting', 'wpoptin' ),
                    'optional'       => __( 'optional', 'wpoptin' ),
                    'free_plan'      => wpop_fs()->is_free_plan(),
                ] );
            } elseif ( $hook == 'wpoptin_page_wpop_overview' ) {
                /*
                 * Custom scripts file for admin area.
                 */
                wp_register_script(
                    'wpoptin-admin',
                    WPOP_URL . '/assets/js/wpoptin-admin.js',
                    [ 'jquery', 'materialize.min' ],
                    WPOP_VERSION
                );
                /*
                 * Localizing script to get admin-ajax url dynamically.
                 */
                wp_localize_script( 'wpoptin-admin', 'wpoptin', [
                    'ajax_url'       => admin_url( 'admin-ajax.php' ),
                    'logo_url'       => WPOP_URL . '/assets/images/wpoptin-logo.png',
                    'nonce'          => wp_create_nonce( 'wpop-ajax-nonce' ),
                    'feat_img_title' => __( 'Choose featured image', 'wpoptin' ),
                ] );
            }
            
            /*
             * Css file for Customizer to make it fancy.
             */
            wp_enqueue_style(
                'wpoptin-customizer',
                WPOP_URL . '/assets/css/wpoptin-customizer.css',
                [],
                WPOP_VERSION
            );
        }
        
        /* wpop_admin_style Method ends here. */
        /*
         * wpop_get_account on ajax.
         * Return the .
         * Gets the exisiting accounts.
         */
        function wpop_get_account()
        {
            global  $WPOptins ;
            /* Getting service provider name. */
            $service_name = (string) sanitize_text_field( $_POST['service_provider'] );
            /*
             * Arguments for WP_Query().
             */
            $wpOptins = [
                'posts_per_page'         => '500',
                'post_type'              => 'wpop_accounts',
                'post_status'            => 'publish',
                'meta_key'               => '_wpop_service_provider',
                'meta_value'             => $service_name,
                'no_found_rows'          => false,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ];
            /*
             * Quering all active wpOptins.
             * WP_Query() object of wp will be used.
             */
            $wpOptins = new WP_Query( $wpOptins );
            /* If any wpOptins are in database. */
            
            if ( $wpOptins->have_posts() ) {
                $html = '';
                $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
						<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
						<select class="xo_account_name">';
                $html .= '<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                /* Looping xOptins to get all records. */
                while ( $wpOptins->have_posts() ) {
                    /* Making it post. */
                    $wpOptins->the_post();
                    $html .= '<option value="' . get_the_ID() . '" ' . selected( get_the_ID(), $WPOptins->wpop_get_settings( $wpoptin_id, 'account_id' ), false ) . '>' . get_the_title() . '</option>';
                }
                // end while
                $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
			</select></li>';
                //
                echo  wp_send_json_success( $html ) ;
                die;
                /* Reseting the current query. */
                wp_reset_postdata();
            } else {
                echo  wp_send_json_error() ;
                die;
            }
        
        }
        
        /* xo_get_account_func Method ends here. */
        /*
         * xo_get_accountLists_func on ajax.
         * Returns nothing.
         * Gets the exisiting account.
         */
        function xo_get_accountLists_func()
        {
            global  $WPOptins ;
            /* Getting service provider name. */
            $acount_id = (int) sanitize_text_field( $_POST['acount_id'] );
            /*
             * get_post() to get list specific account.
             */
            $data = get_post( $acount_id, 'ARRAY_A' );
            
            if ( isset( $data ) ) {
                $html = null;
                $data = unserialize( $data['post_content'] );
                $html .= '<li class="xo_account_list_li xo_account_list_li_' . $acount_id . '">
					  <label class="label">' . __( 'Select account lists', 'wpoptin' ) . '</label>
					  <select name="xo_account_list" class="xo_account_list">';
                $html .= '<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                foreach ( $data['lists'] as $key => $value ) {
                    $html .= '<option ' . selected( $key, $WPOptins->wpop_get_settings( $wpoptin_id, 'list_id' ), false ) . ' value="' . $key . '">' . $value . '</option>';
                }
                $html .= '</select>
					 </li>';
                echo  wp_send_json_success( $html ) ;
            } else {
                echo  wp_send_json_error() ;
                die;
            }
        
        }
        
        /* xo_get_accountLists_func Method ends here. */
        /*
         * xo_admin_notice contains html of admin notice.
         * Returns nothing.
         */
        function wpop_admin_notices()
        {
            /**
             * Admin notices view.
             */
            include_once WPOP_DIR . 'admin/views/html-admin-notices.php';
        }
        
        /*
         * wpop_supported_func on ajax.
         * Returns nothing.
         * Update the value on submit.
         */
        function wpop_supported_func()
        {
            /* Update the supported value into the db. */
            update_site_option( 'wpop_support', 'yes' );
            echo  json_encode( [ "success" ] ) ;
            exit;
        }
        
        /*
         * Hide the campaign notice
         */
        function wpop_hide_campaign_notice()
        {
            update_site_option( 'wpop_hide_campaign_notice', 'yes' );
            echo  json_encode( [ "success" ] ) ;
            exit;
        }
        
        /*
         * Hide the conversions notice
         */
        function wpop_hide_conversion_notice()
        {
            update_site_option( 'wpop_hide_conversion_notice', 'yes' );
            echo  json_encode( [ "success" ] ) ;
            exit;
        }
        
        /*
         * xo_save_offer_bar will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_offer_bar()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            $return_url = urlencode( $_POST['return_url'] );
            parse_str( $form_data );
            if ( empty($wpop_new_title) ) {
                $wpop_new_title = 'Offer';
            }
            $wpop_edit_id = (int) sanitize_text_field( $wpop_edit_id );
            
            if ( isset( $wpop_edit_id ) && !empty($wpop_edit_id) ) {
                $status = get_post_status( $wpop_edit_id );
            } else {
                $status = 'draft';
            }
            
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => htmlentities( sanitize_text_field( $wpop_new_content ) ),
                'post_type'    => 'wpoptins',
                'post_status'  => $status,
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_insert_post( $wpop_new_post );
                    }
                }
            }
            
            $wpop_new_timer = intval( strtotime( $wpop_new_timer ) );
            /* wp_insert_post() insers new post in db. */
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    $thumbnail_set = set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_text', sanitize_text_field( $wpop_new_offer_btn_text ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_url', esc_url_raw( $wpop_new_offer_btn_url ) );
                
                if ( !$wpop_edit_id ) {
                    $skin_title = $wpop_new_title . ' ' . __( 'Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for ', 'wpoptin' ) . $wpop_new_title;
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $wpop_new_goal . '&type=' . $wpop_new_type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                echo  wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    $wpop_skin_html,
                    $thumbnail_set
                ] ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /*
         * xo_save_offer_bar will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_phone_calls()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            $return_url = urlencode( $_POST['return_url'] );
            parse_str( $form_data );
            if ( empty($wpop_new_title) ) {
                $wpop_new_title = __( 'Phone Calls', 'wpoptin' );
            }
            $wpop_edit_id = (int) sanitize_text_field( $wpop_edit_id );
            
            if ( isset( $wpop_edit_id ) && !empty($wpop_edit_id) ) {
                $status = get_post_status( $wpop_edit_id );
            } else {
                $status = 'draft';
            }
            
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => htmlentities( sanitize_text_field( $wpop_new_content ) ),
                'post_type'    => 'wpoptins',
                'post_status'  => $status,
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_insert_post( $wpop_new_post );
                    }
                }
            }
            
            /* wp_insert_post() insers new post in db. */
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    $thumbnail_set = set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, '_wpop_country_code', sanitize_text_field( $wpop_country_code ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_text', sanitize_text_field( $wpop_new_offer_btn_text ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_url', $wpop_new_offer_btn_url );
                
                if ( !$wpop_edit_id ) {
                    $skin_title = $wpop_new_title . ' ' . __( 'Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for ', 'wpoptin' ) . $wpop_new_title;
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $wpop_new_goal . '&type=' . $wpop_new_type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    $wpop_skin_html,
                    $thumbnail_set
                ] );
            } else {
                wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) );
            }
        
        }
        
        /*
         * xo_save_offer_bar will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_announcement()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            $return_url = urlencode( $_POST['return_url'] );
            parse_str( $form_data );
            if ( empty($wpop_new_title) ) {
                $wpop_new_title = __( 'Announcement', 'wpoptin' );
            }
            $wpop_edit_id = (int) sanitize_text_field( $wpop_edit_id );
            
            if ( isset( $wpop_edit_id ) && !empty($wpop_edit_id) ) {
                $status = get_post_status( $wpop_edit_id );
            } else {
                $status = 'draft';
            }
            
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => htmlentities( sanitize_text_field( $wpop_new_content ) ),
                'post_type'    => 'wpoptins',
                'post_status'  => $status,
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_insert_post( $wpop_new_post );
                    }
                }
            }
            
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    $thumbnail_set = set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, '_wpop_enable_cupon', sanitize_text_field( $wpop_enable_cupon ) );
                update_post_meta( $wpop_new_postID, '_wpop_new_cupon', sanitize_text_field( $wpop_new_cupon ) );
                update_post_meta( $wpop_new_postID, '_wpop_new_timer_text', sanitize_text_field( $wpop_new_timer_text ) );
                update_post_meta( $wpop_new_postID, '_wpop_timer', sanitize_text_field( $wpop_new_timer ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_text', sanitize_text_field( $wpop_new_offer_btn_text ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_url', esc_url_raw( $wpop_new_offer_btn_url ) );
                
                if ( !$wpop_edit_id ) {
                    $skin_title = $wpop_new_title . ' ' . __( 'Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for ', 'wpoptin' ) . $wpop_new_title;
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $wpop_new_goal . '&type=' . $wpop_new_type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                echo  wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    $wpop_skin_html,
                    $thumbnail_set
                ] ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /*
         * xo_save_offer_bar will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_social_traffic()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            $return_url = urlencode( $_POST['return_url'] );
            parse_str( $form_data );
            if ( empty($wpop_new_title) ) {
                $wpop_new_title = __( 'Social Traffic', 'wpoptin' );
            }
            $wpop_edit_id = (int) sanitize_text_field( $wpop_edit_id );
            
            if ( isset( $wpop_edit_id ) && !empty($wpop_edit_id) ) {
                $status = get_post_status( $wpop_edit_id );
            } else {
                $status = 'draft';
            }
            
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => htmlentities( sanitize_text_field( $wpop_new_content ) ),
                'post_type'    => 'wpoptins',
                'post_status'  => $status,
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_insert_post( $wpop_new_post );
                    }
                }
            }
            
            /* wp_insert_post() insers new post in db. */
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    $thumbnail_set = set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, '_wpop_url_type', sanitize_text_field( $wpop_url_type ) );
                update_post_meta( $wpop_new_postID, '_wpop_social_url', esc_url_raw( $wpop_new_social_url ) );
                
                if ( !$wpop_edit_id ) {
                    $skin_title = $wpop_new_title . ' ' . __( 'Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for ', 'wpoptin' ) . $wpop_new_title;
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $wpop_new_goal . '&type=' . $wpop_new_type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                echo  wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    $wpop_skin_html,
                    $thumbnail_set
                ] ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /*
         * xo_save_offer_bar will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_custom()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            $return_url = urlencode( $_POST['return_url'] );
            parse_str( $form_data );
            if ( empty($wpop_new_title) ) {
                $wpop_new_title = __( 'Custom', 'wpoptin' );
            }
            $wpop_edit_id = (int) sanitize_text_field( $wpop_edit_id );
            
            if ( isset( $wpop_edit_id ) && !empty($wpop_edit_id) ) {
                $status = get_post_status( $wpop_edit_id );
            } else {
                $status = 'draft';
            }
            
            $wpop_new_content = wp_kses( $wpop_new_content, wp_kses_allowed_html( 'post' ) );
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => $wpop_new_content,
                'post_type'    => 'wpoptins',
                'post_status'  => $status,
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_insert_post( $wpop_new_post );
                    }
                }
            }
            
            $wpop_new_timer = intval( strtotime( $wpop_new_timer ) );
            /* wp_insert_post() insers new post in db. */
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    $thumbnail_set = set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, '_wpop_enable_content', sanitize_text_field( $wpop_enable_content ) );
                update_post_meta( $wpop_new_postID, '_wpop_enable_button', sanitize_text_field( $wpop_enable_button ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_text', sanitize_text_field( $wpop_new_offer_btn_text ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_offer_btn_url', esc_url_raw( $wpop_new_offer_btn_url ) );
                
                if ( !$wpop_edit_id ) {
                    $skin_title = $wpop_new_title . ' ' . __( 'Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for ', 'wpoptin' ) . $wpop_new_title;
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $wpop_new_goal . '&type=' . $wpop_new_type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                echo  wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    $wpop_skin_html,
                    $thumbnail_set
                ] ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /*
         * wpop_save_triggers_and_conditions will add tnc in db.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_triggers_and_conditions()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            parse_str( $form_data );
            /* Arguments for new post. */
            $wpop_post_cond = [
                'wpop_trigger_method'    => sanitize_text_field( $wpop_trigger_method ),
                'wpop_auto_method'       => sanitize_text_field( $wpop_auto_method ),
                'wpop_sec_value'         => $wpop_sec_value,
                'wpop_scroll_method'     => sanitize_text_field( $wpop_scroll_method ),
                'wpop_scrol_perc_value'  => $wpop_scrol_perc_value,
                'wpop_scrol_slect_value' => $wpop_scrol_slect_value,
                'wpop_click_class'       => $wpop_click_class,
                'wpop_pages_method'      => sanitize_text_field( $wpop_pages_method ),
                'wpop_posts_method'      => sanitize_text_field( $wpop_posts_method ),
                'wpop_tags_method'       => sanitize_text_field( $wpop_tags_method ),
                'wpop_cats_method'       => sanitize_text_field( $wpop_cats_method ),
                'wpop_pages_show_name'   => $wpop_pages_show_name,
                'wpop_posts_show_name'   => $wpop_posts_show_name,
                'wpop_tags_show_name'    => $wpop_tags_show_name,
                'wpop_cats_show_name'    => $wpop_cats_show_name,
                'wpop_pages_hide_name'   => $wpop_pages_hide_name,
                'wpop_posts_hide_name'   => $wpop_posts_hide_name,
                'wpop_tags_hide_name'    => $wpop_tags_hide_name,
                'wpop_cats_hide_name'    => $wpop_cats_hide_name,
                'wpop_show_all'          => sanitize_text_field( $wpop_show_all ),
                'wpop_show_home'         => sanitize_text_field( $wpop_show_home ),
            ];
            /* Serializing data and saving. */
            $wpop_post_cond = maybe_serialize( $wpop_post_cond );
            if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                    $id = update_post_meta( $xo_new_id, '_wpop_conditions', $wpop_post_cond );
                }
            }
            
            if ( isset( $id ) ) {
                echo  wp_send_json_success( __( 'Saved', 'wpoptin' ) ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* wpop_save_triggers_and_conditions Method ends here. */
        /*
         * xo_save_optin will add goal in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_optin()
        {
            /* Getting service provider name. */
            $form_data = $_POST['form_data'];
            parse_str( $form_data );
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => sanitize_text_field( $wpop_new_title ),
                'post_content' => sanitize_text_field( $wpop_new_content ),
                'post_type'    => 'wpoptins',
                'post_status'  => 'draft',
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $wpop_edit_id ) {
                $wpop_new_post['ID'] = $wpop_edit_id;
                $wpop_new_postID = wp_update_post( $wpop_new_post );
            } else {
                $wpop_new_postID = wp_insert_post( $wpop_new_post );
            }
            
            $wpop_new_timer = intval( strtotime( $wpop_new_timer ) );
            
            if ( isset( $wpop_new_postID ) ) {
                
                if ( filter_var( $wpop_feat_img, FILTER_VALIDATE_URL ) ) {
                    $thumbnail_id = wpop_get_image_id( $wpop_feat_img );
                    set_post_thumbnail( $wpop_new_postID, $thumbnail_id );
                } else {
                    delete_post_thumbnail( $wpop_new_postID );
                }
                
                update_post_meta( $wpop_new_postID, '_wpop_goal', sanitize_text_field( $wpop_new_goal ) );
                update_post_meta( $wpop_new_postID, '_wpop_type', sanitize_text_field( $wpop_new_type ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_newsletter_placeholder', sanitize_text_field( $wpop_new_newsletter_placeholder ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_newsletter_btn_text', sanitize_text_field( $wpop_new_newsletter_btn_text ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_newsletter_sucess_msg', sanitize_text_field( $wpop_new_newsletter_sucess_msg ) );
                update_post_meta( $wpop_new_postID, 'wpop_new_newsletter_error_msg', sanitize_text_field( $wpop_new_newsletter_error_msg ) );
                wp_send_json_success( [
                    __( 'Saved', 'wpoptin' ),
                    $wpop_new_postID,
                    '',
                    $thumbnail_set
                ] );
            } else {
                wp_send_json_error( __( 'Something went wrong! Please try again' ) );
            }
        
        }
        
        /* xo_save_optin Method ends here. */
        /*
         * wpop_save_acounts_data will add acounts data in database.
         * Returns nothing.
         * Add the value on click.
         */
        function wpop_save_acounts_data()
        {
            /* Saving ajax value in variable. */
            $acount_id = (int) sanitize_text_field( $_POST['acount_id'] );
            $list_id = str_replace( "/\\s+/", '', $_POST['list_id'] );
            $account_provider = (string) sanitize_text_field( $_POST['account_provider'] );
            $edit_id = (int) sanitize_text_field( $_POST['edit_id'] );
            $goal = (string) sanitize_text_field( $_POST['goal'] );
            $type = (string) sanitize_text_field( $_POST['type'] );
            /* Arguments for new post. */
            $wpop_new_post = [
                'post_title'   => 'Optin',
                'post_content' => __( 'Join our mailing list to stay up to date on our upcoming events', 'wpoptin' ),
                'post_type'    => 'wpoptins',
                'post_status'  => 'draft',
                'post_author'  => get_current_user_id(),
            ];
            
            if ( $edit_id ) {
                $wpop_new_post['ID'] = $edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            } else {
                $wpop_new_post['ID'] = $edit_id;
                if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                    if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                        $wpop_new_postID = wp_update_post( $wpop_new_post );
                    }
                }
            }
            
            /* update_post_meta() insers meta values in post db. */
            $goal_meta = update_post_meta( $wpop_new_postID, '_wpop_goal', $goal );
            $type_meta = update_post_meta( $wpop_new_postID, '_wpop_type', $type );
            $meta_key = update_post_meta( $wpop_new_postID, '_wpop_acount_id', $acount_id );
            $meta_key2 = update_post_meta( $wpop_new_postID, '_wpop_list_id', $list_id );
            $meta_key3 = update_post_meta( $wpop_new_postID, '_wpop_service_provider', $account_provider );
            
            if ( isset(
                $meta_key,
                $meta_key2,
                $meta_key3,
                $goal_meta
            ) ) {
                
                if ( !$edit_id ) {
                    $skin_title = __( 'Optin Design', 'wpoptin' );
                    $skin_description = __( 'Default design settings for optin', 'wpoptin' );
                    $skin_data = [
                        'post_title'   => $skin_title,
                        'post_content' => $skin_description,
                        'post_type'    => 'wpop_skins',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    if ( wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                        if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                            // Update the skin into the database
                            $skin_id = wp_insert_post( $skin_data );
                        }
                    }
                    $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    $meta_id = update_post_meta(
                        $wpop_new_postID,
                        '_wpop_skin_id',
                        $skin_id,
                        false
                    );
                    /* Getting xoption demo page id. */
                    $wpop_demopage_id = get_option( 'wpop_page_id', true );
                    /* Getting permalink of xoption demo page. */
                    $wpop_url_c = get_permalink( $wpop_demopage_id );
                    /* Encoding the url to enhance the awesomeness. */
                    $wpop_url_c = urlencode( $wpop_url_c );
                    $wpop_skin_url = admin_url( 'customize.php?wpop_optin_id=' . $wpop_new_postID . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $goal . '&type=' . $type . '&wpop_skin_id=' . $skin_id . '' );
                    if ( $skin_id ) {
                        $wpop_skin_html .= '<div class="card xo_skin_main_holder xo_offer_goal xo_skin_' . $skin_id . ' col s3 wpop_show_selected_label" id="' . $skin_id . '">
					       <div class="card-image">
					         <img src="' . $feat_html . '">
					         <span class="selected_skin">' . __( 'Selected', 'wpoptin' ) . '</span>
					       </div>
					       <div class="card-content">
					         <h4>' . $skin_title . '</h4>
					         <p>' . $skin_description . '</p>
					       </div>
					        <div class="wpop-skin-actions">

							   <button type="button" data-skin_id="' . $skin_id . '" class="btn waves-effect waves-light xo_skin_select disabled">' . __( 'Select', 'wpoptin' ) . '</button>
							   <a href="' . $wpop_skin_url . '"  class="btn waves-effect waves-light xo_customize_skin right">' . __( 'Customize', 'wpoptin' ) . '</a>
							 </div>
					     </div>';
                    }
                }
                
                echo  wp_send_json_success( [ $wpop_new_postID, __( 'Saved', 'wpoptin' ), $wpop_skin_html ] ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* xo_save_acounts_data Method ends here. */
        /*
         * wpop_mad_mimi_authentication will authenticate mad_mimi and save the values in db.
         * Returns lists.
         */
        function wpop_mad_mimi_authentication()
        {
            /* Saving ajax value in variable. */
            $acount_name = $_POST['acount_name'];
            $api_key = $_POST['api_key'];
            $username = $_POST['username'];
            $service_provider = $_POST['service_provider'];
            $post_id = $_POST['post_id'];
            $request_url = esc_url_raw( 'https://api.madmimi.com/audience_lists/lists.json?username=' . rawurlencode( $username ) . '&api_key=' . $api_key );
            $theme_request = wp_remote_get( $request_url, [
                'timeout' => 30,
            ] );
            $response_code = wp_remote_retrieve_response_code( $theme_request );
            
            if ( !is_wp_error( $theme_request ) && $response_code == 200 ) {
                $theme_response = json_decode( wp_remote_retrieve_body( $theme_request ), true );
                
                if ( !empty($theme_response) ) {
                    wp_update_post( [
                        'ID'          => $post_id,
                        'post_status' => 'publish',
                    ] );
                    $lists_array = [];
                    foreach ( $theme_response as $list ) {
                        $id = $list['id'];
                        $name = $list['name'];
                        $lists_array['lists'][$id] = $name;
                    }
                    $api_key = base64_encode( $api_key );
                    $username = base64_encode( $username );
                    $lists_array['api_key'] = $api_key;
                    $lists_array['username'] = $username;
                    $lists_array = serialize( $lists_array );
                    /* Arguments for new account. */
                    $wpop_new_account = [
                        'post_title'   => $acount_name,
                        'post_content' => $lists_array,
                        'post_type'    => 'wpop_accounts',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    /* wp_insert_post() insers new post in db. */
                    $wpop_new_accountID = wp_insert_post( $wpop_new_account );
                    update_post_meta( $wpop_new_accountID, '_wpop_service_provider', $service_provider );
                    /*
                     * Arguments for WP_Query().
                     */
                    $wpOptins = [
                        'posts_per_page'         => '500',
                        'post_type'              => 'wpop_accounts',
                        'post_status'            => 'publish',
                        'meta_key'               => '_wpop_service_provider',
                        'meta_value'             => $service_provider,
                        'no_found_rows'          => false,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                    ];
                    /*
                     * Quering all active wpOptins.
                     * WP_Query() object of wp will be used.
                     */
                    $wpOptins = new WP_Query( $wpOptins );
                    /* If any xOptins are in database. */
                    
                    if ( $wpOptins->have_posts() ) {
                        $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
								<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
								<select class="xo_account_name">
								<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                        /* Looping xOptins to get all records. */
                        while ( $wpOptins->have_posts() ) {
                            /* Making it post. */
                            $wpOptins->the_post();
                            $html .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                        }
                        // end while
                        $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
					</select></li>';
                        /* Reseting the current query. */
                        wp_reset_postdata();
                    }
                    
                    echo  wp_send_json_success( [ $html, $wpop_new_accountID, __( 'Successfully authenticated!' ) ] ) ;
                    die;
                }
            
            } else {
                echo  wp_send_json_error( wp_remote_retrieve_body( $theme_request ) ) ;
                die;
            }
        
        }
        
        /* xo_mailpoet_auth_save Method ends here. */
        /*
         * xo_acount_data_page_func will authenticate mailchimp.
         * Returns nothing.
         */
        function xo_acount_data_page_func()
        {
            /* Saving ajax value in variable. */
            $acount_name = (int) sanitize_text_field( $_POST['acount_name'] );
            $api_key = sanitize_key( $_POST['api_key'] );
            $post_name = (string) sanitize_text_field( $_POST['post_name'] );
            $account_id = (int) sanitize_text_field( $_POST['acount_id'] );
            $api = new MCAPI( $api_key );
            $retval = $api->get_lists();
            
            if ( empty($retval) ) {
                echo  wp_send_json_error( $api->api_response['1'] ) ;
                die;
            } else {
                
                if ( $retval->total_items == 0 ) {
                    echo  wp_send_json_error( __( 'No Lists Found. Please Add one in your MailChimp Account', 'wpoptin' ) ) ;
                    die;
                }
                
                $lists_array = [];
                if ( isset( $retval->lists ) ) {
                    foreach ( $retval->lists as $list ) {
                        $id = $list->id;
                        $name = $list->name;
                        $lists_array['lists'][$id] = $name;
                    }
                }
                $api_key = base64_encode( $api_key );
                $lists_array['api_key'] = $api_key;
                $lists_array = serialize( $lists_array );
                /* Arguments for new account. */
                $wpop_new_account = [
                    'post_title'   => $acount_name,
                    'post_content' => $lists_array,
                    'post_type'    => 'wpop_accounts',
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                ];
                
                if ( $account_id ) {
                    $wpop_new_account['ID'] = $account_id;
                    $wpop_new_accountID = wp_update_post( $wpop_new_account );
                } else {
                    /* wp_insert_post() insers new post in db. */
                    $wpop_new_accountID = wp_insert_post( $wpop_new_account );
                }
                
                update_post_meta( $wpop_new_accountID, '_wpop_service_provider', $post_name );
                /* Getting service provider name. */
                $service_name = $_POST['post_name'];
                /*
                 * Arguments for WP_Query().
                 */
                $wpOptins = [
                    'posts_per_page'         => '500',
                    'post_type'              => 'wpop_accounts',
                    'post_status'            => 'publish',
                    'meta_key'               => '_wpop_service_provider',
                    'meta_value'             => $service_name,
                    'no_found_rows'          => false,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                ];
                /*
                 * Quering all active wpOptins.
                 * WP_Query() object of wp will be used.
                 */
                $wpOptins = new WP_Query( $wpOptins );
                /* If any wpOptins are in database. */
                
                if ( $wpOptins->have_posts() ) {
                    $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
						<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
						<select class="xo_account_name">
						<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                    /* Looping wpOptins to get all records. */
                    while ( $wpOptins->have_posts() ) {
                        /* Making it post. */
                        $wpOptins->the_post();
                        $html .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                    }
                    // end while
                    $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
			</select></li>';
                    /* Reseting the current query. */
                    wp_reset_postdata();
                }
            
            }
            
            // echo '<pre><br>';
            // 		print_r($data_holder);
            // exit();
            echo  wp_send_json_success( [ $html, $wpop_new_accountID ] ) ;
            die;
        }
        
        /* xo_save_goal_func Method ends here. */
        /*
         * wpop_delete_entity will delete offer.
         * Returns nothing.
         */
        function wpop_delete_entity()
        {
            $remove_id = (int) sanitize_text_field( $_POST['remove_id'] );
            /* Delete specific offer from db. */
            $true_c = delete_option( 'wpop_data_' . $remove_id );
            
            if ( $_POST['child_id'] ) {
                delete_option( 'wpop_data_' . $_POST['child_id'] );
                wp_delete_post( $_POST['child_id'], true );
            }
            
            /* Delete specific offer from db. */
            $true_p = wp_delete_post( $_POST['remove_id'], true );
            
            if ( $true_c or $true_p ) {
                $data = [
                    'parent_id'    => $_POST['remove_id'],
                    'child_id'     => $_POST['child_id'],
                    'notification' => __( 'Deleted', 'wpoptin' ),
                ];
                wp_send_json_success( $data );
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
            }
            
            die;
        }
        
        /* xo_del_offer_func Method ends here. */
        /*
         * wpop_change_status will change status offer.
         * Returns nothing.
         */
        function wpop_change_status()
        {
            $post = [
                'ID'          => $_POST['id'],
                'post_status' => $_POST['status'],
            ];
            $updated = wp_update_post( $post );
            
            if ( $updated ) {
                echo  wp_send_json_success( __( 'Successfully Updated', 'wpoptin' ) ) ;
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong.', 'wpoptin' ) ) ;
            }
            
            wp_die();
        }
        
        /*
         * wpop_remove_featured_img will remove the attached featured image.
         */
        function wpop_remove_featured_img()
        {
            if ( !wp_verify_nonce( $_POST['wpop_nonce'], 'wpop-ajax-nonce' ) ) {
                if ( !current_user_can( 'editor' ) || !current_user_can( 'administrator' ) ) {
                    echo  wp_send_json_error( esc_html__( 'Nonce not verified! Reload the page and try again', 'wpoptin' ) ) ;
                }
            }
            $deleted = delete_post_thumbnail( $_POST['id'] );
            
            if ( $deleted ) {
                echo  wp_send_json_success( __( 'Successfully Deleted', 'wpoptin' ) ) ;
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong.', 'wpoptin' ) ) ;
            }
            
            wp_die();
        }
        
        /* wpop_add_variation__premium_only Method ends here. */
        /*
         * wpop_create_skin will update skin data.
         * Add the value on click.
         */
        function wpop_create_skin()
        {
            $form_data = $_POST['form_data'];
            parse_str( $form_data );
            $attachment_id = 0;
            $dir = wp_upload_dir();
            
            if ( false !== strpos( $wpop_skin_feat_img, $dir['baseurl'] . '/' ) ) {
                // Is URL in uploads directory?
                $file = basename( $wpop_skin_feat_img );
                $query_args = [
                    'post_type'   => 'attachment',
                    'post_status' => 'inherit',
                    'fields'      => 'ids',
                    'meta_query'  => [ [
                    'value'   => $file,
                    'compare' => 'LIKE',
                    'key'     => '_wp_attachment_metadata',
                ] ],
                ];
                $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                    foreach ( $query->posts as $post_id ) {
                        $meta = wp_get_attachment_metadata( $post_id );
                        $original_file = basename( $meta['file'] );
                        $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                        
                        if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                            $attachment_id = $post_id;
                            break;
                        }
                    
                    }
                }
            }
            
            if ( empty($wpop_skin_description) ) {
                $wpop_skin_description = __( 'Default skin for wpoptins', 'wpoptin' );
            }
            // Update skin
            $skin_data = [
                'post_title'   => $wpop_new_skin_title,
                'post_content' => $wpop_skin_description,
                'post_type'    => 'wpop_skins',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
            ];
            // Update the skin into the database
            $skin_id = wp_insert_post( $skin_data );
            $thumb_id = set_post_thumbnail( $skin_id, $attachment_id );
            
            if ( isset( $skin_id, $thumb_id ) ) {
                echo  wp_send_json_success( __( 'Saved', 'wpoptin' ) ) ;
                die;
            } else {
                echo  wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* wpop_create_skin_data Method ends here. */
        /*
         * wpop_save_skin_id will add skin id to optin/offer.
         * Add the value on click.
         */
        function wpop_save_skin_id()
        {
            /* Getting variables. */
            $skin_id = $_POST['skin_id'];
            $id = $_POST['id'];
            $meta_id = update_post_meta(
                $id,
                '_wpop_skin_id',
                $skin_id,
                false
            );
            
            if ( isset( $meta_id ) ) {
                echo  wp_send_json_success( esc_html__( 'Updated', 'wpoptin' ) ) ;
                die;
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong.', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* wpop_save_skin_id Method ends here. */
        /*
         * wpop_customize_skin will add skin id to optin/offer and send it to customizer for customization.
         * Add the value on click.
         */
        function wpop_customize_skin()
        {
            /* Getting variables. */
            $skin_id = $_POST['skin_id'];
            $id = $_POST['id'];
            $return_url = urlencode( $_POST['return_url'] );
            $meta_id = update_post_meta(
                $id,
                '_wpop_skin_id',
                $skin_id,
                false
            );
            
            if ( isset( $meta_id ) ) {
                /* Getting xoption demo page id. */
                $wpop_demopage_id = get_option( 'wpop_page_id', true );
                $goal = get_post_meta( $id, '_wpop_goal', true );
                $goal = get_post_meta( $id, '_wpop_type', true );
                /* Getting permalink of xoption demo page. */
                $wpop_url_c = get_permalink( $wpop_demopage_id );
                /* Encoding the url to enhance the awesomeness. */
                $wpop_url_c = urlencode( $wpop_url_c );
                $response_data = admin_url( 'customize.php?wpop_optin_id=' . $id . '&url=' . $wpop_url_c . '&autofocus[panel]=wpop_customizer_panel&goal=' . $goal . '&wpop_skin_id=' . $skin_id . '&return=' . $return_url . '' );
                echo  wp_send_json_success( $response_data ) ;
                die;
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong! Please try again', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* wpop_customize_skin Method ends here. */
        /*
         * wpop_edit_skin will edit the skin.
         * Add the value on click.
         */
        function wpop_edit_skin()
        {
            /* Getting variables. */
            $skin_id = $_POST['skin_id'];
            global  $wpoptins_skins ;
            
            if ( $wpoptins_skins[$skin_id] ) {
                $arraySkin = [
                    'title'       => $wpoptins_skins[$skin_id]['title'],
                    'description' => $wpoptins_skins[$skin_id]['description'],
                    'feat_img'    => $wpoptins_skins[$skin_id]['featured_img'],
                    'skin_for'    => $wpoptins_skins[$skin_id]['skin_for_name'],
                    'id'          => $skin_id,
                ];
                echo  wp_send_json_success( $arraySkin ) ;
                die;
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong.', 'wpoptin' ) ) ;
                die;
            }
        
        }
        
        /* wpop_edit_skin Method ends here. */
        /*
         * xo_skins_cb starts here.
         * Return html of Accounts.
         */
        public function wpop_skins_cb()
        {
            global  $wpoptins_skins ;
            $wpop_new_skin_form_html = null;
            $wpop_new_skin_form_html .= sprintf(
                '<div id="xo_new_skin_wrap" class="mdl-cell--12-col xo_new_skin_wraper">
												<div class="xo_new_skin_holder">
						<div id="xo_new_skin_form_wraper" class="mdl-cell--12-col xo_new_skin_form_wraperr">
						<form class="xo_new_skin_form">

							<div class="input-field col s12">
								<i class="material-icons prefix">title</i>
								<input id="wpop_new_skin_title" type="text" value="" name="wpop_new_skin_title">
								<label for="wpop_new_skin_title">%1$s</label>
							</div>

						  	
						  	<div class="input-field col s12">
						  		<i class="material-icons prefix">description</i>
								<textarea id="wpop_skin_description" class="materialize-textarea" name="wpop_skin_description"></textarea>
          						<label for="wpop_skin_description">%2$s</label>
							</div>


							 <div class="input-field col s12 wpop_skin_feat_img_wrap">
							 	<div class="wpop-skin-feat-warp">
								 	<i class="material-icons prefix">image</i>
								 	<input id="wpop_skin_feat_img" type="text" value="" name="wpop_skin_feat_img">
								</div>	
								<label for="wpop_skin_feat_img">%3$s</label>	
									<i class="btn waves-effect waves-light waves-input-wrapper wpop-skin-feat-btn-warp">
									<input type="button" class="xo_submit" value="%4$s" id="wpop_skin_feat_img_btn"/>
									<i class="material-icons left">file_upload</i>
									</i>

							  </div>
							  <br />

						<span class="description">%5$s</span>

						<input name="xo_new_skin_sub" data-snc="false" type="submit" class="xo_new_skin_sub xo_submit btn waves-effect waves-light" value="%6$s">

						</form>
				</div>
				</div>
				</div>',
                /* Variables starts here. */
                __( 'Title', 'wpoptin' ),
                __( 'Description', 'wpoptin' ),
                __( 'Skin Image', 'wpoptin' ),
                __( 'Upload Image', 'wpoptin' ),
                __( 'Upload the skin image', 'wpoptin' ),
                __( 'Save', 'wpoptin' ),
                __( 'Save & continue', 'wpoptin' )
            );
            $wpop_new_skin_form_html = apply_filters( 'wpop_new_skin_form_html', $wpop_new_skin_form_html );
            // echo "<pre>"; print_r($set_cat);exit();
            $all_skins_loop = null;
            $i = null;
            $feat_html = null;
            if ( $wpoptins_skins ) {
                foreach ( $wpoptins_skins as $wpoptin_skin ) {
                    
                    if ( isset( $wpoptin_skin['featured_img'] ) && !empty($wpoptin_skin['featured_img']) ) {
                        $feat_html = $wpoptin_skin['featured_img'];
                    } else {
                        $feat_html = WPOP_URL . '/assets/images/skin-placeholder.jpg';
                    }
                    
                    $all_skins_loop .= sprintf(
                        '<div class="xo_skin_main_holder card col s3 xo_offer_goal xo_skin_%1$s" id="%1$s">
		
							<div class="card-image">
					          <img src="%2$s">
					        </div>

					         <div class="card-content">
					          <h4>%3$s</h4>
					          <p>%4$s</p>
					        </div>
					         <div class="wpop-skin-actions">

							    <button type="button" data-skin_id="%1$s" class="btn waves-effect waves-light xo_edit_skin"> %6$s</button>
							    <button type="button" data-remove_skin_id="%1$s" class="btn waves-effect waves-light xo_del_skin right"> %5$s</button>
							  </div>
							</div>',
                        /* Variables starts here. */
                        $wpoptin_skin['ID'],
                        $feat_html,
                        $wpoptin_skin['title'],
                        $wpoptin_skin['description'],
                        __( 'Delete', 'wpoptin' ),
                        __( 'Edit', 'wpoptin' )
                    );
                    # code...
                }
            }
            /*
             * ALL skins html.
             * wpop_all_skins_html filter can be used to customize all skins html.
             */
            $wpop_all_skins_html = null;
            $wpop_all_skins_html .= sprintf(
                '<div id="xo_all_skins_wrap" class="mdl-cell--12-col xo_all_skins_wraper">
					%1$s	
				</div>',
                /* Variables starts here. */
                $all_skins_loop
            );
            $wpop_all_skins_html = apply_filters( 'wpop_all_skins_html', $wpop_all_skins_html );
            /*
             * Base html.
             * wpop_skins_filter filter can be used to customize the html of skins page.
             */
            $returner = null;
            $returner .= sprintf(
                '<div class="xo_wrap z-depth-1">
			<span class="xo_loader_holder"><img class="loader_img fadeIn" src="' . WPOP_URL . '/assets/images/wpoptin-logo.png"></span>
				<div class="xo_header fadeIn">
					<div class="xo_sliders_wrap">
					<img src="' . WPOP_URL . '/assets/images/wpop-menu-icon.png"></span>

					 	  <div class="xo-addnew-right">
					 	  <a href="' . admin_url( 'admin.php?page=wpop_overview' ) . '" id="xo_stats" class="xo_stats wpop-tooltipped" data-position="bottom" data-tooltip="%1$s">		
							<i class="material-icons">insert_chart</i>
						</a>

						 <a href="' . admin_url( 'admin.php?page=wpop_accounts' ) . '" id="xo_accounts" class="xo_accounts wpop-tooltipped" data-position="bottom" data-tooltip="%5$s">
							<i class="material-icons">account_circle</i>
						</a>

					<a href="javascript:void(0);" id="xo_skin_add" class="ox-add-skin wpop-tooltipped" data-position="bottom" data-tooltip="%2$s">
					<i class="material-icons">add</i></i>
					</a>
					<a href="' . admin_url( 'admin.php?page=wpop_skins' ) . '" title="Close" class="xo-close wpop-tooltipped" data-position="bottom" data-tooltip="%6$s">
						<i class="material-icons">close</i>
					 </a>

				</div>	 
				</div>
				<div class="xo-settings-genral-wrapper">
				<!-- All skins html starts here. -->
				  %3$s
				<!-- All skins html ends here. -->
				<!-- Add new skins html starts here. -->
				 %4$s
				<!-- Add new skins html ends here. -->
				',
                /* Variables starts here. */
                __( 'Analytics', 'wpoptin' ),
                __( 'Add New', 'wpoptin' ),
                $wpop_all_skins_html,
                $wpop_new_skin_form_html,
                __( 'Accounts', 'wpoptin' ),
                __( 'Close', 'wpoptin' )
            );
            $returner = apply_filters( 'wpop_skins_filter', $returner );
            echo  $returner ;
        }
        
        /* xo_skins_cb Method ends here. */
        /*
         * wpop_delete_skin will delete skin.
         * Returns nothing.
         */
        function wpop_delete_skin()
        {
            /* Delete specific skin data from option. */
            $true_c = delete_option( 'wpop_data_' . $_POST['remove_id'] );
            /* Delete specific offer from db. */
            $true_p = wp_delete_post( $_POST['remove_id'], true );
            
            if ( isset( $true_p ) ) {
                wp_send_json_success( [ $_POST['remove_id'], __( 'Deleted', 'wpoptin' ) ] );
            } else {
                echo  wp_send_json_error( esc_html__( 'Something went wrong.', 'wpoptin' ) ) ;
            }
            
            die;
        }
        
        /* wpop_delete_skin Method ends here. */
        /*
         * xo_pages_hooks starts here.
         * Return the hooks names of wpoptin pages.
         */
        public function wpop_pages_hooks()
        {
            $hooks = [
                'toplevel_page_wpoptin',
                'wpoptin_page_wpop_new',
                'wpoptin_page_wpop_overview',
                'wpoptin_page_wpop_accounts',
                'wpoptin_page_wpop_skins'
            ];
            return apply_filters( 'wpop_pages_hooks', $hooks );
        }
        
        /* xo_pages_hooks Method ends here. */
        /*
         * wpop_save_access_token saves access token
         */
        private function wpop_save_access_token()
        {
            
            if ( isset( $_GET['wpop_access_token'] ) && !empty($_GET['wpop_access_token']) ) {
                $access_token = $_GET['wpop_access_token'];
                $service = sanitize_text_field( $_GET['service'] );
                
                if ( $service == 'campaign_monitor' ) {
                    $access_token = urldecode( $access_token );
                    $refresh_access_token = urldecode( $_GET['wpop_refresh_access_token'] );
                }
                
                $client_id = sanitize_text_field( $_GET['client_id'] );
                $username = sanitize_text_field( $_GET['username'] );
                
                if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
                    $script = ' <script>
				    jQuery( document ).ready(function( $ ) {

			
				    	$(".xo_account_provider").val("' . $service . '").trigger("change");

				    	$(".xo_account_provider").formSelect();

				    	M.Toast.dismissAll();

	  					M.toast({html: "Please wait! Authenticating...", displayLength : 50000000});

	  					var url      = window.location.href; 

	  					url = WPOPremoveURLParameter(url, "wpop_access_token");

						url = WPOPremoveURLParameter(url, "service");

				       var data={\'action\':\'wpop_authenticate_access_token\',
				       			\'access_token\':\'' . $access_token . '\',
				       			\'refresh_access_token\':\'' . $refresh_access_token . '\',
				       			\'service\' :\'' . $service . '\',
				       			\'client_id\' :\'' . $client_id . '\',
				       			\'username\' :\'' . $username . '\',
				   				}

			       jQuery.ajax({
				        
				        url: "' . admin_url( 'admin-ajax.php' ) . '",
				        type: "post",
				        data: data,
				        dataType: "json",
				        success: function(response ) {
				        	window.history.pushState("newurl", "newurl", url);

							M.Toast.dismissAll();
				            if (response.success) {

				            	M.toast({html: response.data[2]});

								$(".xo_lists_holder").html("");

								$(".xo_lists_holder").append(response.data[0]);
								$(".xo_account_name").val(response.data[1]).trigger("change");
								$(".xo_account_add_holder").slideUp("slow");
								$(".xo_sub_holder").slideDown("slow");
								$(".xo_account_provider").val("' . $service . '").trigger("change");
								$(".xo_account_name").formSelect();
								$(".xo_account_list").formSelect();

								$(".xo_optin_accounts .xo_sub_holder").slideDown();
								
				            }else{
				            	M.toast({html: response.data});
				            }
				      	  }
				         });
				      
				    
				    });
		  	  </script>';
                    echo  $script ;
                }
            
            }
        
        }
        
        /* wpop_save_access_token Method ends here. */
        /*
         * Authenticate access token
         */
        function wpop_authenticate_access_token()
        {
            $access_token = $_POST['access_token'];
            $refresh_access_token = $_POST['refresh_access_token'];
            $service_name = sanitize_text_field( $_POST['service'] );
            $client_id = sanitize_text_field( $_POST['client_id'] );
            $username = sanitize_text_field( $_POST['username'] );
            switch ( $service_name ) {
                case 'mailchimp':
                    /* Getting meta data of user like data center */
                    $wpop_mc_meta_raw = wp_remote_get( "https://login.mailchimp.com/oauth2/metadata?=", [
                        'headers' => [
                        'Authorization' => 'OAuth ' . $access_token,
                        'Accept'        => 'application/json',
                    ],
                    ] );
                    $wpop_mc_meta = wp_remote_retrieve_body( $wpop_mc_meta_raw );
                    if ( wp_remote_retrieve_response_code( $wpop_mc_meta_raw ) != 200 ) {
                        wp_send_json_error( esc_html__( wp_remote_retrieve_response_message( $wpop_mc_meta_raw ) ) );
                    }
                    $wpop_mc_meta = json_decode( $wpop_mc_meta );
                    if ( !$wpop_mc_meta ) {
                        wp_send_json_error( esc_html__( 'Unable to find valid json response', 'wpoptin' ) );
                    }
                    /* Getting lists of MailChimp Account */
                    $api_key = $access_token . "-" . $wpop_mc_meta->dc;
                    $wpop_mc_lists_api_url = $wpop_mc_meta->api_endpoint . "/3.0/lists?apikey=" . $api_key;
                    $wpop_mc_lists_raw = wp_remote_get( $wpop_mc_lists_api_url );
                    $wpop_mc_lists = json_decode( wp_remote_retrieve_body( $wpop_mc_lists_raw ) );
                    
                    if ( !isset( $wpop_mc_lists->lists ) ) {
                        echo  wp_send_json_error( esc_html__( 'No Lists Found. Please Add one in your MailChimp Account', 'wpoptin' ) ) ;
                        wp_die();
                    }
                    
                    $lists_array = [];
                    if ( $wpop_mc_lists->lists ) {
                        foreach ( $wpop_mc_lists->lists as $list ) {
                            $id = $list->id;
                            $name = $list->name;
                            $lists_array['lists'][$id] = $name;
                        }
                    }
                    $api_key = base64_encode( $api_key );
                    $lists_array['api_key'] = $api_key;
                    $lists_array = serialize( $lists_array );
                    /* Arguments for new account. */
                    $wpop_new_account = [
                        'post_title'   => $wpop_mc_meta->accountname,
                        'post_content' => $lists_array,
                        'post_type'    => 'wpop_accounts',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    /* wp_insert_post() insers new post in db. */
                    $wpop_new_accountID = wp_insert_post( $wpop_new_account );
                    update_post_meta( $wpop_new_accountID, '_wpop_service_provider', $service_name );
                    /*
                     * Arguments for WP_Query().
                     */
                    $wpOptins = [
                        'posts_per_page'         => '500',
                        'post_type'              => 'wpop_accounts',
                        'post_status'            => 'publish',
                        'meta_key'               => '_wpop_service_provider',
                        'meta_value'             => $service_name,
                        'no_found_rows'          => false,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                    ];
                    /*
                     * Quering all active wpOptins.
                     * WP_Query() object of wp will be used.
                     */
                    $wpOptins = new WP_Query( $wpOptins );
                    /* If any xOptins are in database. */
                    
                    if ( $wpOptins->have_posts() ) {
                        $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
							<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
							<select class="xo_account_name">
							<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                        /* Looping xOptins to get all records. */
                        while ( $wpOptins->have_posts() ) {
                            /* Making it post. */
                            $wpOptins->the_post();
                            $html .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                        }
                        // end while
                        $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
				</select></li>';
                        /* Reseting the current query. */
                        wp_reset_postdata();
                    }
                    
                    echo  wp_send_json_success( [ $html, $wpop_new_accountID, esc_html__( 'Successfully authenticated!' ) ] ) ;
                    wp_die();
                    break;
                case 'constant_contact':
                    $api_url = esc_url_raw( 'https://api.constantcontact.com/v2/lists?api_key=' . $client_id );
                    $requested_content = wp_remote_get( $api_url, [
                        'timeout' => 30,
                        'headers' => [
                        'Authorization' => 'Bearer ' . $access_token,
                    ],
                    ] );
                    $response_code = wp_remote_retrieve_response_code( $requested_content );
                    
                    if ( !is_wp_error( $requested_content ) && $response_code == 200 ) {
                        $responsed_data = wp_remote_retrieve_body( $requested_content );
                        $wpop_cc_lists = json_decode( $responsed_data, true );
                    }
                    
                    
                    if ( !$wpop_cc_lists ) {
                        echo  wp_send_json_error( esc_html__( 'No Lists Found. Please Add one in your Constant Constant Account', 'wpoptin' ) ) ;
                        wp_die();
                    }
                    
                    $lists_array = [];
                    if ( $wpop_cc_lists ) {
                        foreach ( $wpop_cc_lists as $list ) {
                            $id = $list['id'];
                            $name = $list['name'];
                            $lists_array['lists'][$id] = $name;
                            $contact_count = $list['contact_count'];
                        }
                    }
                    $api_key = base64_encode( $client_id );
                    $access_token = base64_encode( $access_token );
                    $lists_array['api_key'] = $client_id;
                    $lists_array['access_token'] = $access_token;
                    $lists_array = serialize( $lists_array );
                    /* Arguments for new account. */
                    $wpop_new_account = [
                        'post_title'   => $username,
                        'post_content' => $lists_array,
                        'post_type'    => 'wpop_accounts',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    /* wp_insert_post() insers new post in db. */
                    $wpop_new_accountID = wp_insert_post( $wpop_new_account );
                    update_post_meta( $wpop_new_accountID, '_wpop_service_provider', $service_name );
                    /*
                     * Arguments for WP_Query().
                     */
                    $wpOptins = [
                        'posts_per_page'         => '500',
                        'post_type'              => 'wpop_accounts',
                        'post_status'            => 'publish',
                        'meta_key'               => '_wpop_service_provider',
                        'meta_value'             => $service_name,
                        'no_found_rows'          => false,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                    ];
                    /*
                     * Quering all active wpOptins.
                     * WP_Query() object of wp will be used.
                     */
                    $wpOptins = new WP_Query( $wpOptins );
                    /* If any xOptins are in database. */
                    
                    if ( $wpOptins->have_posts() ) {
                        $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
							<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
							<select class="xo_account_name">
							<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                        /* Looping xOptins to get all records. */
                        while ( $wpOptins->have_posts() ) {
                            /* Making it post. */
                            $wpOptins->the_post();
                            $html .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                        }
                        // end while
                        $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
				</select></li>';
                        /* Reseting the current query. */
                        wp_reset_postdata();
                    }
                    
                    wp_send_json_success( [ $html, $wpop_new_accountID, esc_html__( 'Successfully authenticated!' ) ] );
                    break;
                case 'campaign_monitor':
                    $all_clients_id = [];
                    $all_lists = [];
                    $lists_array = [];
                    $auth = [
                        'access_token'  => $access_token,
                        'refresh_token' => $refresh_access_token,
                    ];
                    $wrap = new CS_REST_General( $auth );
                    $result = $wrap->get_clients();
                    
                    if ( !$result->was_successful() ) {
                        # If you receive '121: Expired OAuth Token', refresh the access token
                        
                        if ( $result->response->Code == 121 ) {
                            list( $new_access_token, $new_expires_in, $new_refresh_token ) = $wrap->refresh_token();
                            # Save $new_access_token, $new_expires_in, and $new_refresh_token
                        }
                        
                        # Make the call again
                        $result = $wrap->get_clients();
                    }
                    
                    if ( $result->response ) {
                        foreach ( $result->response as $client => $client_details ) {
                            $all_clients_id[] = $client_details->ClientID;
                        }
                    }
                    
                    if ( !$result->response ) {
                        wp_send_json_error( esc_html__( 'No Lists Found. Please Add one in your Campaign Monitor Account', 'wpoptin' ) );
                        wp_die();
                    }
                    
                    if ( !empty($all_clients_id) ) {
                        foreach ( $all_clients_id as $client ) {
                            $wrap = new CS_REST_Clients( $client, $auth );
                            $lists_data = $wrap->get_lists();
                            foreach ( $lists_data->response as $list ) {
                                $id = $list->ListID;
                                $name = $list->Name;
                                $lists_array['lists'][$id] = $name;
                            }
                        }
                    }
                    $access_token = base64_encode( $access_token );
                    $refresh_access_token = base64_encode( $refresh_access_token );
                    $lists_array['api_key'] = $access_token;
                    $lists_array['refresh_access_token'] = $refresh_access_token;
                    $username = $result->response[0]->Name;
                    $lists_array = serialize( $lists_array );
                    /* Arguments for new account. */
                    $wpop_new_account = [
                        'post_title'   => $username,
                        'post_content' => $lists_array,
                        'post_type'    => 'wpop_accounts',
                        'post_status'  => 'publish',
                        'post_author'  => get_current_user_id(),
                    ];
                    /* wp_insert_post() insers new post in db. */
                    $wpop_new_accountID = wp_insert_post( $wpop_new_account );
                    update_post_meta( $wpop_new_accountID, '_wpop_service_provider', $service_name );
                    /*
                     * Arguments for WP_Query().
                     */
                    $wpOptins = [
                        'posts_per_page'         => '500',
                        'post_type'              => 'wpop_accounts',
                        'post_status'            => 'publish',
                        'meta_key'               => '_wpop_service_provider',
                        'meta_value'             => $service_name,
                        'no_found_rows'          => false,
                        'update_post_meta_cache' => false,
                        'update_post_term_cache' => false,
                    ];
                    /*
                     * Quering all active wpOptins.
                     * WP_Query() object of wp will be used.
                     */
                    $wpOptins = new WP_Query( $wpOptins );
                    /* If any xOptins are in database. */
                    
                    if ( $wpOptins->have_posts() ) {
                        $html .= '<li class="xo_accounts_li xo_accounts_li_' . $service_name . '">
							<label class="label">' . __( 'Select account name', 'wpoptin' ) . '</label>
							<select class="xo_account_name">
							<option value="0">' . __( '--Select one--', 'wpoptin' ) . '</option>';
                        /* Looping xOptins to get all records. */
                        while ( $wpOptins->have_posts() ) {
                            /* Making it post. */
                            $wpOptins->the_post();
                            $html .= '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                        }
                        // end while
                        $html .= '<option value="new">' . __( 'Add account', 'wpoptin' ) . '</option>
				</select></li>';
                        /* Reseting the current query. */
                        wp_reset_postdata();
                    }
                    
                    wp_send_json_success( [ $html, $wpop_new_accountID, esc_html__( 'Successfully authenticated!' ) ] );
                    break;
            }
        }
        
        /*
         * Exclude demo page from admin listing
         */
        public function wpop_exclude_demo_page( $query )
        {
            
            if ( is_admin() && $query->is_main_query() ) {
                $wpop_demo_page_id = get_option( 'wpop_page_id', false );
                $query->set( 'post__not_in', [ $wpop_demo_page_id ] );
            }
        
        }
        
        /*
         * Redirect to home if on demo page
         */
        public function wpop_redirect_demo_page_frontend()
        {
            $wpop_demo_page_id = get_option( 'wpop_page_id', false );
            if ( is_customize_preview() ) {
                return;
            }
            
            if ( is_page( $wpop_demo_page_id ) ) {
                wp_redirect( site_url() );
                exit;
            }
        
        }
        
        public function _wpop_admin_footer_text( $text )
        {
            $screen = get_current_screen();
            $arr = [
                'toplevel_page_wpoptin',
                'wpoptin_page_wpop_overview',
                'wpoptin_page_wpop_accounts',
                'wpoptin_page_wpoptin-account',
                'wpoptin_page_wpoptin-contact',
                'wpoptin_page_wpoptin-pricing'
            ];
            
            if ( in_array( $screen->id, $arr ) ) {
                $WPOPTIN = new WPOPTIN();
                $text = '<i><a href="' . admin_url( '?page=wpoptin' ) . '" title="' . __( 'Visit WPOptin page for more info', 'wpoptin' ) . '">WPOptin</a> v' . $WPOPTIN->version . '. Please <a target="_blank" href="https://wordpress.org/support/plugin/wpoptin/reviews/?filter=5#new-post" title="Rate the plugin">rate the plugin <span style="color: #ffb900;" class="stars">&#9733; &#9733; &#9733; &#9733; &#9733; </span></a> to help us spread the word. Thank you from the WPOptin team!</i><div style="margin-left:5px;top: 1px;" class="fb-like" data-href="https://www.facebook.com/wpoptin" data-width="" data-layout="button" data-action="like" data-size="small" data-share="false"></div>';
            }
            
            return $text;
            // echo "<pre>"; print_r($screen);exit();
        }
        
        public function _wpop_admin_footer()
        {
            $screen = get_current_screen();
            $arr = [
                'toplevel_page_wpoptin',
                'wpoptin_page_wpop_overview',
                'wpoptin_page_wpop_accounts',
                'wpoptin_page_wpoptin-account',
                'wpoptin_page_wpoptin-contact',
                'wpoptin_page_wpoptin-pricing'
            ];
            if ( in_array( $screen->id, $arr ) ) {
                echo  '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v6.0&appId=1983264355330375&autoLogAppEvents=1"></script><style>#wpfooter{background-color: #fff;padding: 15px 20px;-webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.12), 0 1px 5px 0 rgba(0,0,0,.2);box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.12), 0 1px 5px 0 rgba(0,0,0,.2);}.fb_iframe_widget{float:left;}#wpfooter a{text-decoration:none;}</style>' ;
            }
        }
        
        /*
         * Return documentation url by goal.
         */
        function wpop_doc_url( $goal = 'optin' )
        {
            $wpop_doc_url = '';
            switch ( $goal ) {
                case 'optin':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-opt-in' );
                    break;
                case 'phone_calls':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-phone-calls-banner' );
                    break;
                case 'offer_bar':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-sales-promotions-and-special-offers' );
                    break;
                case 'announcement':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-announcement' );
                    break;
                case 'social_traffic':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-social-traffic' );
                    break;
                case 'custom':
                    $wpop_doc_url = esc_url( 'https://wpxoptin.com/documentation/how-to-create-sales-promotions-and-special-offers' );
                    break;
            }
            return $wpop_doc_url;
        }
        
        /**
         * Get upgrade banner info from main site
         * @return mixed|string[]
         */
        private function wpop_upgrade_banner()
        {
            if ( !wpop_fs()->is_free_plan() ) {
                return false;
            }
            $banner_info = array(
                'name'          => 'WPOptin',
                'bold'          => 'PRO',
                'description'   => 'Unlock all premium features such as Advanced Conditions to display campagins on specific pages and for specific users, Disable campaign when the time runs out, Background Image, Analytics and insights, A/B testing, Timer, Views and conversion counter, Inline HTML and Shortcode, Coupon, Smart like option, No branding and above all top notch priority support.',
                'discount-text' => 'Upgrade today and get a 27% exclusive discount! On the checkout click on "Have a promotional code?" and enter',
                'coupon'        => 'BFCMOP',
                'button-text'   => 'Upgrade Now',
            );
            return $banner_info;
        }
        
        /**
         * Include Premium Modal view
         * @param $id
         * @param $title
         * @param $description
         * @return Html
         * @since 1.2.0
         */
        private function wpop_get_premium_modal_html( $id, $title, $description )
        {
            if ( !$id || !wpop_fs()->is_free_plan() ) {
                return false;
            }
            $wpop_banner_info = $this->wpop_upgrade_banner();
            include WPOP_DIR . 'admin/views/html-premium-modal.php';
        }
        
        /**
         * Publish campaign on publish button click
         *
         * @since 1.2.0
         */
        public function publish_campaign()
        {
            // If nonce is invalid or not verified, Send error message.
            if ( !isset( $_POST['wpop_nonce'] ) && !wp_verify_nonce( $_POST['wpop_nonce'] ) ) {
                wp_send_json_error( __( 'Nonce not verified! Please try again.', 'wpoptin' ) );
            }
            $id = (int) intval( $_POST['id'] );
            
            if ( isset( $id ) && !empty($id) ) {
                $status = sanitize_text_field( $_POST['status'] );
                
                if ( $status == 'publish' ) {
                    $status = 'draft';
                    $success_message = __( 'Saved as draft', 'wpoptin' );
                    $button_text = __( 'Publish', 'wpoptin' );
                } else {
                    $status = 'publish';
                    $success_message = __( 'Published', 'wpoptin' );
                    $button_text = __( 'Switch to draft', 'wpoptin' );
                }
                
                $updated = wp_update_post( [
                    'ID'          => $id,
                    'post_status' => $status,
                ] );
                
                if ( $updated ) {
                    wp_send_json_success( [
                        'message'    => $success_message,
                        'buttonText' => $button_text,
                    ] );
                } else {
                    wp_send_json_error( __( 'Something went wrong! Please try again', 'wpoptin' ) );
                }
            
            } else {
                wp_send_json_error( __( 'Invalid ID', 'wpoptin' ) );
            }
        
        }
    
    }
    new WPOptins_Admin();
}
