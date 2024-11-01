<?php

namespace Bunny\Dns\Controllers;

/**
 * 
 */
class wpController
{
	/**
	 * [$model description]
	 * @var [type]
	 */
	protected $model;

	/**
	 * [$logger description]
	 * @var [type]
	 */
	protected $logger;

	/**
	 * [__construct description]
	 * @param \Bunny\Dns\Models\wpModel $model [description]
	 */
	public function __construct( \Bunny\Dns\Models\wpModel $model, \Monolog\Logger $logger )
	{
		$this->model 	= $model;
		$this->logger 	= $logger;
	}

	/**
	 * [maybePurgeZone description]
	 * @param  [type] $newStatus [description]
	 * @param  [type] $oldStatus [description]
	 * @param  [type] $post      [description]
	 * @return [type]            [description]
	 */
	public function maybePurgeZone( $newStatus, $oldStatus, $post )
	{
		$transitionFrom = [ 'draft', 'pending', 'publish', 'future' ];

		if ( in_array( $oldStatus, $transitionFrom ) && $newStatus == 'publish' )
		{
			$this->logger->info( 'Starting purge on [' . $oldStatus . ' -> ' . $newStatus . ']: Triggered by Post ID: ' .  $post->ID );
			$this->purgeZone( $post->ID );


		}
	}

	/**
	 * [addCdnTag description]
	 * @param [type] $wp [description]
	 */
	public function addCdnTag( $wp )
	{
		if ( ! is_admin() )
			header( 'CDN-Tag: WP_Response' );
	}

	/**
	 * [purgeZone description]
	 * @param  [type] $postId [description]
	 * @return [type]         [description]
	 */
	private function purgeZone( $postId )
	{
    	if ( ! defined( 'BUNNY_PULL_ZONE_ID' ) || ! defined( 'BUNNY_API_KEY' ) )
			return $postId;

    	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
      		return $postId;

        if ( defined('DOING_AJAX') )
            return $postId;

        if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) )
			return $postId;

		$response = $this->model->makeCdnApiRequest( BUNNY_PULL_ZONE_ID, BUNNY_API_KEY );

		if ( $response['status'] == false )
			$this->logger->error( $response['message'] );
		else
			$this->logger->info( $response['message'] );
	}

	/**
	 * [adminNotices description]
	 * @return [type] [description]
	 */
	public function adminNotices()
	{
		if ( ! defined( 'BUNNY_PULL_ZONE_ID' ) || ! defined( 'BUNNY_API_KEY' ) )
		{
		?>
			<div class="notice notice-error is-dismissible">
        		<p>Please make sure you define BUNNY_PULL_ZONE_ID and BUNNY_API_KEY constants in wp-config.php</p>
    		</div>
    	<?php
		}
	}
}