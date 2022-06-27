<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Frontend of xOptins
//======================================================================

if ( !class_exists( 'WPOptins_Frontend' ) ) {
    class WPOptins_Frontend
    {
        /*
         * __construct initialize all function of this class.
         * Returns nothing. 
         * Used action_hooks to get things sequentially.
         */
        function __construct()
        {
            /*
             * wp_enqueue_scripts hook will include scripts in wp.
             */
            add_action( 'wp_enqueue_scripts', [ $this, 'wpop_style' ] );
            /*
             * wp_footer hook will call on wp footer.
             */
            add_action( 'wp_head', [ $this, 'wpop_head' ] );
            /*
             * wp_footer hook will call on wp footer.
             */
            add_action( 'wp_footer', [ $this, 'wpop_content' ] );
            /*
             * wp_ajax_nopriv_wpop_newsletter Hook will call the wpop_newsletter_cb ajax function.
             */
            add_action( 'wp_ajax_nopriv_wpop_newsletter', [ $this, 'wpop_newsletter' ] );
            add_action( 'wp_ajax_wpop_newsletter', [ $this, 'wpop_newsletter' ] );
            /*
             * wp_ajax_nopriv_wpop_view Hook will call the wpop_view ajax function.
             */
            add_action( 'wp_ajax_nopriv_wpop_view', [ $this, 'wpop_view' ] );
            add_action( 'wp_ajax_wpop_view', [ $this, 'wpop_view' ] );
            /*
             * wp_ajax_nopriv_xo_view Hook will call the wpop_conversion ajax function.
             */
            add_action( 'wp_ajax_nopriv_wpop_conversion', [ $this, 'wpop_conversion' ] );
            add_action( 'wp_ajax_wpop_conversion', [ $this, 'wpop_conversion' ] );
            /*
             * Hide the entitiy for 30 days
             */
            add_action( 'wp_ajax_nopriv_wpop_hide', [ $this, 'wpop_hide' ] );
            add_action( 'wp_ajax_wpop_hide', [ $this, 'wpop_hide' ] );
        }
        
        /* __construct Method ends here. */
        /* wpop_style method will call stlying and jquery file for frontend. */
        public function wpop_style()
        {
            /* Enqueuing fonts file. */
            wp_enqueue_style(
                'wpoptin-fonts',
                WPOP_URL . '/assets/css/wpoptin-fonts.css',
                [],
                WPOP_VERSION
            );
            /* Enqueuing Main style file. */
            wp_enqueue_style(
                'wpoptin-frontend',
                WPOP_URL . 'frontend/assets/css/wpoptin-frontend.css',
                [],
                WPOP_VERSION
            );
            wp_enqueue_style(
                'jquery.fancybox.min',
                WPOP_URL . 'frontend/assets/css/jquery.fancybox.min.css',
                [],
                WPOP_VERSION
            );
            wp_enqueue_script(
                'jquery.fancybox.min',
                WPOP_URL . 'frontend/assets/js/jquery.fancybox.min.js',
                [ 'jquery' ],
                WPOP_VERSION
            );
            /* Enqueing Custom jquery file for frontend.*/
            wp_enqueue_script(
                'wpoptin-frontend',
                WPOP_URL . 'frontend/assets/js/wpoptin-frontend.js',
                [ 'jquery' ],
                WPOP_VERSION,
                false
            );
            wp_localize_script( 'wpoptin-frontend', 'wpoptin', [
                'ajax_url'           => admin_url( 'admin-ajax.php' ),
                'offer_expired_text' => __( 'This offer has expired!', 'wpoptin' ),
                'nonce'              => wp_create_nonce( 'wpop-ajax-nonce' ),
                'day'                => __( 'Day', 'wpoptin' ),
                'week'               => __( 'Week', 'wpoptin' ),
                'hours'              => __( 'Hour', 'wpoptin' ),
                'minutes'            => __( 'Minutes', 'wpoptin' ),
                'seconds'            => __( 'Seconds', 'wpoptin' ),
                'email_empty'        => __( 'Email field is required', 'wpoptin' ),
                'subscribing'        => __( 'Subscribing...', 'wpoptin' ),
                'inverted_logo'      => WPOP_URL . '/assets/images/wpop-menu-icon.png',
                'logo'               => WPOP_URL . '/assets/images/wpoptin-logo.png',
                'powered_by'         => __( 'Powered by WPOptin', 'wpoptin' ),
                'free_plan'          => wpop_fs()->is_free_plan(),
            ] );
            wp_enqueue_style( 'wpoptin-customizer-style', admin_url( 'admin-ajax.php' ) . '?action=wpoptin-customizer-style', 'wpoptin-frontend' );
        }
        
        /* wpop_style Method ends here. */
        /* wpop_content method will perform main frontend settings etc. */
        function wpop_content()
        {
            /* Getting main xoptins object. */
            global  $wpoptins ;
            /* Getting Main class. */
            global  $WPOptins ;
            /* Checking if it's customizer iframe. */
            
            if ( is_customize_preview() ) {
                /* Getting saved ID. */
                $xoptin_id = get_option( 'wpop_optin_id_save', true );
                $skin_customize = get_option( 'wpop_skin_customize_save', true );
                $post = get_post( $xoptin_id );
                
                if ( $post->post_parent ) {
                    $is_child = true;
                } else {
                    $is_child = false;
                }
                
                /* Html of offer bar */
                $returner = null;
                $optin_goal = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'goal',
                    false,
                    false,
                    $is_child
                )['0'];
                $type = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'type',
                    false,
                    false,
                    $is_child
                );
                if ( !isset( $type ) || empty($type) ) {
                    $type = 'bar';
                }
                /* Geting content. */
                $content = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'text',
                    false,
                    false,
                    $is_child
                );
                /* Geting cupon code. */
                $cupon = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'cupon-code',
                    false,
                    false,
                    $is_child
                )['0'];
                /* Geting timer text */
                $timer_text = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'timer-text',
                    false,
                    false,
                    $is_child
                );
                /* Geting btn url */
                $offer_btn_url = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'offer-btn-url',
                    false,
                    false,
                    $is_child
                );
                /* Geting btn text */
                $offer_btn_text = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'offer-btn-text',
                    false,
                    false,
                    $is_child
                );
                $type = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'type',
                    false,
                    false,
                    $is_child
                );
                $newsletter_placeholder = wp_strip_all_tags( $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'newsletter-placeholder',
                    false,
                    false,
                    $is_child
                ) );
                $btn_text = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'btn-text',
                    false,
                    false,
                    $is_child
                );
                $service_provider = $WPOptins->wpop_get_settings(
                    $xoptin_id,
                    'service_provider',
                    false,
                    false,
                    $is_child
                );
                
                if ( isset( $skin_customize ) && $skin_customize == 'true' ) {
                    $newsletter_placeholder = __( 'Enter your email address', 'wpoptin' );
                    $btn_text = __( 'Submit', 'wpoptin' );
                }
                
                /*
                 * Get all values of current instance
                 */
                if ( $xoptin_id ) {
                    $instance = $wpoptins[$xoptin_id];
                }
                if ( !isset( $optin_goal ) && empty($optin_goal) ) {
                    $optin_goal = 'offer-bar';
                }
                $optin_goal = str_replace( "_", "-", $optin_goal );
                
                if ( $wpop_templateurl = locate_template( [ 'wpoptin/frontend/templates/template-' . $optin_goal . '.php' ] ) ) {
                    $wpop_templateurl = $wpop_templateurl;
                } else {
                    $wpop_templateurl = WPOP_DIR . 'frontend/templates/template-' . $optin_goal . '.php';
                }
                
                require $wpop_templateurl;
            } else {
                if ( $wpoptins ) {
                    foreach ( $wpoptins as $xoptin ) {
                        $xoptin_id = $xoptin['ID'];
                        $post = get_post( $xoptin_id );
                        
                        if ( $post->post_parent ) {
                            $is_child = true;
                        } else {
                            $is_child = false;
                        }
                        
                        /* Geting content. */
                        $content = $WPOptins->wpop_get_settings(
                            $xoptin_id,
                            'text',
                            false,
                            false,
                            $is_child
                        );
                        /* Geting cupon code. */
                        $cupon = $WPOptins->wpop_get_settings(
                            $xoptin_id,
                            'cupon-code',
                            false,
                            false,
                            $is_child
                        )['0'];
                        /* Html of offer bar */
                        $returner = null;
                        $optin_goal = $WPOptins->wpop_get_settings(
                            $xoptin_id,
                            'goal',
                            false,
                            false,
                            $is_child
                        )['0'];
                        $type = $WPOptins->wpop_get_settings(
                            $xoptin_id,
                            'type',
                            false,
                            false,
                            $is_child
                        );
                        if ( !isset( $type ) || empty($type) ) {
                            $type = 'bar';
                        }
                        $should_show = $this->wpop_check_display( $xoptin_id );
                        $wpop_excluded = apply_filters( 'wpop_exclude_on', [
                            'wpoptin_id'     => $xoptin_id,
                            'current_status' => $should_show,
                        ] );
                        $should_show = $wpop_excluded['current_status'];
                        /* If variable don't have "yes" value than go back(don't show). */
                        if ( empty($should_show) ) {
                            continue;
                        }
                        $attr = null;
                        /* Sending triggers in js. */
                        switch ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) ) {
                            case 'auto':
                                $attr .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
                                $attr .= 'data-auto_method ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_auto_method' ) . '"';
                                if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_auto_method' ) == 'sec' ) {
                                    $attr .= 'data-auto_sec ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_sec_value' ) . '"';
                                }
                                break;
                            case 'scroll':
                                $attr .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
                                $attr .= 'data-scroll_method ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scroll_method' ) . '"';
                                
                                if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scroll_method' ) == 'scroll_perc' ) {
                                    $attr .= 'data-scroll_percent ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scrol_perc_value' ) . '"';
                                } else {
                                    $attr .= 'data-scroll_selector ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_scrol_slect_value' ) . '"';
                                }
                                
                                break;
                            case 'click':
                                $attr .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
                                $attr .= 'data-click_class ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_click_class' ) . '"';
                                break;
                            case 'exit':
                                $attr .= 'data-trigger_method="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_trigger_method' ) . '"';
                                if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'xo_ops' ) ) {
                                    $attr .= 'data-exit_ops ="' . $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'xo_ops' ) . '"';
                                }
                                break;
                            default:
                                $attr .= 'data-trigger_method="auto"';
                                break;
                        }
                        if ( !isset( $optin_goal ) && empty($optin_goal) ) {
                            $optin_goal = 'offer-bar';
                        }
                        $optin_goal = str_replace( "_", "-", $optin_goal );
                        /*
                         * Get all values of current instance
                         */
                        if ( $xoptin_id ) {
                            $instance = $wpoptins[$xoptin_id];
                        }
                        
                        if ( $wpop_templateurl = locate_template( [ 'wpoptin/frontend/templates/template-' . $optin_goal . '.php' ] ) ) {
                            $wpop_templateurl = $wpop_templateurl;
                        } else {
                            $wpop_templateurl = WPOP_DIR . 'frontend/templates/template-' . $optin_goal . '.php';
                        }
                        
                        require $wpop_templateurl;
                    }
                }
                /* Optins loop ends here. */
            }
            
            /* Customizer else ends here. */
        }
        
        /* xo_content method ends here. */
        /*
         * wpop_newsletter on ajax.
         * Return the sucess message. 
         */
        function wpop_newsletter()
        {
            global  $wpoptins ;
            global  $WPOptins ;
            /* Getting service provider name. */
            $email = $_POST['email'];
            parse_str( $email );
            $is_child = $xo_newsletter_is_child;
            $account_id = $WPOptins->wpop_get_settings(
                $xo_newsletter_id,
                'account_id',
                false,
                false,
                $is_child
            );
            $list_id = $WPOptins->wpop_get_settings(
                $xo_newsletter_id,
                'list_id',
                false,
                false,
                $is_child
            );
            $account = get_post( $account_id, 'ARRAY_A' );
            $account = unserialize( $account['post_content'] );
            $api_key = base64_decode( $account['api_key'] );
            $refresh_access_token = base64_decode( $account['refresh_access_token'] );
            $token = base64_decode( $account['access_token'] );
            $username = base64_decode( $account['username'] );
            switch ( $xo_newsletter_service ) {
                case 'mailchimp':
                    $api = new MCAPI( $api_key );
                    $response = $api->add_subscribers( $xo_newsletter_email, $list_id );
                    
                    if ( $response->status === 'subscribed' ) {
                        $sucess_message = $WPOptins->wpop_get_settings(
                            $xo_newsletter_id,
                            'success-message',
                            false,
                            false,
                            $is_child
                        );
                        echo  wp_send_json_success( $sucess_message ) ;
                        wp_die();
                    } else {
                        $error_message = $WPOptins->wpop_get_settings(
                            $xo_newsletter_id,
                            'error-message',
                            false,
                            false,
                            $is_child
                        );
                        echo  wp_send_json_error( $error_message ) ;
                        wp_die();
                    }
                    
                    break;
                case 'constant_contact':
                    $api_key = $account['api_key'];
                    $cc_api_url = esc_url_raw( 'https://api.constantcontact.com/v2/contacts?email=' . $xo_newsletter_email . '&api_key=' . $api_key );
                    $error_message = null;
                    $cc_api_requested = wp_remote_get( $cc_api_url, [
                        'timeout' => 30,
                        'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    ] );
                    $response_code = wp_remote_retrieve_response_code( $cc_api_requested );
                    
                    if ( !is_wp_error( $cc_api_requested ) && $response_code == 200 ) {
                        $subscribe_arr = sprintf( '{"email_addresses":[{"email_address": "%1$s" }], "lists":[{"id": "%2$s"}]}', sanitize_email( $xo_newsletter_email ), esc_html( $list_id ) );
                        $add_url = esc_url_raw( 'https://api.constantcontact.com/v2/contacts?api_key=' . $api_key );
                        $subscribe_request = wp_remote_post( $add_url, [
                            'timeout' => 30,
                            'headers' => [
                            'Authorization' => 'Bearer ' . $token,
                            'content-type'  => 'application/json',
                        ],
                            'body'    => $subscribe_arr,
                        ] );
                        $response_code = wp_remote_retrieve_response_code( $subscribe_request );
                        
                        if ( $response_code == 201 ) {
                            $sucess_message = $WPOptins->wpop_get_settings(
                                $xo_newsletter_id,
                                'success-message',
                                false,
                                false,
                                $is_child
                            );
                            echo  wp_send_json_success( $sucess_message ) ;
                            wp_die();
                        } else {
                            $error_message = $WPOptins->wpop_get_settings(
                                $xo_newsletter_id,
                                'error-message',
                                false,
                                false,
                                $is_child
                            );
                            echo  wp_send_json_error( $error_message ) ;
                            wp_die();
                        }
                    
                    }
                    
                    break;
                case 'campaign_monitor':
                    $auth = [
                        'access_token'  => $api_key,
                        'refresh_token' => $refresh_access_token,
                    ];
                    $wrap = new CS_REST_Subscribers( $list_id, $auth );
                    $result = $wrap->add( [
                        'EmailAddress'   => sanitize_email( $xo_newsletter_email ),
                        'ConsentToTrack' => "Yes",
                    ] );
                    
                    if ( $result->was_successful() ) {
                        $sucess_message = $WPOptins->wpop_get_settings(
                            $xo_newsletter_id,
                            'success-message',
                            false,
                            false,
                            $is_child
                        );
                        echo  wp_send_json_success( $sucess_message ) ;
                        wp_die();
                    } else {
                        $error_message = $WPOptins->wpop_get_settings(
                            $xo_newsletter_id,
                            'error-message',
                            false,
                            false,
                            $is_child
                        );
                        echo  wp_send_json_error( $error_message ) ;
                        wp_die();
                    }
                    
                    break;
                case 'mad_mimi':
                    // check whether the user already subscribed
                    $check_user_url = esc_url_raw( sprintf(
                        'https://api.madmimi.com/audience_members/%1$s/lists.json?username=%2$s&api_key=%3$s',
                        rawurlencode( sanitize_email( $xo_newsletter_email ) ),
                        rawurlencode( sanitize_text_field( $username ) ),
                        sanitize_text_field( $api_key )
                    ) );
                    $check_user_request = wp_remote_get( $check_user_url, [
                        'timeout' => 30,
                    ] );
                    $check_user_response_code = wp_remote_retrieve_response_code( $check_user_request );
                    
                    if ( !is_wp_error( $check_user_request ) && $check_user_response_code == 200 ) {
                        $check_user_response = json_decode( wp_remote_retrieve_body( $check_user_request ), true );
                        if ( !empty($check_user_response) ) {
                            // check whether current email subscribed to current list and return if true
                            foreach ( $check_user_response as $list ) {
                                if ( (int) $list_id === (int) $list['id'] ) {
                                    return esc_html__( 'Already subscribed', 'wpoptin' );
                                }
                            }
                        }
                        // if user is not subscribed yet - try to subscribe
                        $request_url = esc_url_raw( sprintf(
                            'https://api.madmimi.com/audience_lists/%1$s/add?email=%2$s&api_key=%3$s&username=%4$s',
                            sanitize_text_field( $list_id ),
                            rawurlencode( sanitize_email( $xo_newsletter_email ) ),
                            sanitize_text_field( $api_key ),
                            rawurlencode( sanitize_text_field( $username ) )
                        ) );
                        $theme_request = wp_remote_post( $request_url, [
                            'timeout' => 30,
                        ] );
                        $response_code = wp_remote_retrieve_response_code( $theme_request );
                        
                        if ( !is_wp_error( $theme_request ) && $response_code == 200 ) {
                            $sucess_message = $WPOptins->wpop_get_settings(
                                $xo_newsletter_id,
                                'success-message',
                                false,
                                false,
                                $is_child
                            );
                            echo  wp_send_json_success( $sucess_message ) ;
                            wp_die();
                        } else {
                            $error_message = $WPOptins->wpop_get_settings(
                                $xo_newsletter_id,
                                'error-message',
                                false,
                                false,
                                $is_child
                            );
                            echo  wp_send_json_error( $error_message ) ;
                            wp_die();
                        }
                    
                    }
                    
                    break;
                case 'mailpoet':
                    echo  wp_send_json_error( __( 'Whoops! MailPoet not included in free version', 'wpoptin' ) ) ;
                    wp_die();
                    break;
                default:
                    echo  wp_send_json_error( __( 'Service provider not found', 'wpoptin' ) ) ;
                    wp_die();
                    break;
            }
        }
        
        /* xo_newsletter Method ends here. */
        /*
         * wpop_view on ajax.
         * Return the sucess message.
         */
        function wpop_view()
        {
            
            if ( is_customize_preview() ) {
                echo  wp_send_json_success( __( 'In Customizer', 'wpoptin' ) ) ;
                wp_die();
            }
            
            global  $wp ;
            /* Getting data from action. */
            $module = $_POST['module'];
            $id = $_POST['id'];
            $type = $_POST['xo_type'];
            $time = current_time( 'mysql' );
            $timestamp = current_time( 'timestamp' );
            $current_url = home_url();
            $meta_key = $type . '_view';
            $arr = [
                'module'    => $module,
                'type'      => $type,
                'time'      => $time,
                'url'       => $current_url,
                'timestamp' => $timestamp,
            ];
            $return_id = add_post_meta( $id, 'views', $arr );
            echo  wp_send_json_success( $return_id ) ;
            wp_die();
        }
        
        /* wpop_view Method ends here. */
        /*
         * wpop_conversion on ajax.
         * Return the sucess message. 
         */
        function wpop_conversion()
        {
            if ( is_customize_preview() ) {
                wp_send_json_success( __( 'In Customizer', 'wpoptin' ) );
            }
            global  $wp ;
            /* Getting data from action. */
            $module = $_POST['module'];
            $id = $_POST['id'];
            $type = $_POST['xo_type'];
            $time = current_time( 'mysql' );
            $timestamp = current_time( 'timestamp' );
            $current_url = home_url( add_query_arg( [], $wp->request ) );
            $meta_key = $type . '_conversion';
            $arr = [
                'module'    => $module,
                'type'      => $type,
                'time'      => $time,
                'timestamp' => $timestamp,
            ];
            $return_id = add_post_meta( $id, 'conversions', $arr );
            wp_send_json_success( $return_id );
        }
        
        /*
         * wpop_hide on ajax.
         * Return the sucess message.
         */
        function wpop_hide()
        {
            if ( is_customize_preview() ) {
                wp_send_json_success( __( 'In Customizer', 'wpoptin' ) );
            }
            $id = $_POST['id'];
            $wpop_cookie_set = setcookie(
                'wpop_hide_entities[' . $id . ']',
                $id,
                time() + 86400 * 30,
                "/"
            );
            wp_send_json_success( $wpop_cookie_set );
        }
        
        public function wpop_head()
        {
            echo  '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0&appId=1983264355330375&autoLogAppEvents=1"></script>' ;
        }
        
        public function wpop_check_display( $xoptin_id )
        {
            global  $WPOptins ;
            /* By default show everywhere. */
            $should_show = true;
            $arr = [
                $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_tags_method' ),
                $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_cats_method' ),
                $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_pages_method' ),
                $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_posts_method' ),
                $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_show_home' )
            ];
            
            if ( in_array( 'on', $arr ) ) {
                $should_show = false;
                //======================================================================
                // Tags Conditions
                //======================================================================
                /* Checking if the tags method is selected. */
                if ( is_tag() ) {
                    
                    if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_tags_method' ) == 'on' ) {
                        $tags_include = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_tags_show_name' );
                        if ( !empty($tags_include) ) {
                            /* Checking if selected tags are in current post tags. */
                            
                            if ( is_tag( $tags_include ) ) {
                                /* Show on current page. */
                                $should_show = true;
                            } else {
                                /* Show on current page. */
                                $should_show = false;
                            }
                        
                        }
                        /* Making an array of tags Id,s. */
                        $tags_exclude = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_tags_hide_name' );
                        if ( !empty($tags_exclude) ) {
                            /* Checking if selected tags are in current post tags. */
                            
                            if ( is_tag( $tags_exclude ) && !is_page() ) {
                                /* Don't show on current page. */
                                $should_show = false;
                            } else {
                                $should_show = true;
                            }
                        
                        }
                        if ( empty($tags_exclude) && empty($tags_include) && is_tag() ) {
                            $should_show = true;
                        }
                    }
                
                }
                //======================================================================
                // Categories Conditions
                //======================================================================
                /* Checking if the cats method is selected. */
                if ( is_category() ) {
                    
                    if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_cats_method' ) == 'on' ) {
                        $cats_include = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_cats_show_name' );
                        if ( !empty($cats_include) ) {
                            /* Checking if selected cats are in current post cats. */
                            
                            if ( is_category( $cats_include ) ) {
                                /* Show on current page. */
                                $should_show = true;
                            } else {
                                /* Show on current page. */
                                $should_show = false;
                            }
                        
                        }
                        /* Making an array of cats Id,s. */
                        $cats_exclude = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_cats_hide_name' );
                        if ( !empty($cats_exclude) ) {
                            /* Checking if selected cats are in current post cats. */
                            
                            if ( is_category( $cats_exclude ) ) {
                                /* Don't show on current page. */
                                $should_show = false;
                            } else {
                                $should_show = true;
                            }
                        
                        }
                        if ( empty($cats_exclude) && empty($cats_include) && is_category() ) {
                            $should_show = true;
                        }
                    }
                
                }
                //======================================================================
                // Pages Conditions
                //======================================================================
                /* Checking if selected method is show page. */
                
                if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_pages_method' ) == 'on' && is_singular( 'page' ) ) {
                    /* Making an array of pages id,s. */
                    $pages_include = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_pages_show_name' );
                    if ( !empty($pages_include) ) {
                        /* Checking if selected pages is current page. */
                        
                        if ( is_page( $pages_include ) ) {
                            /* Show on current page. */
                            $should_show = true;
                        } else {
                            /* Show on current page. */
                            $should_show = false;
                        }
                    
                    }
                    /* Making an array of pages id,s. */
                    $pages_exclude = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_pages_hide_name' );
                    if ( !empty($pages_exclude) ) {
                        /* Checking if selected pages is current page. */
                        
                        if ( is_page( $pages_exclude ) ) {
                            /* Don't show on current page. */
                            $should_show = false;
                        } else {
                            $should_show = true;
                        }
                    
                    }
                    if ( empty($pages_exclude) && empty($pages_include) ) {
                        $should_show = true;
                    }
                    if ( is_archive() ) {
                        $should_show = false;
                    }
                }
                
                //======================================================================
                // Posts Conditions
                //======================================================================
                /* Checking if selected method is posts_show. */
                
                if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_posts_method' ) == 'on' && is_single() && get_post_type() == 'post' ) {
                    /* Making an array of posts id,s. */
                    $posts_include = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_posts_show_name' );
                    if ( !empty($posts_include) ) {
                        /* Checking if selected posts is current post. */
                        
                        if ( is_single( $posts_include ) ) {
                            /* Show on current page. */
                            $should_show = true;
                        } else {
                            /* Show on current page. */
                            $should_show = false;
                        }
                    
                    }
                    /* Making an array of posts id,s. */
                    $posts_exclude = $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_posts_hide_name' );
                    if ( !empty($posts_exclude) ) {
                        /* Checking if selected posts is current post. */
                        
                        if ( is_single( $posts_exclude ) ) {
                            /* Don't show on current page. */
                            $should_show = false;
                        } else {
                            $should_show = true;
                        }
                    
                    }
                    if ( empty($posts_exclude) && empty($posts_include) ) {
                        $should_show = true;
                    }
                    if ( is_archive() ) {
                        $should_show = false;
                    }
                }
                
                
                if ( is_front_page() or is_home() ) {
                    $should_show = false;
                    if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_show_home' ) == 'on' ) {
                        $should_show = true;
                    }
                }
            
            } else {
                $should_show = true;
            }
            
            if ( $WPOptins->wpop_get_settings( $xoptin_id, 'conditions', 'wpop_show_all' ) == 'on' ) {
                $should_show = true;
            }
            if ( is_search() or is_404() ) {
                $should_show = false;
            }
            if ( isset( $_COOKIE['wpop_hide_entities'] ) && !empty($_COOKIE['wpop_hide_entities']) && in_array( $xoptin_id, $_COOKIE['wpop_hide_entities'] ) ) {
                $should_show = false;
            }
            return $should_show;
        }
        
        /* xo_sperate_variants method will seprate the variants and return the randomize optins. */
        public function xo_sperate_variants( $xoptins )
        {
            /* Backing up the main array. */
            $main_optins_array = $xoptins;
            if ( $xoptins ) {
                foreach ( $xoptins as $xoptin ) {
                    $id = $xoptin['ID'];
                    
                    if ( $xoptin['has_varint'] ) {
                        $args = [
                            'post_parent' => $id,
                            'post_type'   => 'xoptin',
                            'numberposts' => 1,
                        ];
                        $childrens = get_children( $args );
                    }
                
                }
            }
        }
    
    }
    /* Class ends here. */
    /*
     * Globalising class to get functionality on other pages.
     */
    $WPOptins_Frontend = new WPOptins_Frontend();
}
