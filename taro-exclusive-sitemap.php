<?php
/*
Plugin Name: Taro Exclusive Sitemap
Plugin URI: https://wordpress.org/extend/plugins/taro-exclusive-sitemap
Description: Yet another sitemap plugin with more than 200,000 posts.
Version: nightly
Author: Tarosky INC
Author URI: https://tarosky.co.jp
Text Domain: ts-esm
Domain Path: /languages
License: GPL3 or Later
*/

// Do not load directory.
defined( 'ABSPATH' ) || die();

/**
 * Initialize plugin.
 *
 * @return void
 */
function ts_esm_init() {
	load_plugin_textdomain( 'ts-esm', false, basename( __DIR__ ) . '/languages' );
	require __DIR__ . '/vendor/autoload.php';
	TaroSitemapAdmin::get_instance();
	TaroSitemapRender::get_instance();
}

// Register hook.
add_action( 'plugins_loaded', 'ts_esm_init' );
