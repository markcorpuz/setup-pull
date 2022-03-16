 <?php

global $bars;

$mfunc = new SetupPullMain();

// CONTAINER (WRAP) | CSS
$cont_class = $mfunc->setup_array_validation( 'wrap_sel', $bars );
// CONTAINER (WRAP) | INLINE STYLE
$cont_style = $mfunc->setup_array_validation( 'wrap_sty', $bars );

/**
 * CONTENT | START
 */

$classes = 'item-pulldetails '.$mfunc->setup_array_validation( 'block_class', $bars ).' '.$cont_class;

$styles = ''; // add your styles here without the HTML tag STYLE

if( !empty( $cont_style ) || !empty( $styles ) ) {
	$inline_style = ' style="'.$cont_style.$styles.'"';	
} else {
	$inline_style = '';
}

// WRAP | OPEN
echo '<div class="'.$classes.'"'.$inline_style.'>';

	// ACF | title
	$acf_title = $mfunc->setup_array_validation( "title", $bars );
	if( !empty( $acf_title ) ) {
		echo '<div class="item-acf-title"><b>DETAILS | TITLE:</b> '.$acf_title.'</div>';
	}

	// ACF | Credit
	$acf_credit = $mfunc->setup_array_validation( "credit", $bars );
	if( !empty( $acf_credit ) ) {
		echo '<div class="item-acf-credit"><b>DETAILS | CREDIT:</b> '.$acf_credit.'</div>';
	}

	// ACF | Summary
	$acf_summary = $mfunc->setup_array_validation( "summary", $bars );
	if( !empty( $acf_summary ) ) {
		echo '<div class="item-acf-summary"><b>DETAILS | SUMMARY:</b> '.$acf_summary.'</div>';
	}

// WRAP | CLOSE
echo '</div>';