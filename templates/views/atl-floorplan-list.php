<?php

global $bars;

$mfunc = new SetupPullMain();
$pid = $mfunc->setup_array_validation( 'pid', $bars );

// class
$cs = array(
	'manual_class'		=> 'floorplan-list',
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

	/*echo '<a class="item-entry-link" href="'.get_the_permalink( $pid ).'">';

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

	echo '</a>';*/

	// CUSTOM FIELDS

	echo '<div class="items-info"><a href="'.get_the_permalink( $pid ).'">';

	// Title
	$wp_title = get_the_title( $pid );
	if( !empty( $wp_title ) && !empty( $mfunc->setup_field_control_validation( 'title', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		//echo '<div class="item-title"><a href="'.get_the_permalink( $pid ).'">'.$wp_title.'</a></div>';
		echo '<span class="item-title">'.$wp_title.'</span>';	
	}

	// Group
	$atl_cf_plan_group = get_field( 'plan-group', $pid );
	if( !empty( $atl_cf_plan_group ) ) {
		echo '<span class="item-group"> '.$atl_cf_plan_group.'</span>';
	}

	echo '</a></div>';

	// CTA
	/*
	echo '<div class="item-cta"><a class="item-cta-link" href="'.get_the_permalink( $pid ).'">VIEW</a></div>';
	*/

	// Label
	/*$plan_label = get_field( 'plan-label', $pid );
	if( !empty( $plan_label ) ) {
		echo '<div class="item-plan-label">'.$plan_label.'</div>';
	}*/

	// Price
	/*
	$plan_price = get_field( 'plan-price', $pid );
	if( !empty( $plan_price ) ) {
		echo '<div class="item-price">'.$plan_price.'</div>';
	} else {
		// if empty or unchecked, show "call for pricing"
		echo '<div class="item-price">Call for Pricing</div>';
	}
	*/

	// Deal
	/*
	$plan_deal = get_field( 'plan-deal', $pid );
	if( !empty( $plan_deal ) ) {
		echo '<div class="item-deal">'.$plan_deal.'</div>';
	}
	*/

// WRAP | CLOSE
echo '</div>';

