 <?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $block_css;

// add more class selectors here
$classes = array();

$classes = array_merge( $classes, explode( ' ', $block_css ) );

$pull_from = get_field( 'pull_from' );

$pull_html_view = get_field( 'pull_html_view' );
if( empty( $pull_html_view ) ) {
	$pull_html_view = 'default-view.html';
}

// get this specific block
$get_this_block = get_field( 'pull_block' );

if( empty( $get_this_block ) ) {

	$outs = setup_pull_the_whole_content( $pull_from[ 0 ] );

} else {



}


$showsource = get_field( 'pull_source' );
if( $showsource == 'show' && is_user_logged_in() && is_array( $out ) ) {

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

	//$outs = $btn_ops.'<hr />'.$out[ 'output' ].'<hr />'.$timestamp.' | '.$link_stamp.' | '.$this_url;
	$replace_array = array(
				'{@buttons}' 			=> '<div class="pull-buttons">'.$btn_ops.'</div>',
				'{@output}' 			=> '<div class="pull-output">'.$out[ 'output' ].'</div>',
				'{@date_modified}'		=> '<div class="pull-datemod">'.$timestamp.'</div>',
				'{@slugid}'				=> '<div class="pull-slugid">'.$link_stamp.'</div>',
				'{@url}'				=> '<div class="pull-url">'.$this_url.'</div>',
			);

} else {

	if( is_array( $out ) && array_key_exists( 'output', $out ) ) {
		///$outs = $out[ 'output' ];
		$replace_array = array(
				'{@output}' 			=> '<div class="pull-output">'.$out[ 'output' ].'</div>',
			);
	} else {
		//$outs = $out;
		$replace_array = array(
				'{@output}' 			=> '<div class="pull-output">'.$out.'</div>',
			);
	}
	
}


// OUTPUT
echo '<div class="'.join( ' ', $classes ).'">
		<div class="module-wrap entry-content">'.
			strtr( setup_pull_get_html_template_contents( $pull_html_view ), $replace_array )
		.'</div>
	</div>';
