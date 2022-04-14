 <?php

global $bars;

$mfunc = new SetupPullMain();

// class
$cs = array(
	'manual_class'		=> 'item-pull-remote',
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

	// TITLE
	$wp_title = $mfunc->setup_array_validation( "title", $bars );
	if( !empty( $wp_title ) ) {
		echo '<div class="item-title"><b>WP TITLE:</b> '.$wp_title.'</div>';
	}

	// CONTENT
	$wp_content = $mfunc->setup_array_validation( "content", $bars );
	if( !empty( $wp_content ) ) {
		echo '<div class="item-content"><b>WP CONTENT:</b> '.$wp_content.'</div>';
	}

	// EXCERPT
	$wp_excerpt = $mfunc->setup_array_validation( "excerpt", $bars );
	if( !empty( $wp_excerpt ) ) {
		echo '<div class="item-excerpt"><b>WP EXCERPT:</b> '.$wp_excerpt.'</div>';
	}

	// FEATURED IMAGE
	$wp_featured_image = $mfunc->setup_array_validation( "featured-image", $bars );
	if( !empty( $wp_featured_image ) ) {
		echo '<div class="item-featured-image"><b>WP FEATURED IMAGE:</b> '.$wp_featured_image.'</div>';
	}

	// DATE MODIFIED
	$wp_date_modified = $mfunc->setup_array_validation( "date-modified", $bars );
	if( !empty( $wp_date_modified ) ) {
		echo '<div class="item-date-modified"><b>WP DATE MODIFIED:</b> '.date( 'd M Y, h:i a', strtotime( $wp_date_modified ) ).'</div>';
	}

	// SOURCE
	$e_source = $mfunc->setup_array_validation( "sourced", $bars );
	if( !empty( $e_source ) ) {
		$link = '<a href="'.$e_source.'" target="_blank">'.$e_source.'</a>';
		echo '<div class="item-entry-source"><b>SOURCE:</b> '.$link.'</div>';
	}

	echo '<div class="item-innerblock"><InnerBlocks /></div>';

// WRAP | CLOSE
echo '</div>';