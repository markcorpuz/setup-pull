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

	echo '<a class="item-entry-link" href="'.get_the_permalink( $pid ).'">';

		// featured media/image
		$feat_img = get_the_post_thumbnail_url( $pid, "medium-large" );
		if( !empty( $feat_img ) && !empty( $mfunc->setup_field_control_validation( 'featured_media', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
			echo '<figure class="item-thumbnail"><img src="'.$feat_img.'" border="0" /></figure>';
		}
		//$feat_img = get_the_post_thumbnail_url( $pid, "medium-large" ); // do not remove line 33
		if( !empty( $feat_img ) && !empty( $mfunc->setup_field_control_validation( 'featured_media_link', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
			//echo '<figure class="item-thumbnail"><a href="'.get_the_permalink( $pid ).'"><img src="'.$feat_img.'" border="0" /></a></figure>';
			echo '<figure class="item-thumbnail"><img src="'.$feat_img.'" border="0" /></figure>';
		}

		echo '<div class="items-info">';

			// wp-title
			$wp_title = get_the_title( $pid );
			if( !empty( $wp_title ) && !empty( $mfunc->setup_field_control_validation( 'title', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
				//echo '<div class="item-title"><a href="'.get_the_permalink( $pid ).'">'.$wp_title.'</a></div>';
				echo '<div class="item-title">'.$wp_title.'</div>';	
			}
	
			// wp-content
			$wp_content = $mfunc->setup_pull_apply_filters_to_content( $pid );
			if( !empty( $wp_content ) && !empty( $mfunc->setup_field_control_validation( 'content', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
				echo '<div class="item-content">'.$wp_content.'</div>';
			}

			// wp-excerpt
			$wp_excerpt = get_the_excerpt( $pid );
			if( !empty( $wp_excerpt ) && !empty( $mfunc->setup_field_control_validation( 'excerpt', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
				echo '<div class="item-excerpt">'.$wp_excerpt.'</div>';
			}

			// date modified
			if( !empty( $mfunc->setup_field_control_validation( 'modified', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
				echo '<div class="item-modified">'.get_the_modified_date( "F j, Y, g:i a", $pid ).'</div>';
			}
			
			// date published
			if( !empty( $mfunc->setup_field_control_validation( 'date_published', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
				echo '<div class="item-published">'.get_the_date( "F j, Y, g:i a", $pid ).'</div>';
			}

			//echo '<div class="item-cta"><a href="'.get_the_permalink( $pid ).'">MORE INFO</a></div>';
			echo '<div class="item-cta">MORE INFO</div>';

			// SOURCE
			$e_source = $mfunc->setup_array_validation( "sources", $bars );
			if( !empty( $e_source ) && $e_source === TRUE ) {
				/*$link = '<a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a>';
				echo '<div class="item-cta">'.$link.'</div>';*/
				echo '<div class="item-cta">'.get_the_title( $pid ).'</div>';
			}

		echo '</div>';

	echo '</a>';

// WRAP | CLOSE
echo '</div>';

