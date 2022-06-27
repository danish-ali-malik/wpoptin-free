<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Customizer Area Of WPOptin
//======================================================================

if ( !class_exists( 'WPOptins_Customizer' ) ) {
    class WPOptins_Customizer
    {
        /*
         * __construct initialize all function of this class.
         * Returns nothing. 
         * Used action_hooks to get things sequentially.
         */
        function __construct()
        {
            /*
             * customize_register hook will add our customizer things in customizer.
             */
            add_action( 'customize_register', [ $this, 'wpop_customizer_options' ] );
            /*
             * customize_preview_init hook will add our js file in customizer.
             */
            add_action( 'customize_controls_enqueue_scripts', [ $this, 'wpop_customizer_scripts' ] );
            /*
             * customize_preview_init hook will add our js file in customizer.
             */
            add_action( 'customize_preview_init', [ $this, 'wpop_live_preview' ] );
            add_action( 'wp_ajax_wpoptin-customizer-style', [ $this, 'wpop_load_customizer_css' ] );
            add_action( 'wp_ajax_nopriv_wpoptin-customizer-style', [ $this, 'wpop_load_customizer_css' ] );
        }
        
        /* __construct Method ends here. */
        /*
         * wpop_customizer_scripts holds cutomizer data.
         */
        function wpop_customizer_scripts()
        {
            /*
             * Enqueing customizer style file.
             */
            wp_enqueue_style( 'wpoptin-customizer', WPOP_URL . 'css/wpoptin-customizer.css' );
            wp_enqueue_script( 'wpoptin-customizer-extended', WPOP_URL . 'assets/js/wpoptin-customizer-extended.js', [ 'jquery' ] );
        }
        
        /* wpop_customizer_scripts Method ends here. */
        /*
         * wpop_customizer_options holds cutomizer data.
         */
        function wpop_customizer_options( $wp_customize )
        {
            /* if id is in url then save that id into db.*/
            
            if ( isset( $_GET['wpop_optin_id'] ) ) {
                $optin_id = $_GET['wpop_optin_id'];
                update_option( 'wpop_optin_id_save', $optin_id );
            }
            
            
            if ( isset( $_GET['wpop_skin_id'] ) ) {
                $skin_id = $_GET['wpop_skin_id'];
                update_option( 'wpop_skin_id_save', $skin_id );
            }
            
            
            if ( isset( $_GET['goal'] ) ) {
                $optin_type = $_GET['goal'];
                update_option( 'wpop_optin_type_save', $optin_type );
            }
            
            
            if ( isset( $_GET['type'] ) ) {
                $type = $_GET['type'];
                update_option( 'wpop_optin_module_type_save', $type );
            }
            
            /* Getting back the saved ID.*/
            $optin_id = get_option( 'wpop_optin_id_save', true );
            /* Getting back the saved ID.*/
            $skin_id = get_option( 'wpop_skin_id_save', true );
            /* Getting back the saved type.*/
            $optin_type = get_option( 'wpop_optin_type_save', true );
            $module_type = get_option( 'wpop_optin_module_type_save', true );
            /* Adding our XOptin panel in customizer.*/
            $wp_customize->add_panel( 'wpop_customizer_panel', [
                'title' => __( 'WPOptin', 'wpoptin' ),
            ] );
            global  $WPOptins ;
            $defaults = $WPOptins->wpoptins_defaults();
            $post = get_post( $optin_id );
            
            if ( $post->post_parent ) {
                $is_child = true;
            } else {
                $is_child = false;
            }
            
            global  $wpoptins ;
            //======================================================================
            // Position section
            //======================================================================
            if ( $module_type == 'slide_in' || $module_type == 'bar' ) {
                $wp_customize->add_section( 'wpop_customizer_position', [
                    'title'       => __( 'Positions & Behaviour', 'wpoptin' ),
                    'description' => __( 'Change positions.', 'wpoptin' ),
                    'priority'    => 35,
                    'panel'       => 'wpop_customizer_panel',
                ] );
            }
            
            if ( $module_type == 'bar' ) {
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][sticky-bar]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['sticky-bar'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                    'label'    => __( 'Sticky Bar', 'wpoptin' ),
                    'type'     => 'checkbox',
                    'section'  => 'wpop_customizer_position',
                    'settings' => $setting,
                ] ) );
            }
            
            
            if ( wpop_fs()->is_plan( 'premium', true ) or wpop_fs()->is_plan( 'enterprise', true ) ) {
            } else {
                if ( $module_type == 'slide_in' || $module_type == 'bar' ) {
                    $wp_customize->add_control( new WPOPTIN_Position_Upgrade( $wp_customize, 'wpoptin_position_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Position', 'wpoptin' ),
                        'section'     => 'wpop_customizer_position',
                        'description' => __( "We're sorry, Changing position is not included in your plan. Please upgrade to the premium plan to unlock this and many more awesome features", 'wpoptin' ),
                        'popup_id'    => 'wpoptin_position_upgrade',
                    ] ) );
                }
            }
            
            //======================================================================
            // Button section
            //=====================================================================
            
            if ( $optin_type !== 'announcement' && $optin_type !== 'social_traffic' ) {
                // echo "<pre>"; print_r($optin_type);exit();
                $button_enable = $WPOptins->wpop_get_settings(
                    $optin_id,
                    'button-enable',
                    false,
                    false,
                    $is_child
                );
                if ( $optin_type !== 'custom' ) {
                    $button_enable = 'on';
                }
                
                if ( $button_enable == 'on' ) {
                    /* Adding colors section in customizer.*/
                    $wp_customize->add_section( 'wpop_customizer_button', [
                        'title'       => __( 'Button', 'wpoptin' ),
                        'description' => __( 'Customize button in real time', 'wpoptin' ),
                        'priority'    => 35,
                        'panel'       => 'wpop_customizer_panel',
                    ] );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btnbgcolor]';
                    /* Adding Setting of cuponborder color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btnbgcolor'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of cuponborder color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Background color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btncolor]';
                    /* Adding Setting of cuponborder color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btncolor'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of cuponborder color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Text color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btnbgcolor-hover]';
                    /* Adding Setting of cuponborder color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btnbgcolor-hover'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of cuponborder color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Hover background color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btncolor-hover]';
                    /* Adding Setting of cuponborder color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btncolor-hover'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of cuponborder color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Hover text color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btnbordercolor]';
                    /* Adding Setting of border color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btnbordercolor'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of border color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Border color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[design][btnbordercolor-hover]';
                    /* Adding Setting of border color.*/
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['design']['btnbordercolor-hover'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    /* Adding control of border color.*/
                    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Hover border color',
                        'wpop_customizer_button',
                        $setting,
                        null
                    ) ) );
                    /* Making settings dynamic and saving data with array.*/
                    $setting = 'wpop_data_' . $skin_id . '[border][btn-size]';
                    $wp_customize->add_setting( $setting, [
                        'default'   => $defaults['border']['btn-size'],
                        'transport' => 'postMessage',
                        'type'      => 'option',
                    ] );
                    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                        'label'       => __( 'Border size', 'wpoptin' ),
                        'type'        => 'number',
                        'section'     => 'wpop_customizer_button',
                        'settings'    => $setting,
                        'input_attrs' => [
                        'max' => 50,
                    ],
                    ] ) );
                }
            
            }
            
            //======================================================================
            // Content section
            //======================================================================
            $content_enable = $WPOptins->wpop_get_settings(
                $optin_id,
                'content-enable',
                false,
                false,
                $is_child
            );
            if ( $optin_type !== 'custom' ) {
                $content_enable = 'on';
            }
            
            if ( $content_enable == 'on' ) {
                /* Adding colors section in customizer.*/
                $wp_customize->add_section( 'wpop_customizer_content', [
                    'title'       => __( 'Content', 'wpoptin' ),
                    'description' => __( 'Customize content in real time', 'wpoptin' ),
                    'priority'    => 35,
                    'panel'       => 'wpop_customizer_panel',
                ] );
                
                if ( wpop_fs()->is_plan( 'premium', true ) or wpop_fs()->is_plan( 'enterprise', true ) ) {
                } else {
                    $wp_customize->add_control( new WPOPTIN_Background_Image_Upgrade( $wp_customize, 'wpoptin_background_image_upgrade', [
                        'settings'    => [],
                        'label'       => __( 'Background Image', 'wpoptin' ),
                        'section'     => 'wpop_customizer_content',
                        'description' => __( "We're sorry, Background image, image position and overlay with csutom color features are not included in your plan. Please upgrade to the premium plan to unlock this and many more awesome features", 'wpoptin' ),
                        'popup_id'    => 'wpoptin_position_upgrade',
                    ] ) );
                }
                
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][background-color]';
                /* Adding Setting of  main background color.*/
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['background-color'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                /* Adding Control of  main background color.*/
                $wp_customize->add_control(
                    /* Using WP_Customize_Color_Control method to show color picker.*/
                    new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                        'Background color',
                        'wpop_customizer_content',
                        $setting,
                        null
                    ) )
                );
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][color]';
                /* Adding Setting of  main color.*/
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['color'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                /* Adding Control of  main color.*/
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                    'Text color',
                    'wpop_customizer_content',
                    $setting,
                    null
                ) ) );
            }
            
            
            if ( $optin_type == 'optin' ) {
                //======================================================================
                // Form section
                //======================================================================
                /* Adding colors section in customizer.*/
                $wp_customize->add_section( 'wpop_customizer_email_field', [
                    'title'       => __( 'Form', 'wpoptin' ),
                    'description' => __( 'Customize Form in real time', 'wpoptin' ),
                    'priority'    => 35,
                    'panel'       => 'wpop_customizer_panel',
                ] );
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][emailfield-bgcolor]';
                /* Adding Setting of border color.*/
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['emailfield-bgcolor'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                /* Adding control of border color.*/
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                    'Background color',
                    'wpop_customizer_email_field',
                    $setting,
                    null
                ) ) );
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][emailfield-color]';
                /* Adding Setting of border color.*/
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['emailfield-color'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                /* Adding control of border color.*/
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                    'Color',
                    'wpop_customizer_email_field',
                    $setting,
                    null
                ) ) );
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[design][emailfield-bordercolor]';
                /* Adding Setting of border color.*/
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['design']['emailfield-bordercolor'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                /* Adding control of border color.*/
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                    'Border color',
                    'wpop_customizer_email_field',
                    $setting,
                    null
                ) ) );
                /* Making settings dynamic and saving data with array.*/
                $setting = 'wpop_data_' . $skin_id . '[border][emailfield-size]';
                $wp_customize->add_setting( $setting, [
                    'default'   => $defaults['border']['emailfield-size'],
                    'transport' => 'postMessage',
                    'type'      => 'option',
                ] );
                $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                    'label'       => __( 'Border size', 'wpoptin' ),
                    'type'        => 'number',
                    'section'     => 'wpop_customizer_email_field',
                    'settings'    => $setting,
                    'input_attrs' => [
                    'max' => 50,
                ],
                ] ) );
            }
            
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[design][border-color]';
            /* Adding Setting of border color.*/
            $wp_customize->add_setting( $setting, [
                'default'   => $defaults['design']['border-color'],
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            /* Adding control of border color.*/
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, $this->cutomizer_values(
                'Border color',
                'wpop_customizer_content',
                $setting,
                null
            ) ) );
            // echo "<pre>"; print_r($module_type);exit();
            
            if ( $module_type == 'popup' ) {
                $border_top_size = 4;
                $border_bottom_size = 4;
            } else {
                $border_top_size = 0;
                $border_bottom_size = 2;
            }
            
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[border][border-wrap-top]';
            $wp_customize->add_setting( $setting, [
                'default'   => $border_top_size,
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                'label'    => __( 'Top border size', 'wpoptin' ),
                'type'     => 'number',
                'section'  => 'wpop_customizer_content',
                'settings' => $setting,
            ] ) );
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[border][border-wrap-bottom]';
            $wp_customize->add_setting( $setting, [
                'default'   => $border_bottom_size,
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                'label'    => __( 'Bottom border size', 'wpoptin' ),
                'type'     => 'number',
                'section'  => 'wpop_customizer_content',
                'settings' => $setting,
            ] ) );
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[border][border-wrap-left]';
            $wp_customize->add_setting( $setting, [
                'default'   => $defaults['border']['border-wrap-left'],
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                'label'    => __( 'Left border size', 'wpoptin' ),
                'type'     => 'number',
                'section'  => 'wpop_customizer_content',
                'settings' => $setting,
            ] ) );
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[border][border-wrap-right]';
            $wp_customize->add_setting( $setting, [
                'default'   => $defaults['border']['border-wrap-right'],
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                'label'    => __( 'Right border size', 'wpoptin' ),
                'type'     => 'number',
                'section'  => 'wpop_customizer_content',
                'settings' => $setting,
            ] ) );
            /* Making settings dynamic and saving data with array.*/
            $setting = 'wpop_data_' . $skin_id . '[border][wrapborder-style]';
            $wp_customize->add_setting( $setting, [
                'default'   => $defaults['border']['wrapborder-style'],
                'transport' => 'postMessage',
                'type'      => 'option',
            ] );
            $wp_customize->add_control( new WP_Customize_Control( $wp_customize, $setting, [
                'label'    => __( 'Border style', 'wpoptin' ),
                'section'  => 'wpop_customizer_content',
                'settings' => $setting,
                'type'     => 'select',
                'choices'  => [
                'dashed' => 'Dashed',
                'dotted' => 'Dotted',
                'double' => 'Double',
                'solid'  => 'Solid',
                'inset'  => 'Inset',
                'outset' => 'Outset',
                'none'   => 'None',
            ],
            ] ) );
        }
        
        /*
         * cutomizer_values holds the array of customizer values.
         * Returns array. 
         */
        function cutomizer_values(
            $label,
            $section,
            $settings,
            $type
        )
        {
            $array = [
                'label'    => __( $label, 'wpoptin' ),
                'section'  => $section,
                'settings' => $settings,
                'type'     => $type,
            ];
            return $array;
        }
        
        /* cutomizer_values method ends here.*/
        /**
         * Used by hook: 'customize_preview_init'
         *
         * @see add_action('customize_preview_init',$func)
         */
        public function wpop_live_preview()
        {
            $skin_id = get_option( 'wpop_skin_id_save', true );
            $optin_id = get_option( 'wpop_optin_id_save', false );
            wp_enqueue_script(
                'wpoptin-customizer-live',
                WPOP_URL . 'assets/js/wpoptin-customizer-live.js',
                [ 'jquery', 'customize-preview' ],
                '',
                true
            );
            wp_localize_script( 'wpoptin-customizer-live', 'wpop_customizer_object', [
                'wpop_skin_id' => $skin_id,
                'wpop_id'      => $optin_id,
            ] );
        }
        
        /*
         * Include customizer style file
         */
        public function wpop_load_customizer_css()
        {
            header( "Content-type: text/css; charset: UTF-8" );
            require WPOP_DIR . 'frontend/assets/css/wpoptin-customizer-style.css.php';
            exit;
        }
    
    }
    /* Class ends here. */
    /*
     * Globalising class to get functionality on other pages.
     */
    $WPOptins_Customizer = new WPOptins_Customizer();
}
