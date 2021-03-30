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

// set variables
$pull_from = get_field( 'pull_from' )[0]; // POST ID
$pull_html_view = get_field( 'pull_html_view' );
if( empty( $pull_html_view ) ) {
	$pull_html_view = 'default-view.html';
}
// get this specific block
$get_this_block = get_field( 'pull_block' );

if( empty( $get_this_block ) ) {

	$out[ 'output' ] = setup_pull_the_whole_content( $pull_from );
	$out[ 'output_pre' ] = '<pre id="copyme">'.setup_pull_display_code_pre( $out[ 'output' ] ).'</pre>';

} else {

	$out[ 'output' ] = setup_pull_parse_blocks( get_the_content( NULL, FALSE, $pull_from ), $get_this_block );
	$out[ 'output_pre' ] = '<pre id="copyme">'.setup_pull_display_code_pre( $out[ 'output' ] ).'</pre>';

}


// validate pulled information and show controls when logged in
if( is_array( $out ) && array_key_exists( 'output_pre', $out ) ) {

	// set admin buttons and raw code
	$btn_ops = '<div>
					<button class="buttons" id="show_raw__'.$block_counter.'">Show Raw</button>
					<button class="buttons" id="copy_to_clipboard__'.$block_counter.'">Copy to clipboard</button>
				</div>
				<div class="hidden box-it" id="output_pre_container__'.$block_counter.'">'.$out[ "output_pre" ].'</div>';
				// <button class="buttons" onclick="BtncopyToClipboard(\'#copyme\')">Copy to clipboard</button>

} else {

	$btn_ops = '';

}


$showsource = get_field( 'pull_source' );
if( $showsource == 'show' && is_user_logged_in() && is_array( $out ) ) {

	$pull_from_perma = get_the_permalink( $pull_from );

	// DATE | catch error if no information available
	if( !count( $out ) ) {
		$timestamp = 'No date available';
		$link_stamp = $pull_from_article;
	} else{
		//$timestamp = date( 'ymd', strtotime( $out[ 'mod_date' ] ) );
		$timestamp = get_the_modified_date( 'ymd', $pull_from );
		$link_stamp = '<a href="'.$pull_from_perma.'" target="_blank">'.$pull_from.'</a>';
	}
	
	// LINK | catch error if no information available
	/*if( empty( $out[ 'entry_link' ] ) ) {
		$link_stamp = $pull_from_article;
	} else {
		$link_stamp = '<a href="'.$out[ "entry_link" ].'" target="_blank">'.$pull_from_article.'</a>';
	}*/

	// SET the URL
	$this_url = urldecode( $pull_from_perma ); // CLEAN UP URL
	$this_url = preg_replace( "{/$}", "", $this_url ); // REMOVE THE / AT THE END OF THE URL
	$this_url = preg_replace( "#^[^:/.]*[:/]+#i", "", $this_url ); // REMOVE THE HTTP://WWW or HTTPS

	//$outs = $btn_ops.'<hr />'.$out[ 'output' ].'<hr />'.$timestamp.' | '.$link_stamp.' | '.$this_url;
	$replace_array = array(
				'{@buttons}' 			=> '<div class="pull-buttons">'.$btn_ops.'</div>',
				'{@output}' 			=> $out[ 'output' ],
				'{@date_modified}'		=> '<div class="pull-datemod">'.$timestamp.'</div>',
				'{@slugid}'				=> '<div class="pull-slugid">'.$link_stamp.'</div>',
				'{@url}'				=> '<div class="pull-url">'.$this_url.'</div>',
				'{@group_start}'		=> '<div class="fontsize-smaller">
												<span class="fontsize-tiny" style="background-color: orange;color:#fff;padding:2px 5px;border-radius:5px;font-weight:600;margin-right:0.5rem;">START</span>
												'.$timestamp.'
												&nbsp;'.$this_url.'
												&nbsp;'.$link_stamp.'
											</div>',
				'{@group_end}'			=> '<div class="fontsize-smaller">
												<span class="fontsize-tiny" style="background-color: orange;color:#fff;padding:2px 5px;border-radius:5px;font-weight:600;margin-right:0.5rem;">END</span>
												'.$link_stamp.'
											</div>',
			);

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
