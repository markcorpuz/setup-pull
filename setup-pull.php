<?php
/**
 * Plugin Name: Setup Pull
 * Description: Utilize custom Guttenburg block to pull post entry fields.
 * Version: 2.0.0
 * Author: Jake Almeda & Mark Corpuz
 * Author URI: https://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/*
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
*/

// include required functions that needs to be executed in the main directory
class SetupPullVariables {

    // simply return this plugin's main directory
    public function setup_plugin_dir_path() {

        return plugin_dir_path( __FILE__ );

    }

}


// include file
include_once( 'lib/setup-pull-acf.php' );
include_once( 'lib/setup-pull-functions.php' );
//include_once( 'lib/setup-pull-variables.php' );
