<?php
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//======================================================================
// Main class of all Xoptins Accounts
//======================================================================

if ( ! class_exists( 'WPOptins_Accounts' ) ):
	class WPOptins_Accounts {

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
			add_action( 'init', [ $this, 'wpop_accounts' ], 100 );
		}/* __construct Method ends here. */

		/*
		* xo_accounts will get the all accounts.
		* Returns accounts  object.
		*/
		public function wpop_accounts() {

			/*
			* Arguments for WP_Query().
			*/

			$xoptins_accounts = [
				'posts_per_page' => 1000,
				'post_type'      => 'wpop_accounts',
				'post_status'    => [ 'publish', 'draft', 'pending' ],
			];


			$lists_array = [];

			/*
			* Quering all active xOptins.
			* WP_Query() object of wp will be used.
			*/
			$xoptins_accounts = new WP_Query( $xoptins_accounts );


			/* If any xoptins_accounts are in database. */
			if ( $xoptins_accounts->have_posts() ) {

				/* Declaring an empty array. */
				$xo_accounts_holder = [];


				/* Looping xoptins_accounts to get all records. */
				while ( $xoptins_accounts->have_posts() ):

					$lists_array = [];

					/* Making it post. */
					$xoptins_accounts->the_post();

					/* Making xOptin status more understandable. */
					if ( get_post_status() == 'publish' ) {
						$status = 'active';
					} else {
						$status = 'not active';
					}


					/* Getting the ID. */
					$id = get_the_ID();

					$custom_fields = get_post_custom( $id );
					$content       = maybe_unserialize( get_the_content() );


					if ( isset( $custom_fields['_wpop_service_provider'] ) ) {
						$service_provider = $custom_fields['_wpop_service_provider']['0'];
					}

					// 	echo "<pre>";
					// print_r($id);
					// exit();


					switch ( $service_provider ) {
						case 'mailchimp':

							$api    = new MCAPI( base64_decode( $content['api_key'] ) );
							$retval = $api->get_lists();

							$sp_list = $retval->lists;
							break;

						case 'constant_contact':

							$api_url = esc_url_raw( 'https://api.constantcontact.com/v2/lists?api_key=' . $content['api_key'] );

							$requested_content = wp_remote_get( $api_url, [
								'timeout' => 30,
								'headers' => [ 'Authorization' => 'Bearer ' . base64_decode( $content['access_token'] ) ],
							] );

							$response_code = wp_remote_retrieve_response_code( $requested_content );

							if ( ! is_wp_error( $requested_content ) && $response_code == 200 ) {
								$responsed_data = wp_remote_retrieve_body( $requested_content );
								$sp_list        = json_decode( $responsed_data, true );

							}

							// echo "<pre>"; print_r($sp_list);exit();
							break;

						case 'campaign_monitor':

							$auth   = [
								'access_token'  => base64_decode( $content['api_key'] ),
								'refresh_token' => base64_decode( $content['refresh_access_token'] ),
							];
							$wrap   = new CS_REST_General( $auth );
							$result = $wrap->get_clients();
							if ( ! $result->was_successful() ) {
								# If you receive '121: Expired OAuth Token', refresh the access token
								if ( $result->response->Code == 121 ) {
									list( $new_access_token, $new_expires_in, $new_refresh_token ) = $wrap->refresh_token();
									# Save $new_access_token, $new_expires_in, and $new_refresh_token
								}
								# Make the call again
								$result = $wrap->get_clients();
							}


							$clients_array = $result->response;

							if ( $result->response ) {

								foreach ( $clients_array as $client => $client_details ):

									$all_clients_id[] = $client_details->ClientID;
								endforeach;


								if ( ! empty( $all_clients_id ) ) :


									foreach ( $all_clients_id as $client ):


										$wrap       = new CS_REST_Clients( $client, $auth );
										$lists_data = $wrap->get_lists();

										foreach ( $lists_data->response as $list => $single_list ) {
											$all_lists['name'] = sanitize_text_field( $single_list->Name );
											$all_lists['id']   = $single_list->ListID;

											$wrap_stats               = new CS_REST_Lists( $single_list->ListID, $auth );
											$result_stats             = $wrap_stats->get_stats();
											$all_lists['subscribers'] = sanitize_text_field( $result_stats->response->TotalActiveSubscribers );

										}

									endforeach;
								endif;


								$sp_list = [ $all_lists ];


							}

							break;

						case 'mad_mimi':

							$request_url = esc_url_raw( 'https://api.madmimi.com/audience_lists/lists.json?username=' . rawurlencode( base64_decode( $content['username'] ) ) . '&api_key=' . base64_decode( $content['api_key'] ) );

							$theme_request = wp_remote_get( $request_url, [ 'timeout' => 30 ] );

							$response_code = wp_remote_retrieve_response_code( $theme_request );


							if ( ! is_wp_error( $theme_request ) && $response_code == 200 ) {
								$theme_response = json_decode( wp_remote_retrieve_body( $theme_request ), true );
								if ( ! empty( $theme_response ) ) {
									$sp_list = $theme_response;

								}
							}


							break;

						case 'mailpoet':

							global $wpdb;
							$table_name  = $wpdb->prefix . 'wysija_list';
							$table_users = $wpdb->prefix . 'wysija_user_list';

							if ( ! class_exists( 'WYSIJA' ) ) {
								return;
							}

							$list_model      = WYSIJA::get( 'list', 'model' );
							$all_lists_array = $list_model->get( [
								'name',
								'list_id',
							], [ 'is_enabled' => '1' ] );

							if ( ! empty( $all_lists_array ) ) {
								foreach ( $all_lists_array as $key => $value ) {
									$all_lists_array[ $key ]['id'] = $value['list_id'];

									$user_model            = WYSIJA::get( 'user_list', 'model' );
									$all_subscribers_array = $user_model->get( [ 'user_id' ], [ 'list_id' => $value['list_id'] ] );

									$subscribers_count                      = count( $all_subscribers_array );
									$all_lists_array[ $key ]['subscribers'] = $subscribers_count;


								}
								$sp_list = $all_lists_array;

							}

							break;


						default:
							$sp_list = [];
							break;
					}


					if ( isset( $sp_list ) ) {
						foreach ( $sp_list as $list ) {

							$list_ID = $list->id;

							$lists_array[ $list_ID ]['name'] = $list->name;


							switch ( $service_provider ) {
								case 'mailchimp':
									$lists_array[ $list_ID ]['subscribers'] = $list->stats->member_count;
									break;

								case 'constant_contact':
									$lists_array[ $list_ID ]['subscribers'] = $list['contact_count'];
									break;

								case 'campaign_monitor':
									$lists_array[ $list_ID ]['subscribers'] = $list['subscribers'];
									break;

								case 'mad_mimi':

									$lists_array[ $list_ID ]['subscribers'] = $list['list_size'];
									break;

								case 'mailpoet':
									$lists_array[ $list_ID ]['subscribers'] = $list['subscribers'];
									break;

								default:
									$lists_array[ $list_ID ]['subscribers'] = null;
									break;
							}

						}
					}
					$service_provider = str_replace( '_', ' ', $service_provider );
					$service_provider = ucwords( $service_provider );
					if ( 'Mailpoet' == $service_provider ):
						$content['api_key'] = null;
					endif;
					/* Making an array of xOptin. */
					$xo_accounts_holder[ $id ] = [
						'ID'               => $id,
						'title'            => get_the_title(),
						'status'           => $status,
						'lists'            => $lists_array,
						'api_key'          => $content['api_key'],
						'service_provider' => $service_provider,

					];
					if ( isset( $content['access_token'] ) ):
						$xo_accounts_holder[ $id ]['access_token'] = $content['access_token'];
					endif;


				endwhile; // Loop ends here.
				/* Reseting the current query. */
				wp_reset_postdata();

			} /* If no data found. */ else {
				return __( 'No wpoptins accounts found.', 'wpoptin' );
			}

			/* Globalising array to access anywhere. */
			$GLOBALS['wpoptins_accounts'] = $xo_accounts_holder;

			// 			echo "<pre>";
			// print_r($xo_accounts_holder);
			// exit();

		}/* xo_accounts Method ends here. */


	}/* Class ends here. */
	/*
	* Globalising class to get functionality on other pages.
	*/
	$WPOptins_Accounts = new WPOptins_Accounts();
endif;