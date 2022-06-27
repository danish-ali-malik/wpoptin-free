<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//======================================================================
// Custom post type of xOptin
//======================================================================

if ( ! class_exists( 'WPOptins_Cpts' ) ) :
	class WPOptins_Cpts {

		/*
		* __construct initialize all function of this class.
		* Returns nothing. 
		* Used action_hooks to get things sequentially.
		*/
		function __construct() {

			/* Action hook fires on admin load. */
			add_action( 'init', [ $this, 'wpop_posttype' ], 20 );


		}/* __construct Method ends here. */


		/* Create custom post type of xOptin. */
		public function wpop_posttype() {


			$Xoptins = new WPOptins();

			/* Arguments for custom post type of xOptin. */
			$args = [
				'public'       => true,
				'label'        => 'WPOptins',
				'show_in_menu' => false,
				'hierarchical' => true,
			];

			/* register_post_type() registers a custom post type in wp. */
			register_post_type( 'wpoptins', $args );

			/* Arguments for custom post type of xo_accounts. */
			$args = [
				'public'       => false,
				'label'        => 'WPOptins Accounts',
				'show_in_menu' => false,
			];

			/* register_post_type() registers a custom post type in wp. */
			register_post_type( 'wpop_accounts', $args );

			/* Arguments for custom post type of xo_skins. */
			$args = [
				'public'       => false,
				'label'        => 'WPOptins Skins',
				'show_in_menu' => false,
			];

			/* register_post_type() registers a custom post type in wp. */
			register_post_type( 'wpop_skins', $args );

			// unregister_post_type( 'xo_skins' );
			global $wp_post_types;

			// echo "<pre>";
			// print_r($wp_post_types);exit();


			// Registering accounts taxonamy
			register_taxonomy( 'wpop_accounts_tax', [
				'wpoptins',
				'wpop_accounts',
			], [
				// Hierarchical taxonomy (like categories)
				'hierarchical' => true,

				// Control the slugs used for this taxonomy
				'rewrite'      => [
					'slug'         => 'wpop_accounts_tax',
					// This controls the base slug that will display before each term
					'with_front'   => false,
					// Don't display the category base before "/locations/"
					'hierarchical' => true
					// This will allow URL's like "/locations/boston/cambridge/"
				],
			] );


			wp_insert_term( 'Constant Contact', // the term
				'wpop_accounts_tax', // the taxonomy
				[
					'description' => 'ConstantContact service for collecting emails.',
					'slug'        => 'constant_contact',
				] );

			wp_insert_term( 'Campaign Monitor', // the term
				'wpop_accounts_tax', // the taxonomy
				[
					'description' => 'Campaign Monitor service for collecting emails.',
					'slug'        => 'campaign_monitor',
				] );

			wp_insert_term( 'MailChimp', // the term
				'wpop_accounts_tax', // the taxonomy
				[
					'description' => 'MailChimp service for collecting emails.',
					'slug'        => 'MailChimp',
				] );

			wp_insert_term( 'Mad Mimi', // the term
				'wpop_accounts_tax', // the taxonomy
				[
					'description' => 'Mad Mimi service for collecting emails.',
					'slug'        => 'mad_mimi',
				] );

			wp_insert_term( 'MailPoet', // the term
				'wpop_accounts_tax', // the taxonomy
				[
					'description' => 'MailPoet service for collecting emails.',
					'slug'        => 'mailpoet',
				] );

		}/* xo_posttype Method ends here. */

	}/* Class ends here. */

	/*
	* Globalising class to get functionality on other pages.
	*/
	$WPOptins_Cpts = new WPOptins_Cpts();
endif;