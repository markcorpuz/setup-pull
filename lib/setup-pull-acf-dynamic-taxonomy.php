<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


$dts_now = new DynamicTaxonomySelect();
class DynamicTaxonomySelect {

    public function display_them_please() {
        var_dump( get_taxonomies() );
    }
    
    /**
     * Handle the display
     */
    public function __construct() {

        // Enqueue scripts
        if ( is_admin() ) {

            //add_action( 'wp_loaded', array( $this, 'display_them_please' ) );

        }

    }

}


/**
 * RESOURCES


https://gist.github.com/mgburns/23ffdbd20c580c715c47

https://www.mootpoint.org/blog/create-acf-field-programmatically-permanently-in-database/



*/

