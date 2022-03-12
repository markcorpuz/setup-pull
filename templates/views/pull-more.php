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

			---------

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

if( is_array( $out ) && array_key_exists( 'output', $out ) && empty( strip_tags( $out[ 'output' ] ) ) && empty( $log_innerblock ) ) {
	// show default notification that the block exists
	//SETUP-LOG | Template: All-In | Show: Title Summary InnerBlock
	$outs = 'SETUP-PULL | Template: '.get_field( 'pull_layout' );
} else {

	if( is_array( $out ) && array_key_exists( 'output', $out ) ) {

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

	} else {
		$outs = $out;
	}

}

// OUTPUT
echo '<div class="'.join( ' ', $classes ).'"><div class="module-wrap">'.$outs.'</div></div>';
