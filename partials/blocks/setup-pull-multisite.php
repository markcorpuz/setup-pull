 <?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// set global variable for css selectors
global $block_css, $block_counter;
$block_counter++;
$out = array();

// add more class selectors here
$classes = array();
$classes = array_merge( $classes, explode( ' ', $block_css ) );

