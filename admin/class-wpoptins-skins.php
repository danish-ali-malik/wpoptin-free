<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//======================================================================
// Main class of all Xoptins Skins
//======================================================================

if ( ! class_exists( 'WPOptin_Skins' ) ):
	class WPOptin_Skins {

		/*
		* __construct initialize all function of this class.
		* Returns nothing. 
		* Used action_hooks to get things sequentially.
		*/
		function __construct() {

			/*
			* init hooks fires on wp load.
			* Gets all xOptins accounts.
			*/
			add_action( 'init', [ $this, 'wpop_skins' ], 100 );
		}/* __construct Method ends here. */

		/*
		* xo_accounts will get the all accounts.
		* Returns accounts  object.
		*/
		public function wpop_skins() {

			/*
			* Arguments for WP_Query().
			*/

			$xoptins_skins = [
				'posts_per_page' => 1000,
				'post_type'      => 'wpop_skins',
				'post_status'    => [ 'publish', 'draft', 'pending' ],
			];

			$skin_for_id   = null;
			$skin_for_name = null;

			/*
			* Quering all active xOptins.
			* WP_Query() object of wp will be used.
			*/
			$xoptins_skins = new WP_Query( $xoptins_skins );


			/* If any xoptins_skins are in database. */
			if ( $xoptins_skins->have_posts() ) {

				/* Declaring an empty array. */
				$xo_skins_holder = [];

				/* Looping xoptins_skins to get all records. */
				while ( $xoptins_skins->have_posts() ):


					/* Making it post. */ $xoptins_skins->the_post();

					/* Making xOptin status more understandable. */
					if ( get_post_status() == 'publish' ) {
						$status = 'active';
					} else {
						$status = 'not active';
					}


					/* Getting the ID. */
					$id = get_the_ID();

					/* Getting custom fields data. */
					$custom_fields = get_post_custom( $id );


					$design_arr = null;

					/* Getting default layout arr. */
					if ( isset( $custom_fields['wpop_skin_layout'] ) ) {
						$design_arr = maybe_unserialize( $custom_fields['wpop_skin_layout']['0'] );
					}


					// $description = get_the_content();

					// if(!isset($description) && !empty($description))
					// 	$description = __('<p>This is an awesome and slick skin for Opt-in or Special Offer.</p>', 'xo');

					$title = get_the_title();

					if ( empty( $title ) ) {
						$title = __( 'Skin', 'wpoptin' );
					}

					/* Making an array of xOptin. */
					$xo_skins_holder[ $id ] = [
						'ID'            => $id,
						'title'         => $title,
						'description'   => get_the_content(),
						'status'        => $status,
						'skin_for_id'   => $skin_for_id,
						'skin_for_name' => $skin_for_name,
						'featured_img'  => get_the_post_thumbnail_url( $id, 'full' ),
						'skin_layout'   => $design_arr,

					];

				endwhile; // Loop ends here.
				/* Reseting the current query. */
				wp_reset_postdata();

			} /* If no data found. */ else {
				return __( 'No WPOptins skins found.', 'wpoptin' );
			}

			/* Globalising array to access anywhere. */
			$GLOBALS['wpoptins_skins'] = $xo_skins_holder;
			// echo "<pre>";
			// print_r($xo_skins_holder);exit;
		}/* xo_Skins Method ends here. */


	}/* Class ends here. */
	/*
	* Globalising class to get functionality on other pages.
	*/
	$GLOBALS['WPOptin_Skins'] = new WPOptin_Skins();
endif;	