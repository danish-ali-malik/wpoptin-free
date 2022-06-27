<?php

/*
Plugin Name: WPOptin
Plugin URI: https://wordpress.org/plugins/wpoptin
Description: WPOptin is used for building emails list, news announcements, flash sales with coupons and beautiful call to action buttons.With WP Customizer support to design opt-ins and offers bar live.
Author: Danish Ali Malik
Version: 1.2.4
Author URI: https://maltathemes.com/danish-ali-malik
*/
//======================================================================
// WPOptin Freemius Integration
//======================================================================
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
//error_reporting(0);
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wpop_fs' ) ) {
    wpop_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wpop_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wpop_fs()
        {
            global  $wpop_fs ;
            
            if ( !isset( $wpop_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wpop_fs = fs_dynamic_init( [
                    'id'             => '5208',
                    'slug'           => 'wpoptin',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_1258b6af387c6876021fdbcbb8d1b',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => [
                    'days'               => 7,
                    'is_require_payment' => true,
                ],
                    'menu'           => [
                    'slug'       => 'wpoptin',
                    'first-path' => 'admin.php?page=wpoptin',
                    'support'    => false,
                ],
                    'is_live'        => true,
                ] );
            }
            
            return $wpop_fs;
        }
        
        // Init Freemius.
        wpop_fs();
        // Signal that SDK was initiated.
        do_action( 'wpop_fs_loaded' );
    }

}

/*
* WPOPTIN Class
*/

if ( !class_exists( 'WPOPTIN' ) ) {
    class WPOPTIN
    {
        public  $version = '1.2.4' ;
        function __construct()
        {
            add_action( 'init', [ $this, 'wpop_constants' ] );
            add_action( 'init', [ $this, 'wpop_includes' ] );
            register_activation_hook( __FILE__, [ $this, 'wpop_activate' ] );
        }
        
        /*
         * Define required plugin constants
         */
        public function wpop_constants()
        {
            if ( !defined( 'WPOP_VERSION' ) ) {
                define( 'WPOP_VERSION', $this->version );
            }
            if ( !defined( 'WPOPSLUG' ) ) {
                define( 'WPOPSLUG', 'wpop_' );
            }
            if ( !defined( 'WPOP_DIR' ) ) {
                define( 'WPOP_DIR', plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( 'WPOP_URL' ) ) {
                define( 'WPOP_URL', plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( 'WPOP_FILE' ) ) {
                define( 'WPOP_FILE', __FILE__ );
            }
        }
        
        /*
         * Include all plugin files
         */
        public function wpop_includes()
        {
            /*
             * Custom functions to use across plugin.
             */
            include WPOP_DIR . 'includes/wpop-global-functions.php';
            /*
             * Mobile detection library
             */
            if ( !class_exists( 'Mobile_Detect' ) ) {
                include WPOP_DIR . 'frontend/includes/Mobile_Detect.php';
            }
            /*
             * MailChimp API library
             */
            if ( !class_exists( 'MCAPI' ) ) {
                include WPOP_DIR . 'subscription/mailchimp/mailchimp.php';
            }
            /*
             * Campaignmonitor Api library.
             */
            if ( !class_exists( 'CS_REST_Clients' ) ) {
                include WPOP_DIR . 'subscription/createsend/csrest_general.php';
            }
            if ( !class_exists( 'CS_REST_Clients' ) ) {
                include WPOP_DIR . 'subscription/createsend/csrest_clients.php';
            }
            if ( !class_exists( 'CS_REST_Lists' ) ) {
                include WPOP_DIR . 'subscription/createsend/csrest_lists.php';
            }
            if ( !class_exists( 'CS_REST_Subscribers' ) ) {
                include WPOP_DIR . 'subscription/createsend/csrest_subscribers.php';
            }
            /*
             * WPoptins skins
             */
            if ( !class_exists( 'WPOptin_Skins' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins-skins.php';
            }
            /*
             * All WpOptins object
             */
            if ( !class_exists( 'WPOptins' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins.php';
            }
            /*
             * WPOptins accounts object
             */
            if ( !class_exists( 'WPOptins_Accounts' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins-accounts.php';
            }
            if ( !class_exists( 'WPOptins_Admin' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins-admin.php';
            }
            /*
             * Register WPOptins custom post types
             */
            if ( !class_exists( 'WPOptins_Cpts' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins-cpts.php';
            }
            include WPOP_DIR . 'admin/wpoptins-customizer-extend.php';
            if ( !class_exists( 'WPOptins_Customizer' ) ) {
                include WPOP_DIR . 'admin/class-wpoptins-customizer.php';
            }
            if ( !class_exists( 'WPOptins_Frontend' ) ) {
                include WPOP_DIR . 'frontend/class-wpoptins-frontend.php';
            }
        }
        
        /*
         * Add required entries to db on plugin activation
         */
        public function wpop_activate()
        {
            update_option( 'wpoptins_version', $this->version );
            add_option( 'wpoptins_installDate', date( 'Y-m-d h:i:s' ) );
            $data = [
                'post_title'   => 'Optin Demo',
                'post_content' => __( "This is a wpoptin demo page created by plugin automatically. Please don't delete to make the plugin work properly.", 'wpoptin' ),
                'post_type'    => 'page',
                'post_status'  => 'private',
            ];
            $added_id = get_option( 'wpop_page_id', true );
            if ( $added_id != '1' ) {
                $data['ID'] = $added_id;
            }
            $page_id = wp_insert_post( $data );
            update_option( 'wpop_page_id', $page_id );
        }
    
    }
    $GLOBALS['WPOPTIN'] = new WPOPTIN();
}
