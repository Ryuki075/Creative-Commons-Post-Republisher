<?php
/**
 * Place a widget on post pages or after post content with a link to the Creative Commons license that you've applied to your site, as well as a republisher window that makes it easier for others to share your content while maintaining your licensing.
 *
 * @package Creative Commons Post Republisher
 */

/*
Plugin Name: Creative Commons Post Republisher
Plugin URI: https://orangeblossommedia.com/
Description: Place a widget on post pages or after post content with a link to the Creative Commons license that you've applied to your site, as well as a republisher window that makes it easier for others to share your content while maintaining your licensing.
Version: 1.0.0
Author: Orange Blossom Media
Author URI: https://orangeblossommedia.com/plugins
License: GPLv3 or later
Text Domain: cc-post-republisher
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 3
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Copyright 2017 Orange Blossom Media, Inc.
*/

// Make sure we don't expose any info if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'CCPR_VERSION', '1.0.0' );
define( 'CCPR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCPR_ASSET_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

require CCPR_PLUGIN_DIR . 'class-cc-post-republisher.php';

if ( is_admin() ) {
	require CCPR_PLUGIN_DIR . 'class-cc-post-republisher-admin.php';
	require CCPR_PLUGIN_DIR . 'class-cc-post-republisher-meta-box.php';
}

register_activation_hook( __FILE__, 'activate_cc_post_republisher' );



/**
 * Activates the plugin
 */
function activate_cc_post_republisher() {

	CC_Post_Republisher_Admin::default_general_settings();

}
