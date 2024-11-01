=== Use Bunny DNS ===
Contributors: bouk
Donate link: 
Tags: Bunny CDN, Bunny DNS, DNS Proxy
Requires at least: 5.3
Tested up to: 6.6
Requires PHP: 7.0
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Handles automatic purge of CDN pull zone on content change
 
== Description ==
 
In May 2022 Bunny.net introduced a new service called [Bunny DNS](https://bunny.net/blog/transforming-internet-routing-introducing-bunny-dns/) which is currently still in Beta, but available for testing on invitation.

Use Bunny DNS plugin allows for smooth integration with Bunny DNS product. When integrating WordPress site with Bunny DNS where proxy is enabled, you may want to purge the CDN cache on different content updates to allow for new content reaching out your target audience as soon as possible.

When installing and enabling this plugin, your CDN pull zone will be automatically purged anytime the content on your site is updated.


== Installation ==
 
1. Upload the contents of this .zip file into '/wp-content/plugins/use-bunny-dns' on your WordPress installation, or via the 'Plugins->Add New' option in the Wordpress dashboard.
1. Activate the plugin via the 'Plugins' option in the WordPress dashboard.
1. Once activated, there needs to be added following constants into wp-config.php:
    * BUNNY_PULL_ZONE_ID (required) - ID of the pull zone site is being served through
    * BUNNY_API_KEY (required) - API key for secure communication with [Bunny API](https://docs.bunny.net/reference/bunnynet-api-overview), allowing to interact with the cache programatically
    * BUNNY_ENABLE_LOG (optional) - when set to true, there's created file called cdn-purge.log in plugin's directory allowing to check it's correct functionality
 
== Frequently Asked Questions ==
 
== Screenshots ==

== Changelog ==

= 1.1.1 =
* Fixing small bug in wpController

= 1.1.0 =
* Extend API call timeout from 5s to 10s
* Introducing [CDN-Tag header](https://bunny.net/blog/introducing-tag-based-cdn-cache-purging/) to all dynamic requests allowin to purge tagged requests only

= 1.0.0 =
* First stable release of the plugin