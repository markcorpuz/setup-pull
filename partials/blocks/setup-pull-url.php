<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// set global variable for css selectors
global $block_css, $block_counter;
$block_counter++;

// add more class selectors here
$classes = array();
$classes = array_merge( $classes, explode( ' ', $block_css ) );

// set variables
$pull_from_website = get_field( 'pull_from_website' );
$pull_from_article = get_field( 'pull_from_article' );
$pull_post_type = get_field( 'pull_post_type' );
if( $pull_post_type == 'other' ) {
	// either posts or pages
	$pull_post_type = get_field( 'pull_post_type_specific' );
}
$pull_html_view = get_field( 'pull_html_view' );
if( empty( $pull_html_view ) ) {
	$pull_html_view = 'default-view.html';
}

$args = array(
	'url'			=>	$pull_from_website,
	'id'			=>	$pull_from_article,
//	'pull_filter'	=>	$pull_filter,
	'post_type'		=>	$pull_post_type,
);
$out = setup_pull_rest_api( $args );
//var_dump( $out );


// set admin buttons and raw code
$btn_ops = '<div>
				<button class="buttons" id="show_raw__'.$block_counter.'">Show Raw</button>
				<button class="buttons" id="copy_to_clipboard__'.$block_counter.'">Copy to clipboard</button>
			</div>
			<div class="hidden box-it" id="output_pre_container__'.$block_counter.'">'.$out[ "output_pre" ].'</div>';
			// <button class="buttons" onclick="BtncopyToClipboard(\'#copyme\')">Copy to clipboard</button>


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
				'{@group_start}'		=> '<div class="fontsize-smaller" style="display:flex;flex-direction:row;">
												<span class="fontsize-tiny" style="background-color: red;color:#fff;padding:2px 5px;border-radius:5px;font-weight:600;">START</span>
												&nbsp;|&nbsp;'.$timestamp.'
												&nbsp;|&nbsp;'.$this_url.'
												&nbsp;|&nbsp;'.$link_stamp.'
											</div>',
				'{@group_end}'			=> '<div class="fontsize-smaller" style="display:flex;flex-direction:row;">
												<span class="fontsize-tiny" style="background-color: red;color:#fff;padding:2px 5px;border-radius:5px;font-weight:600;">END</span>
												&nbsp;|&nbsp;'.$link_stamp.'
											</div>',
			);
			// {@start} {@date_modified} {@url} {@slugid}
			// {@end}&nbsp;&nbsp;{@slugid}</div>
} else {

	if( is_array( $out ) && array_key_exists( 'output', $out ) ) {
		///$outs = $out[ 'output' ];
		$replace_array = array(
				'{@buttons}'			=> '',
				'{@output}' 			=> '<div class="pull-output">'.$out[ 'output' ].'</div>',
				'{@date_modified}'		=> '',
				'{@slugid}'				=> '',
				'{@url}'				=> '',
				'{@group_start}'		=> '',
				'{@group_end}'			=> '',
			);
	} else {
		//$outs = $out;
		$replace_array = array(
				'{@buttons}'			=> '',
				'{@output}' 			=> '<div class="pull-output">'.$out.'</div>',
				'{@date_modified}'		=> '',
				'{@slugid}'				=> '',
				'{@url}'				=> '',
				'{@group_start}'		=> '',
				'{@group_end}'			=> '',
			);
	}
	
}


// OUTPUT
echo '<div class="'.join( ' ', $classes ).'">
		<div class="module-wrap entry-content">'.
			strtr( setup_pull_get_html_template_contents( $pull_html_view ), $replace_array )
		.'</div>
	</div>';
