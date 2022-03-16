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
	echo '<div class="item-title-native"><h1 style="color:orange;"><b>WP TITLE:</b> '.get_the_title( $pid ).'</h1></div>';

// WRAP | CLOSE
echo '</div>';