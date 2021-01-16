<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $block_css, $pid;

// add more class selectors here
$classes = array();

$classes = array_merge( $classes, explode( ' ', $block_css ) );

$pull_from_website = get_field( 'pull_from_website' );
$pull_from_article = get_field( 'pull_from_article' );
$pull_field = get_field( 'pull_field' );

// get wp-content
$content = get_the_content( NULL, FALSE, $pid );

$pull_filter = get_field( 'pull_filter' );

// get this specific block
$get_this_block = get_field( 'pull_block' );


/*
			Here's the priority on sources:
			1. pull from same site
			2. pull from multisite
			3. pull with REST (technically number 2 is probably gonna use this)

			Here's the priority on content:
			1. pull the_content
			2. pull a specific block from the_content
			3. pull a setup-log block from another location and placing it in the destination
			4. pull a custom_field (if needed)
*/

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
		'url'			=>	$pull_from_website,
		'id'			=>	$pull_from_article,
		'field'			=>	$pull_field,
		'block'			=>	$get_this_block,
		'api_url_ext' 	=> 	get_field( 'pull_api_extension' ),
    	'post_type' 	=> 	get_field( 'pull_post_type' ),
    	'version' 		=> 	get_field( 'pull_rest_version' ),
    	'pull_filter'	=>	$pull_filter,
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
	$out = 'SETUP-PULL | Template: '.get_field( 'pull_layout' );
} else {

	$showsource = get_field( 'pull_source' );
	if( !empty( $showsource ) && $showsource == 'show' ) {

		// show this optional field
		if( !empty( $pull_field ) ) {
			$show_the_field_source = '<div>Field(s): '.$pull_field.'</div>';
		} else {
			$show_the_field_source = '';
		}

		$out = $out.'<div style="background-color:gray;">
					<div>Source: <a href="'.$pull_from_website.'" target="_blank">'.$pull_from_website.'</a></div>
					<div>Article: '.$pull_from_article.'</div>
					'.$show_the_field_source.'
				</div>';

	}

}

// OUTPUT
echo '<div class="'.join( ' ', $classes ).'"><div class="module-wrap">'.$out.'</div></div>';
