<?php
/*
Plugin Name: Use Bunny DNS
Description: Handles automatic purge of CDN pull zone
Version: 1.1.1
Author: Petr Paboucek -aka- BoUk
Author URI: https://wpadvisor.co.uk
Text Domain: use-bunny-dns
*/

if ( ! defined( 'ABSPATH' ) ) 
    exit;

define( 'BDNS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDNS_PLUGIN_URL', 	plugin_dir_url( __FILE__ ) );

/**
 * Load required files
 */
require BDNS_PLUGIN_PATH . "vendor/autoload.php";

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

$logger     = new Logger( 'cdn-purge' );
$logLevel 	= ( defined( 'BUNNY_ENABLE_LOG' ) && BUNNY_ENABLE_LOG ) ? $logger::DEBUG : $logger::EMERGENCY;

$dateFormat = "Y-m-d H:i:s";
$output     = "[%datetime%] %channel%.%level_name%: %message%\n";
$formatter  = new LineFormatter($output, $dateFormat);

$stream     = new StreamHandler( BDNS_PLUGIN_PATH . '/cdn-purge.log', $logLevel );
$stream->setFormatter( $formatter );

$logger->pushHandler( $stream );

$model 		= new \Bunny\Dns\Models\wpModel();
$controller = new \Bunny\Dns\Controllers\wpController( $model, $logger );

/**
 * Make sure setup is correct
 */
add_action( 'admin_notices', [
				$controller,
				'adminNotices' 
			]);

/**
 * Purge pull zone depending on different post transition status
 *
 */
add_action( 'transition_post_status', [
				$controller,
				'maybePurgeZone'
			], 
			10, 3 
		);

/**
 * Add CDN-Tag header to all dynamic requests allowing to purge tagged requests only and keep static assets in CDN cache 
 * without the need of refetching after the purge
 */
add_action( 'send_headers', [
				$controller,
				'addCdnTag'
			], 
			99, 1 
		);