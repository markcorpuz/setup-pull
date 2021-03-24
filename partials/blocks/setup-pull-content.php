<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// set global variable for css selectors
global $block_css;

// add more class selectors here
$classes = array();

$classes = array_merge( $classes, explode( ' ', $block_css ) );

// https://data.basestructure.com/
//types-of-text-content
$pull_from_website = get_field( 'pull_from_website' );
$pull_from_article = get_field( 'pull_from_article' );
$pull_filter = get_field( 'pull_filter' );
/*
		New Block
		Update: YYMMDD 00:00pm | slug | data.basestructure.com

		-----------------

		Remove:

		Search by Title

		-----------------

		Show actual HTML code

		-----------------

		layout-capsule | 817
*/

$args = array(
	'url'			=>	$pull_from_website,
	'id'			=>	$pull_from_article,
	'pull_filter'	=>	$pull_filter,
);

$out = setup_pull_rest_api( $args );
//var_dump($out);
$showsource = get_field( 'pull_source' );
if( $showsource == 'show' && is_user_logged_in() ) {

	// DATE | catch error if no information available
	if( empty( $out[ 'mod_date' ] ) ) {
		$timestamp = 'No date available';	
	} else{
		$timestamp = date( 'ymd', strtotime( $out[ 'mod_date' ] ) );
	}
	
	// LINK | catch error if no information available
	if( empty( $out[ 'entry_link' ] ) ) {
		$link_stamp = $pull_from_article;
	} else {
		$link_stamp = '<a href="'.$out[ "entry_link" ].'" target="_blank">'.$pull_from_article.'</a>';
	}

	// SET the URL
	$this_url = urldecode( $pull_from_website ); // CLEAN UP URL
	$this_url = preg_replace( "{/$}", "", $this_url ); // REMOVE THE / AT THE END OF THE URL
	$this_url = preg_replace( "#^[^:/.]*[:/]+#i", "", $this_url ); // REMOVE THE HTTP://WWW or HTTPS

	$outs = $out[ 'output' ].'<hr />'.$timestamp.' | '.$link_stamp.' | '.$this_url;

} else {

	$outs = $out[ 'output' ];

}

// OUTPUT
echo '<div class="'.join( ' ', $classes ).'"><div class="module-wrap entry-content">'.$outs.'</div></div>';
