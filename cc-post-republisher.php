<?php
/*
Plugin Name: Creative Commons Post Republisher
Plugin URI: https://davidwolfpaw.com/
Description: Place a widget on post pages or after post content with a link to the Creative Commons license that you've applied to your site, as well as a republisher window that makes it easier for others to share your content while maintaining your licensing.
Version: 2.1.0
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

define( 'CCPR_VERSION', '2.1.0' );
define( 'CCPR_PLUGIN_NAME', 'cc-post-republisher' );
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
	load_plugin_textdomain( 'cc-post-republisher', false, basename( __DIR__ ) . 'languages/' );
}
add_action( 'init', 'cc_post_republisher_init' );

/**
 * Register block scripts and styles.
 * If the full site editor is not available, fallback
 */
function cc_post_republisher_register_block() {
	wp_register_script(
		'cc-post-republisher-block',
		plugins_url( 'license-block/block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
		CCPR_VERSION,
		true
	);

	wp_register_script(
		'cc-post-republisher-modal',
		plugins_url( 'license-block/modal.js', __FILE__ ),
		array( 'jquery', 'wp-blocks', 'wp-element', 'wp-editor' ),
		CCPR_VERSION,
		true
	);

	wp_register_style(
		'cc-post-republisher-style',
		plugins_url( 'assets/css/cc-post-republisher.css', __FILE__ ),
		array(),
		CCPR_VERSION
	);

	register_block_type(
		'cc/post-republisher',
		array(
			'editor_script' => 'cc-post-republisher-block',
			'style'         => 'cc-post-republisher-style',
		)
	);

	wp_enqueue_script( 'cc-post-republisher-modal' );
}
add_action( 'init', 'cc_post_republisher_register_block' );

// Initialize the plugin
add_action(
	'plugins_loaded',
	function () {
		new CC_Post_Republisher();
	}
);
