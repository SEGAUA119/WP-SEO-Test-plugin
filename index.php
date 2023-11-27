<?php
/*
Plugin Name: Search posts by SEO
Description: Test task for company
Version: 1.0
Requires at least: 5.8
Requires PHP: 8.1
Author: Serhii Shumakov
License: GPLv2 or later
Text Domain: wp-seo-search
*/

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( ! defined( 'WPSEO_SEARCH_FILE' ) ) {
    define( 'WPSEO_SEARCH_FILE', __FILE__ );
}

if ( ! defined( 'WPSEO_SEARCH_PATH' ) ) {
    define( 'WPSEO_SEARCH_PATH', plugin_dir_path( WPSEO_SEARCH_FILE ) );
}

if ( ! defined( 'WPSEO_SEARCH_BASENAME' ) ) {
    define( 'WPSEO_SEARCH_BASENAME', 'wp-seo-search' );
}

if( ! defined( 'WPSEO_SEARCH_URL' ) ) {
    define( 'WPSEO_SEARCH_URL', plugin_dir_url( WPSEO_SEARCH_FILE ) );
}

require_once WPSEO_SEARCH_PATH . '/inc/wp-seo-search.php';
