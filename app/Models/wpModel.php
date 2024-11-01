<?php

namespace Bunny\Dns\Models;

/**
 * 
 */
class wpModel
{	
	/**
	 * [makeCdnApiRequest description]
	 * @param  [type] $pullZone [description]
	 * @param  [type] $apiKey   [description]
	 * @return [type]           [description]
	 */
	public function makeCdnApiRequest( $pullZone, $apiKey )
	{
		$response = wp_remote_post( 'https://api.bunny.net/pullzone/' . $pullZone . '/purgeCache', [
		    'body' 		=> '{"CacheTag":"WP_Response"}',
		    'headers' 	=> [
		        'AccessKey' 	=> $apiKey,
		    	'Content-Type' 	=> 'application/json',
		    ],
		    'timeout' 	=> 10
		]);

		if ( is_wp_error( $response ) ) 
		{
			$data = [
				'status'	=> false,
				'message' 	=> $response->get_error_message()
			];
		}

		else 
		{
			$httpCode 	= wp_remote_retrieve_response_code( $response );
			$message 	= wp_remote_retrieve_response_message( $response );

			$data = [
				'status'	=> true,
				'message' 	=> $httpCode . ' [' . $message . ']'
			];
		}

		return $data;
	}	
}