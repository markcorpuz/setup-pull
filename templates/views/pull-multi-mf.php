 <?php

global $bars;

$mfunc = new SetupPullMain();
$pid = $mfunc->setup_array_validation( 'pid', $bars );

// class
$cs = array(
	'manual_class'		=> 'item-pullentry',
	'item_class' 		=> $mfunc->setup_array_validation( 'wrap_sel', $bars ),
	'block_class'		=> $mfunc->setup_array_validation( 'block_class', $bars ),
);
$css = $mfunc->setup_pull_combine_classes( $cs );
$classes = !empty( $css ) ? ' class="'.$css.'"' : '';

// styles
$ss = array(
	'manual_style'		=> '',
	'item_style' 		=> $mfunc->setup_array_validation( 'wrap_sty', $bars ),
);
$stayls = $mfunc->setup_pull_combine_styles( $ss );
$inline_style = !empty( $stayls ) ? ' style="'.$stayls.'"' : '';

/**
 * CONTENT | START
 */

// WRAP | OPEN
echo '<div'.$classes.$inline_style.'>';
	
	// pull wp title
	echo '<div class="item-title-native"><b>WP TITLE - MULTI:</b> '.get_the_title( $pid ).'</div>';

	// this is just an extra and can be removed
	$ta = $mfunc->setup_array_validation( 'taxonomy', $bars );
	if( !empty( $ta ) ) {
		$taxi = get_the_terms( $pid, $ta );
		echo '<div class="item-taxonomy"><b>TAXONOMY:</b> '.$taxi[ 0 ]->name.'</div>';
	}

	/*
	// pull wp-content
	echo '<div class="item-content-native"><b>WP CONTENT - MULTI:</b> ';
	echo $mfunc->setup_pull_apply_filters_to_content( $pid );
	echo '</div>';
	*/

	/*
	// ACF | title
	$acf_title = $mfunc->setup_array_validation( "title", $bars );
	if( !empty( $acf_title ) ) {
		echo '<div class="item-acf-title"><b>ACF TITLE - MULTI:</b> '.$acf_title.'</div>';
	}

	// ACF | Credit
	$acf_credit = $mfunc->setup_array_validation( "credit", $bars );
	if( !empty( $acf_credit ) ) {
		echo '<div class="item-acf-credit"><b>ACF CREDIT - MULTI:</b> '.$acf_credit.'</div>';
	}

	// ACF | Summary
	$acf_summary = $mfunc->setup_array_validation( "summary", $bars );
	if( !empty( $acf_summary ) ) {
		echo '<div class="item-acf-summary"><b>ACF SUMMARY - MULTI:</b> '.$acf_summary.'</div>';
	}
	*/
	// SOURCE
	$e_source = $mfunc->setup_array_validation( "sources", $bars );
	if( !empty( $e_source ) ) {
		$link = '<a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a>';
		echo '<div class="item-entry-source"><b>SOURCE:</b> '.$link.'</div>';
	}

// WRAP | CLOSE
echo '</div>';