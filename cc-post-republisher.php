<?php
/*
Plugin Name: Creative Commons Post Republisher
Plugin URI: https://davidwolfpaw.com/
Description: Place a widget on post pages or after post content with a link to the Creative Commons license that you've applied to your site, as well as a republisher window that makes it easier for others to share your content while maintaining your licensing.
Version: 1.4.0
Author: wolfpaw
Author URI: https://davidwolfpaw.com/plugins
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

Copyright 2017 Orange Blossom Media, LLC.
*/

// Make sure we don't expose any info if called directly
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'CCPR_VERSION', '1.4.0' );
define( 'CCPR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CCPR_ASSET_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

require_once CCPR_PLUGIN_DIR . 'class-cc-post-republisher.php';

if ( is_admin() ) {
	require_once CCPR_PLUGIN_DIR . 'class-cc-post-republisher-admin.php';
	require_once CCPR_PLUGIN_DIR . 'class-cc-post-republisher-meta-box.php';
}

register_activation_hook( __FILE__, 'activate_cc_post_republisher' );

function activate_cc_post_republisher() {

	CC_Post_Republisher_Admin::default_general_settings();

}

/**
 * Load the plugin textdomain
 */
function cc_post_republisher_init() {

	load_plugin_textdomain( 'cc-post-republisher', false, basename( dirname( __FILE__ ) ) . 'languages/' );

}
add_action( 'init', 'cc_post_republisher_init' );
