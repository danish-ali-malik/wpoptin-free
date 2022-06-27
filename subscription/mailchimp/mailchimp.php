<?php

//======================================================================
// MailChimp API
//======================================================================

class MCAPI {

	private $api_key;
	private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';

	const TIMEOUT = 10;

	/*  SSL Verification
		Read before disabling:
		http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
	*/
	public $verify_ssl = true;

	public $api_response = [];

	private $request_successful = false;
	private $last_error = '';
	private $last_response = [];
	private $last_request = [];

	/**
	 * Create a new instance
	 *
	 * @param string $api_key Your MailChimp API key
	 * @param string $api_endpoint Optional custom API endpoint
	 *
	 * @throws \Exception
	 */
	public function __construct( $api_key, $api_endpoint = null ) {
		if ( ! function_exists( 'curl_init' ) || ! function_exists( 'curl_setopt' ) ) {
			throw new \Exception( "cURL support is required, but can't be found." );
		}

		$this->api_key = $api_key;

		if ( $api_endpoint === null ) {
			if ( strpos( $this->api_key, '-' ) === false ) {
				$this->api_response = [
					false,
					"Invalid MailChimp API key supplied.",
				];
			}
			list( , $data_center ) = explode( '-', $this->api_key );
			$this->api_endpoint = str_replace( '<dc>', $data_center, $this->api_endpoint );

		} else {
			$this->api_endpoint = $api_endpoint;

			$this->api_response = [ true, "Successful" ];
		}

		$this->last_response = [ 'headers' => null, 'body' => null ];
	}


	public function get_lists() {
		$lists_url = $this->api_endpoint . '/lists/?apikey=' . $this->api_key;

		$xo_mailchimp_lists = wpop_get_api_response( $lists_url );

		// echo '<pre>'; print_r($xo_mailchimp_lists);exit();

		return $xo_mailchimp_lists;
	}

	public function add_subscribers( $value, $listId ) {

		$apiKey = $this->api_key;

		$memberId   = md5( strtolower( $value ) );
		$dataCenter = substr( $apiKey, strpos( $apiKey, '-' ) + 1 );
		$url        = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;


		$json = json_encode( [
			'email_address'   => $value,
			'status'          => 'subscribed',
			// "subscribed","unsubscribed","cleaned","pending"
			'update_existing' => false,

		] );

		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $apiKey );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );

		$result   = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		return json_decode( $result );
	}

	/**
	 * @return string The url to the API endpoint
	 */
	public function getApiEndpoint() {
		return $this->api_endpoint;
	}
}