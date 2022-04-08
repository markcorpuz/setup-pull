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
$classes = $mfunc->setup_pull_combine_classes( $cs );

// styles
$ss = array(
	'manual_style'		=> '',
	'item_style' 		=> $mfunc->setup_array_validation( 'wrap_sty', $bars ),
);
$inline_style = $mfunc->setup_pull_combine_styles( $ss );

/**
 * CONTENT | START
 */

// WRAP | OPEN
echo '<div class="'.$classes.'"'.$inline_style.'>';

	// pull wp title
	echo '<div class="item-title-native"><b>WP TITLE:</b> '.get_the_title( $pid ).'</div>';

	// pull wp-content
	echo '<div class="item-content-native"><b>WP CONTENT:</b> ';
	echo $mfunc->setup_pull_apply_filters_to_content( $pid );
	echo '</div>';

	// ACF | Title
	$acf_title = $mfunc->setup_array_validation( "title", $bars );
	if( !empty( $acf_title ) ) {
		echo '<div class="item-acf-title"><b>ACF TITLE:</b> '.$acf_title.'</div>';
	}

	// ACF | Link
	$acf_link = $mfunc->setup_array_validation( "link", $bars );
	if( !empty( $acf_link ) ) {
			
		$link_title = $mfunc->setup_array_validation( "title", $acf_link );
		$link_url = $mfunc->setup_array_validation( "url", $acf_link );
		$link_target = $mfunc->setup_array_validation( "target", $acf_link );
		if( empty( $link_target ) ) {
			$target = '';
		} else {
			$target = ' target="'.$link_target.'"';
		}

		echo '<div class="item-acf-link"><b>ACF LINK w/ FEATURED IMAGE</b>';
			echo '<a href="'.$link_url.'"'.$target.'><img src="'.get_the_post_thumbnail_url( $pid, "medium" ).'" border="0" /></a>';
		echo '</div>';

	}

	// ACF | Credit
	$acf_credit = $mfunc->setup_array_validation( "credit", $bars );
	if( !empty( $acf_credit ) ) {
		echo '<div class="item-acf-credit"><b>ACF CREDIT:</b> '.$acf_credit.'</div>';
	}

	// ACF | Summary
	$acf_summary = $mfunc->setup_array_validation( "summary", $bars );
	if( !empty( $acf_summary ) ) {
		echo '<div class="item-acf-summary"><b>ACF SUMMARY:</b> '.$acf_summary.'</div>';
	}

	// SOURCE
	$e_source = $mfunc->setup_array_validation( "sources", $bars );
	if( !empty( $e_source ) ) {
		$link = '<a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a>';
		echo '<div class="item-entry-source"><b>SOURCE:</b> '.$link.'</div>';
	}

// WRAP | CLOSE
echo '</div>';