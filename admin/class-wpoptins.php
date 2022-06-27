<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Main class of all WPOptins
//======================================================================

if ( !class_exists( 'WPOptins' ) ) {
    class WPOptins
    {
        /*
         * __construct initialize all function of this class.
         * Returns nothing. 
         * Used action_hooks to get things sequentially.
         */
        function __construct()
        {
            /*
             * init hooks fires on wp load.
             * Gets all active xOptins.
             */
            add_action( 'init', [ $this, 'wpop_active_optins' ], 100 );
        }
        
        /* __construct Method ends here. */
        /*
         * xo_active_optins will add admin page get all active xOptins.
         * Returns xOptins active object.
         */
        public function wpop_active_optins()
        {
            /*
             * Arguments for WP_Query().
             */
            $xOptins = [
                'posts_per_page' => -1,
                'post_type'      => 'wpoptins',
                'post_parent'    => 0,
                'post_status'    => 'publish',
            ];
            if ( is_admin() || is_customize_preview() ) {
                $xOptins['post_status'] = [ 'publish', 'draft', 'pending' ];
            }
            if ( is_customize_preview() ) {
                $xOptins['post_parent'] = null;
            }
            /*
             * Quering all active xOptins.
             * WP_Query() object of wp will be used.
             */
            $xOptins = new WP_Query( $xOptins );
            /* If any xOptins are in database. */
            
            if ( $xOptins->have_posts() ) {
                /* Declaring an empty array. */
                $xo_holder = [];
                $total_views = 0;
                // $total_conversions = 0;
                /* Looping xOptins to get all records. */
                while ( $xOptins->have_posts() ) {
                    /* Making it post. */
                    $xOptins->the_post();
                    $children_id = $this->wpop_get_child_optins( get_the_ID() );
                    $args = [
                        'post_parent' => get_the_ID(),
                        'post_type'   => 'wpoptins',
                        'numberposts' => -1,
                    ];
                    $childrens = get_children( $args );
                    
                    if ( $childrens && !is_admin() ) {
                        $childrens_id = $this->wpop_get_child_optins( get_the_ID() );
                        $children_id = [
                            $childrens_id => 0,
                            get_the_ID()  => 0,
                        ];
                        $rand_number = array_rand( $children_id, '1' );
                        $id = $rand_number;
                    } else {
                        $id = get_the_ID();
                    }
                    
                    /* Making xOptin status more understandable. */
                    
                    if ( get_post_status( $id ) == 'publish' ) {
                        $status = 'active';
                    } else {
                        $status = 'not active';
                    }
                    
                    $bar_views = null;
                    $bar_conversion = null;
                    // delete_post_meta($id, 'bar_conversion');
                    // delete_post_meta($id, 'bar_view');
                    $custom_fields = get_post_custom( $id );
                    if ( isset( $custom_fields['_wpop_skin_id'] ) ) {
                        $skin_id = $custom_fields['_wpop_skin_id']['0'];
                    }
                    if ( isset( $custom_fields['views'] ) ) {
                        $bar_views = count( $custom_fields['views'] );
                    }
                    if ( isset( $custom_fields['conversions'] ) ) {
                        $bar_conversion = count( $custom_fields['conversions'] );
                    }
                    if ( $bar_views == '0' or empty($bar_views) ) {
                        $bar_views = 0;
                    }
                    if ( $bar_conversion == '0' or empty($bar_conversion) ) {
                        $bar_conversion = 0;
                    }
                    $list = get_post_meta( $id, '_wpop_list_id', true );
                    if ( isset( $custom_fields['_wpop_acount_id']['0'] ) ) {
                        $acount_id = $custom_fields['_wpop_acount_id']['0'];
                    }
                    if ( isset( $custom_fields['_wpop_service_provider']['0'] ) ) {
                        $service_provider = $custom_fields['_wpop_service_provider']['0'];
                    }
                    $conditions = maybe_unserialize( get_post_meta( $id, '_wpop_conditions', true ) );
                    /* Getting the settings and other data from live preview section. */
                    $xoptins_customizer = get_option( 'wpop_data_' . $id );
                    $defaults = $this->wpoptins_defaults();
                    $wpop_default_cond = $this->wpop_conditions_defaults();
                    $conditions = wp_parse_args( $conditions, $wpop_default_cond );
                    $goal = $custom_fields['_wpop_goal']['0'];
                    $type = $custom_fields['_wpop_type']['0'];
                    /* Making an array of xOptin. */
                    $xo_holder[$id] = [
                        'ID'              => $id,
                        'title'           => get_the_title( $id ),
                        'status'          => $status,
                        'text'            => get_the_content( $id ),
                        'skin_id'         => $skin_id,
                        'type'            => $type,
                        'bar_views'       => $bar_views,
                        'bar_conversions' => $bar_conversion,
                        'conditions'      => $conditions,
                        'feat_img'        => get_the_post_thumbnail_url( $id ),
                    ];
                    if ( 'bar' != $type ) {
                        if ( 'social_traffic' == $goal ) {
                            $xo_holder[$id]['url_type'] = $custom_fields['_wpop_url_type']['0'];
                        }
                    }
                    if ( isset( $custom_fields['_wpop_goal'] ) ) {
                        $xo_holder[$id]['goal'] = $custom_fields['_wpop_goal'];
                    }
                    
                    if ( $childrens ) {
                        $xo_holder[$id]['has_varint'] = true;
                        $xo_holder[$id]['varint_id'] = $children_id;
                    } else {
                        $xo_holder[$id]['has_varint'] = false;
                    }
                    
                    if ( isset( $list ) ) {
                        $xo_holder[$id]['list_id'] = $list;
                    }
                    if ( isset( $acount_id ) ) {
                        $xo_holder[$id]['account_id'] = $acount_id;
                    }
                    if ( isset( $service_provider ) ) {
                        $xo_holder[$id]['service_provider'] = $service_provider;
                    }
                    if ( isset( $acount_id ) ) {
                        $account_exists = get_post_status( $acount_id );
                    }
                    
                    if ( isset( $account_exists ) && empty($account_exists) && $custom_fields['_wpop_goal']['0'] == 'optin' ) {
                        $xo_holder[$id]['list_id'] = false;
                        $xo_holder[$id]['account_id'] = false;
                        $xo_holder[$id]['service_provider'] = false;
                        $xo_holder[$id]['status'] = 'not active';
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_goal'] ) && $custom_fields['_wpop_goal']['0'] == 'offer_bar' ) {
                        $xo_holder[$id]['list_id'] = false;
                        $xo_holder[$id]['account_id'] = false;
                        $xo_holder[$id]['service_provider'] = false;
                    }
                    
                    
                    if ( 'social_traffic' == $goal ) {
                        
                        if ( isset( $custom_fields['_wpop_enable_social_current_url']['0'] ) ) {
                            $xo_holder[$id]['social-current-url-enable'] = $custom_fields['_wpop_enable_social_current_url']['0'];
                        } else {
                            $xo_holder[$id]['social-current-url-enable'] = null;
                        }
                        
                        
                        if ( isset( $custom_fields['_wpop_social_url']['0'] ) ) {
                            $xo_holder[$id]['social-url'] = $custom_fields['_wpop_social_url']['0'];
                        } else {
                            $xo_holder[$id]['social-url'] = null;
                        }
                    
                    }
                    
                    if ( 'optin' == $goal ) {
                    }
                    if ( 'phone_calls' == $goal ) {
                        
                        if ( isset( $custom_fields['_wpop_country_code']['0'] ) ) {
                            $xo_holder[$id]['country-code'] = $custom_fields['_wpop_country_code']['0'];
                        } else {
                            $xo_holder[$id]['country-code'] = null;
                        }
                    
                    }
                    
                    if ( isset( $custom_fields['_wpop_enable_cupon']['0'] ) ) {
                        $xo_holder[$id]['cupon-enable'] = $custom_fields['_wpop_enable_cupon']['0'];
                    } else {
                        $xo_holder[$id]['cupon-enable'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_enable_content']['0'] ) ) {
                        $xo_holder[$id]['content-enable'] = $custom_fields['_wpop_enable_content']['0'];
                    } else {
                        $xo_holder[$id]['content-enable'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_enable_button']['0'] ) ) {
                        $xo_holder[$id]['button-enable'] = $custom_fields['_wpop_enable_button']['0'];
                    } else {
                        $xo_holder[$id]['button-enable'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_cupon'] ) ) {
                        $xo_holder[$id]['cupon-code'] = $custom_fields['_wpop_cupon'];
                    } else {
                        $xo_holder[$id]['cupon-code'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_enable_timer']['0'] ) ) {
                        $xo_holder[$id]['timer-enable'] = $custom_fields['_wpop_enable_timer']['0'];
                    } else {
                        $xo_holder[$id]['timer-enable'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_new_timer_text']['0'] ) ) {
                        $xo_holder[$id]['timer-text'] = $custom_fields['_wpop_new_timer_text']['0'];
                    } else {
                        $xo_holder[$id]['timer-text'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_enable_timer_title']['0'] ) ) {
                        $xo_holder[$id]['enable-timer-title'] = $custom_fields['_wpop_enable_timer_title']['0'];
                    } else {
                        $xo_holder[$id]['enable-timer-title'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_timer']['0'] ) ) {
                        $timer_enddate = $custom_fields['_wpop_timer']['0'];
                        if ( empty($timer_enddate) ) {
                            $timer_enddate = 122;
                        }
                        $xo_holder[$id]['timer-enddate'] = date( 'Y/m/d', $timer_enddate );
                    } else {
                        $xo_holder[$id]['timer-enddate'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_timer_endtime']['0'] ) ) {
                        $timer_endtime = $custom_fields['_wpop_timer_endtime']['0'];
                        $xo_holder[$id]['timer-endtime'] = $timer_endtime;
                    } else {
                        $xo_holder[$id]['timer-endtime'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_auto_deactivate']['0'] ) ) {
                        $xo_holder[$id]['timer-auto-deactivate'] = $custom_fields['_wpop_auto_deactivate']['0'];
                    } else {
                        $xo_holder[$id]['timer-auto-deactivate'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_offer_btn_text']['0'] ) && !empty($custom_fields['wpop_new_offer_btn_text']['0']) ) {
                        $xo_holder[$id]['offer-btn-text'] = $custom_fields['wpop_new_offer_btn_text']['0'];
                    } else {
                        $xo_holder[$id]['offer-btn-text'] = __( 'Submit', 'xo' );
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_offer_btn_url']['0'] ) && !empty($custom_fields['wpop_new_offer_btn_url']['0']) ) {
                        $xo_holder[$id]['offer-btn-url'] = $custom_fields['wpop_new_offer_btn_url']['0'];
                    } else {
                        $xo_holder[$id]['offer-btn-url'] = '#';
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_newsletter_placeholder']['0'] ) && !empty($custom_fields['wpop_new_newsletter_placeholder']['0']) ) {
                        $xo_holder[$id]['newsletter-placeholder'] = $custom_fields['wpop_new_newsletter_placeholder']['0'];
                    } else {
                        $xo_holder[$id]['newsletter-placeholder'] = __( 'Enter your email address', 'xo' );
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_newsletter_btn_text']['0'] ) && !empty($custom_fields['wpop_new_newsletter_btn_text']['0']) ) {
                        $xo_holder[$id]['btn-text'] = $custom_fields['wpop_new_newsletter_btn_text']['0'];
                    } else {
                        $xo_holder[$id]['btn-text'] = __( 'Submit', 'xo' );
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_newsletter_sucess_msg']['0'] ) && !empty($custom_fields['wpop_new_newsletter_sucess_msg']['0']) ) {
                        $xo_holder[$id]['success-message'] = wp_strip_all_tags( $custom_fields['wpop_new_newsletter_sucess_msg']['0'] );
                    } else {
                        $xo_holder[$id]['success-message'] = __( 'Successfully Subscribed!', 'xo' );
                    }
                    
                    
                    if ( isset( $custom_fields['wpop_new_newsletter_error_msg']['0'] ) && !empty($custom_fields['wpop_new_newsletter_error_msg']['0']) ) {
                        $xo_holder[$id]['error-message'] = wp_strip_all_tags( $custom_fields['wpop_new_newsletter_error_msg']['0'] );
                    } else {
                        $xo_holder[$id]['error-message'] = __( 'Something went wrong!', 'xo' );
                    }
                    
                    
                    if ( isset( $skin_id ) ) {
                        $customizer_style = get_option( 'wpop_data_' . $skin_id );
                        /* If there is no data in live preview section of xOptin setting the default data. */
                        $customizer_data['design'] = $customizer_style['design'];
                        if ( isset( $customizer_style['border'] ) ) {
                            $customizer_data['border'] = $customizer_style['border'];
                        }
                    } else {
                        /* If there is no data in live preview section of xOptin setting the default data. */
                        $customizer_data['design'] = $xoptins_customizer['design'];
                        $customizer_data['border'] = $xoptins_customizer['border'];
                    }
                    
                    $xo_holder[$id]['customizer'] = $customizer_data;
                }
                /* Reseting the current query. */
                wp_reset_postdata();
            } else {
                return __( 'No WPOptins found.', 'wpoptin' );
            }
            
            $GLOBALS['wpoptins'] = $xo_holder;
        }
        
        /* xo_active_optins Method ends here. */
        /*
         * wpoptins_defaults will have default values of xOptins.
         */
        public function wpoptins_defaults()
        {
            $default_val_arr = [
                'design' => [
                'background-color'       => '#fff',
                'btnbgcolor'             => '#7cc68d',
                'btnbordercolor'         => '#7cc68d',
                'btnbordercolor-hover'   => '#68ab77',
                'btncolor'               => '#fff',
                'btnbgcolor-hover'       => '#68ab77',
                'btncolor-hover'         => '#fff',
                'color'                  => '#000',
                'link-color'             => '#fff',
                'counterbg-color'        => '#ed6d62',
                'counter-color'          => '#fff',
                'cuponbg-color'          => '#fcffa9',
                'cupon-color'            => '#000',
                'border-color'           => '#ed6d62',
                'cuponborder-color'      => '#aaa',
                'emailfield-bgcolor'     => '#f7f7f7',
                'emailfield-bordercolor' => '#d1d1d1',
                'emailfield-color'       => '#000',
                'sticky-bar'             => 'true',
            ],
                'border' => [
                'border-wrap-top'    => '2',
                'border-wrap-bottom' => '2',
                'border-wrap-left'   => '0',
                'border-wrap-right'  => '0',
                'wrapborder-style'   => 'solid',
                'cuponborder-style'  => 'dashed',
                'cuponborder-size'   => '1',
                'emailfield-size'    => '1',
                'btn-size'           => '1',
            ],
            ];
            return $default_val_arr;
        }
        
        /* wpoptins_defaults Method ends here. */
        /*
         * wpop_get_settings will return the settings value from customizer.
         */
        public function wpop_get_settings(
            $id,
            $group = false,
            $group_child = false,
            $key = false,
            $variant = false
        )
        {
            if ( !isset( $id ) || empty($id) ) {
                return;
            }
            $setting_val = null;
            $bar_view = null;
            $bar_views = null;
            $bar_conversion = null;
            $goal = null;
            $children_id = null;
            /* Global Object.*/
            global  $wpoptins ;
            // echo "<pre>"; print_r($wpoptins);exit();
            
            if ( isset( $variant ) ) {
                /* Declaring an empty array. */
                $xo_variants = [];
                $total_views = 0;
                $children = get_post( $id );
                /* Getting the ID. */
                $id = $children->ID;
                /* Making xOptin status more understandable. */
                
                if ( $children->post_status == 'publish' ) {
                    $status = 'active';
                } else {
                    $status = 'not active';
                }
                
                $custom_fields = get_post_custom( $id );
                // echo "<pre>";
                //  print_r($custom_fields);exit();
                if ( isset( $custom_fields['views'] ) ) {
                    $bar_views = count( $custom_fields['views'] );
                }
                if ( isset( $custom_fields['conversions'] ) ) {
                    $bar_conversion = count( $custom_fields['conversions'] );
                }
                if ( $bar_views == '0' or empty($bar_views) ) {
                    $bar_views = 0;
                }
                if ( $bar_conversion == '0' or empty($bar_conversion) ) {
                    $bar_conversion = 0;
                }
                $list = get_post_meta( $id, '_wpop_list_id', true );
                if ( isset( $custom_fields['_wpop_acount_id']['0'] ) ) {
                    $acount_id = $custom_fields['_wpop_acount_id']['0'];
                }
                if ( isset( $custom_fields['_wpop_service_provider']['0'] ) ) {
                    $service_provider = $custom_fields['_wpop_service_provider']['0'];
                }
                $conditions = maybe_unserialize( get_post_meta( $id, '_wpop_conditions', true ) );
                /* Getting the settings and other data from live preview section. */
                $xoptins_customizer = get_option( 'wpop_data_' . $id );
                $defaults = $this->wpoptins_defaults();
                $wpop_default_cond = $this->wpop_conditions_defaults();
                $conditions = wp_parse_args( $conditions, $wpop_default_cond );
                
                if ( isset( $custom_fields['_wpop_type'] ) ) {
                    $type = $custom_fields['_wpop_type']['0'];
                } else {
                    $type = '';
                }
                
                
                if ( isset( $custom_fields['_wpop_skin_id'] ) ) {
                    $skin_id = $custom_fields['_wpop_skin_id']['0'];
                } else {
                    $skin_id = '';
                }
                
                
                if ( isset( $custom_fields['_wpop_goal'] ) ) {
                    $goal = $custom_fields['_wpop_goal'];
                } else {
                    $goal = '';
                }
                
                /* Making an array of xOptin. */
                $xo_variants[$id] = [
                    'ID'              => $id,
                    'title'           => $children->post_title,
                    'status'          => $status,
                    'goal'            => $goal,
                    'text'            => $children->post_content,
                    'type'            => $type,
                    'skin_id'         => $skin_id,
                    'bar_views'       => $bar_views,
                    'bar_conversions' => $bar_conversion,
                    'conditions'      => $conditions,
                    'feat_img'        => get_the_post_thumbnail_url( $id ),
                ];
                
                if ( isset( $childrens ) ) {
                    $xo_variants[$id]['has_varint'] = true;
                    $xo_variants[$id]['varint_id'] = $children_id;
                } else {
                    $xo_variants[$id]['has_varint'] = false;
                }
                
                if ( isset( $list ) ) {
                    $xo_variants[$id]['list_id'] = $list;
                }
                if ( isset( $acount_id ) ) {
                    $xo_variants[$id]['account_id'] = $acount_id;
                }
                if ( isset( $service_provider ) ) {
                    $xo_variants[$id]['service_provider'] = $service_provider;
                }
                if ( isset( $acount_id ) ) {
                    $account_exists = get_post_status( $acount_id );
                }
                
                if ( isset( $account_exists ) && empty($account_exists) && $custom_fields['_wpop_goal']['0'] == 'optin' ) {
                    $xo_variants[$id]['account_id'] = false;
                    $xo_variants[$id]['list_id'] = false;
                    $xo_variants[$id]['account_id'] = false;
                    $xo_variants[$id]['service_provider'] = false;
                    $xo_variants[$id]['status'] = 'not active';
                }
                
                if ( 'popup' == $type && 'social_traffic' == $goal ) {
                    $xo_variants[$id]['url_type'] = $custom_fields['_wpop_url_type']['0'];
                }
                
                if ( !isset( $conditions ) ) {
                    $xo_variants[$id]['conditions']['wpop_trigger_method'] = 'auto';
                    $xo_variants[$id]['conditions']['wpop_auto_method'] = 'im';
                    $xo_variants[$id]['conditions']['wpop_scrol_perc_value'] = '50';
                }
                
                
                if ( isset( $custom_fields['_wpop_goal']['0'] ) && $custom_fields['_wpop_goal']['0'] == 'offer_bar' ) {
                    $xo_variants[$id]['list_id'] = false;
                    $xo_variants[$id]['account_id'] = false;
                    $xo_variants[$id]['service_provider'] = false;
                }
                
                
                if ( 'social_traffic' == $goal ) {
                    
                    if ( isset( $custom_fields['_wpop_enable_social_current_url']['0'] ) ) {
                        $xo_variants[$id]['social-current-url-enable'] = $custom_fields['_wpop_enable_social_current_url']['0'];
                    } else {
                        $xo_variants[$id]['social-current-url-enable'] = null;
                    }
                    
                    
                    if ( isset( $custom_fields['_wpop_social_url']['0'] ) ) {
                        $xo_variants[$id]['social-url'] = $custom_fields['_wpop_social_url']['0'];
                    } else {
                        $xo_variants[$id]['social-url'] = null;
                    }
                
                }
                
                if ( 'optin' == $goal ) {
                }
                if ( 'phone_calls' == $goal ) {
                    
                    if ( isset( $custom_fields['_wpop_country_code']['0'] ) ) {
                        $xo_variants[$id]['country-code'] = $custom_fields['_wpop_country_code']['0'];
                    } else {
                        $xo_variants[$id]['country-code'] = null;
                    }
                
                }
                
                if ( isset( $custom_fields['_wpop_enable_cupon']['0'] ) ) {
                    $xo_variants[$id]['cupon-enable'] = $custom_fields['_wpop_enable_cupon']['0'];
                } else {
                    $xo_variants[$id]['cupon-enable'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_enable_timer_title']['0'] ) ) {
                    $xo_variants[$id]['enable-timer-title'] = $custom_fields['_wpop_enable_timer_title']['0'];
                } else {
                    $xo_variants[$id]['enable-timer-title'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_enable_content']['0'] ) ) {
                    $xo_variants[$id]['content-enable'] = $custom_fields['_wpop_enable_content']['0'];
                } else {
                    $xo_variants[$id]['content-enable'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_enable_button']['0'] ) ) {
                    $xo_variants[$id]['button-enable'] = $custom_fields['_wpop_enable_button']['0'];
                } else {
                    $xo_variants[$id]['button-enable'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_cupon'] ) ) {
                    $xo_variants[$id]['cupon-code'] = $custom_fields['_wpop_cupon'];
                } else {
                    $xo_variants[$id]['cupon-code'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_enable_timer']['0'] ) ) {
                    $xo_variants[$id]['timer-enable'] = $custom_fields['_wpop_enable_timer']['0'];
                } else {
                    $xo_variants[$id]['timer-enable'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_new_timer_text']['0'] ) ) {
                    $xo_variants[$id]['timer-text'] = $custom_fields['_wpop_new_timer_text']['0'];
                } else {
                    $xo_variants[$id]['timer-text'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_timer']['0'] ) ) {
                    $xo_variants[$id]['timer-enddate'] = $custom_fields['_wpop_timer']['0'];
                } else {
                    $xo_variants[$id]['timer-enddate'] = null;
                }
                
                
                if ( isset( $custom_fields['wpop_new_offer_btn_text']['0'] ) ) {
                    $xo_variants[$id]['offer-btn-text'] = $custom_fields['wpop_new_offer_btn_text']['0'];
                } else {
                    $xo_variants[$id]['offer-btn-text'] = __( 'Submit', 'xo' );
                }
                
                
                if ( isset( $custom_fields['wpop_new_offer_btn_url']['0'] ) ) {
                    $xo_variants[$id]['offer-btn-url'] = $custom_fields['wpop_new_offer_btn_url']['0'];
                } else {
                    $xo_variants[$id]['offer-btn-url'] = '#';
                }
                
                
                if ( isset( $custom_fields['wpop_new_newsletter_placeholder']['0'] ) ) {
                    $xo_variants[$id]['newsletter-placeholder'] = $custom_fields['wpop_new_newsletter_placeholder']['0'];
                } else {
                    $xo_variants[$id]['newsletter-placeholder'] = __( 'Enter your email address', 'xo' );
                }
                
                
                if ( isset( $custom_fields['wpop_new_newsletter_btn_text']['0'] ) ) {
                    $xo_variants[$id]['btn-text'] = $custom_fields['wpop_new_newsletter_btn_text']['0'];
                } else {
                    $xo_variants[$id]['btn-text'] = __( 'Submit', 'xo' );
                }
                
                
                if ( isset( $custom_fields['wpop_new_newsletter_sucess_msg']['0'] ) ) {
                    $xo_variants[$id]['success-message'] = wp_strip_all_tags( $custom_fields['wpop_new_newsletter_sucess_msg']['0'] );
                } else {
                    $xo_variants[$id]['success-message'] = __( 'Successfully Subscribed! Please check your email', 'xo' );
                }
                
                
                if ( isset( $custom_fields['_wpop_enable_fomo_countdown']['0'] ) ) {
                    $xo_variants[$id]['enable-fomo-countdown'] = $custom_fields['_wpop_enable_fomo_countdown']['0'];
                } else {
                    $xo_variants[$id]['enable-fomo-countdown'] = null;
                }
                
                
                if ( isset( $custom_fields['_wpop_new_timer_fomo_unit']['0'] ) ) {
                    $xo_variants[$id]['fomo-timer-unit'] = $custom_fields['_wpop_new_timer_fomo_unit']['0'];
                } else {
                    $xo_variants[$id]['fomo-timer-unit'] = 4;
                }
                
                
                if ( isset( $custom_fields['_wpop_new_timer_fomo_duration']['0'] ) ) {
                    $xo_variants[$id]['fomo-timer-duration'] = $custom_fields['_wpop_new_timer_fomo_duration']['0'];
                } else {
                    $xo_variants[$id]['fomo-timer-duration'] = 'hours';
                }
                
                
                if ( isset( $custom_fields['wpop_new_newsletter_error_msg']['0'] ) ) {
                    $xo_variants[$id]['error-message'] = wp_strip_all_tags( $custom_fields['wpop_new_newsletter_error_msg']['0'] );
                } else {
                    $xo_variants[$id]['error-message'] = __( 'Something went wrong (Maybe user is already registered)', 'xo' );
                }
                
                /* If there is no data in live preview section of xOptin setting the default data. */
                $customizer_data['design'] = $xoptins_customizer['design'];
                $customizer_data['border'] = $xoptins_customizer['border'];
                /* Final array of xOptin. */
                $xo_variants[$id]['customizer'] = $customizer_data;
            }
            
            /* Getting specific values.*/
            if ( !isset( $id ) ) {
                return;
            }
            if ( $group ) {
                
                if ( $variant ) {
                    $setting_val = $xo_variants[$id][$group];
                } else {
                    if ( isset( $wpoptins[$id] ) ) {
                        $setting_val = $wpoptins[$id][$group];
                    }
                }
            
            }
            if ( $group_child ) {
                
                if ( $variant ) {
                    $setting_val = $xo_variants[$id][$group][$group_child];
                } else {
                    $setting_val = $wpoptins[$id][$group][$group_child];
                }
            
            }
            if ( $key ) {
                
                if ( $variant ) {
                    $setting_val = $xo_variants[$id][$group][$group_child][$key];
                } else {
                    $setting_val = $wpoptins[$id][$group][$group_child][$key];
                }
            
            }
            // echo "<pre>"; print_r($id);exit();
            /* Returning back..*/
            return $setting_val;
        }
        
        /* xo_get_settings Method ends here. */
        /*
         * optin_exists check the post exists in db.
         */
        public function optin_exists( $post_id, $post_type )
        {
            global  $wpdb ;
            
            if ( $wpdb->get_row( "SELECT post_name FROM wp_posts WHERE ID = '" . $post_id . "' AND post_type = '" . $post_type . "'", 'ARRAY_A' ) ) {
                return true;
            } else {
                return false;
            }
        
        }
        
        /* optin_exists Method ends here. */
        /*
         * wpop_get_child_optins will get the child/variations of optin.
         */
        public function wpop_get_child_optins( $id )
        {
            $args = [
                'post_parent' => $id,
                'post_type'   => 'wpoptins',
                'numberposts' => 1,
            ];
            $childrens = get_children( $args );
            if ( $childrens ) {
                foreach ( $childrens as $children ) {
                    /* Getting the ID. */
                    $id = $children->ID;
                }
            }
            return $id;
        }
        
        /* wpop_get_child_optins Method ends here. */
        /*
         * wpoptins_conditions_defaults will have default values of wpoptins.
         */
        public function wpop_conditions_defaults()
        {
            $default_val_arr = [
                'wpop_trigger_method'    => 'auto',
                'wpop_auto_method'       => 'im',
                'wpop_scrol_perc_value'  => '50',
                'wpop_sec_value'         => null,
                'wpop_scroll_method'     => null,
                'wpop_scrol_slect_value' => null,
                'wpop_click_class'       => null,
                'wpop_vistor_login'      => null,
                'wpop_vistor_logout'     => null,
                'wpop_device_mobile'     => null,
                'wpop_device_not_mobile' => null,
                'wpop_pages_method'      => null,
                'wpop_posts_method'      => null,
                'wpop_tags_method'       => null,
                'wpop_cats_method'       => null,
                'wpop_pages_show_name'   => null,
                'wpop_posts_show_name'   => null,
                'wpop_tags_show_name'    => null,
                'wpop_cats_show_name'    => null,
                'wpop_pages_hide_name'   => null,
                'wpop_posts_hide_name'   => null,
                'wpop_tags_hide_name'    => null,
                'wpop_cats_hide_name'    => null,
                'wpop_show_all'          => null,
                'wpop_show_home'         => null,
            ];
            $default_val_arr = apply_filters( 'wpop_default_conditions', $default_val_arr );
            return $default_val_arr;
        }
    
    }
    /* Class ends here. */
    /*
     * Globalising class to get functionality on other pages.
     */
    $GLOBALS['WPOptins'] = new WPOptins();
}
