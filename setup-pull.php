<?php
/**
 * Plugin Name: Setup Pull
 * Description: Utilize custom Guttenburg block to pull post entry fields.
 * Version: 1.0.0
 * Author: Jake Almeda & Mark Corpuz
 * Author URI: https://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// include file
include_once( 'setup-pull-functions.php' );
include_once( 'setup-pull-variables.php' );


add_action( 'genesis_setup', 'setup_cta_fn', 15 );
function setup_cta_fn() {
	include_once( plugin_dir_path( __FILE__ ).'setup-pull-acf.php' );
}

// Enqueue Style
function setup_pull_enqueue_scripts() {

	// 'jquery-effects-core', 'jquery-effects-fade', 'jquery-ui-accordion'
	$scripts = array( 'jquery-ui-core', 'jquery-effects-slide' );
	foreach ( $scripts as $value ) {
		if( !wp_script_is( $value, 'enqueued' ) ) {
        	wp_enqueue_script( $value );
    	}
	}

    // last arg is true - will be placed before </body>
    wp_enqueue_script( 'setup-pull-script', plugins_url( 'js/asset.js', __FILE__ ), NULL, NULL, TRUE );
	
    // enqueue styles
    wp_enqueue_style( 'setup-pull-style', plugins_url( 'css/style.css', __FILE__ ) );

}

if ( !is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'setup_pull_enqueue_scripts', 20 );
}