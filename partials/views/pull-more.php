<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $block_css, $pid;

// add more class selectors here
$classes = array();

$classes = array_merge( $classes, explode( ' ', $block_css ) );

// get wp-content
$content = get_the_content( NULL, FALSE, $pid );

$pull_filter = get_field( 'pull_filter' );

// get this specific block
$get_this_block = get_field( 'pull_block' );


// PULL FROM SAME SITE
if( $pull_filter == 'local' ) {

	if( empty( $get_this_block ) ) {

		// PULL ALL WP-CONTENT
		$out = setup_pull_the_whole_content( $pid );

	} else {

		// PULL SPECIFIC BLOCK FROM WP-CONTENT
		$out = setup_pull_parse_blocks( $content, $get_this_block );

	}

}


// PULL FROM WITHIN MULTISITE
if( $pull_filter == 'subsite' ) {

}


// PULL FROM EXTERNAL SITE
if( $pull_filter == 'rest' ) {

	$args = array(
		'url'			=>	get_field( 'pull_from_website' ),
		'id'			=>	get_field( 'pull_from_article' ),
		'field'			=>	get_field( 'pull_field' ),
		'block'			=>	$get_this_block,
		'api_url_ext' 	=> 	get_field( 'pull_api_extension' ),
    	'post_type' 	=> 	get_field( 'pull_post_type' ),
    	'version' 		=> 	get_field( 'pull_rest_version' ),
	);

	$out = setup_pull_rest_api( $args );

}

/*if( empty( strip_tags( $out ) ) && empty( $log_innerblock ) ) {
// container wrap
echo '<div class="'.join( ' ', $classes ).'">';

echo '</div>';*/
if( empty( strip_tags( $out ) ) && empty( $log_innerblock ) ) {
	// show default notification that the block exists
	//SETUP-LOG | Template: All-In | Show: Title Summary InnerBlock
	$out = 'SETUP-PULL | Template: '.get_field( 'pull_layout' ).' | Show: (Jake show all fields that are selected)';
}

// OUTPUT
echo '<div class="'.join( ' ', $classes ).'"><div class="module-wrap">'.$out.'</div></div>';
