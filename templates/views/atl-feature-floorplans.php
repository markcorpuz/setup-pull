<?php

global $bars;

$mfunc = new SetupPullMain();
$pid = $mfunc->setup_array_validation( 'pid', $bars );

// class
$cs = array(
	'manual_class'		=> 'floorplan-summary',
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

	// wp-title
	$wp_title = get_the_title( $pid );
	if( !empty( $wp_title ) && !empty( $mfunc->setup_field_control_validation( 'title', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		//echo '<div class="item-title"><a href="'.get_the_permalink( $pid ).'">'.$wp_title.'</a></div>';
		echo '<div class="item-title"><a class="item-title-link" href="'.get_the_permalink( $pid ).'">'.$wp_title.'</a></div>';	
	}

	// CUSTOM FIELDS

	// Group
	$atl_cf_plan_group = get_field( 'plan-group', $pid );
	if( !empty( $atl_cf_plan_group ) ) {
		echo '<div class="item-plan-group">'.$atl_cf_plan_group.'</div>';
	}

	// Label
	$plan_label = get_field( 'plan-label', $pid );
	if( !empty( $plan_label ) ) {
		//echo '<div class="item-plan-label">'.$plan_label.'</div>';
	}

	// Price
	$plan_price = get_field( 'plan-price', $pid );
	if( !empty( $plan_price ) ) {
		echo '<div class="item-price">'.$plan_price.'</div>';
	} else {
		// if empty or unchecked, show "call for pricing"
		echo '<div class="item-price">Call for Pricing</div>';
	}

	// Deal
	$plan_deal = get_field( 'plan-deal', $pid );
	if( !empty( $plan_deal ) ) {
		echo '<div class="item-deal">'.$plan_deal.'</div>';
	}

	// Pic
	$plan_pic = get_field( "plan-pic", $pid );
	if( !empty( $plan_pic ) ) {
		//echo '<div class="item-pic">'.$plan_pic.'</div>';
		$ppic = wp_get_attachment_image_src( $plan_pic, $mfunc->setup_array_validation( "plan_pic_size", $bars ) ? $bars[ "plan_pic_size" ] : 'full' );

		echo '<div class="item-pic">';
			echo '<a class="item-pic-link" href="'.get_the_permalink( $pid ).'"><img src="'.$ppic[ 0 ].'" border="0" /></a>';
		echo '</div>';
	}

	// Summary
	$plan_summary = get_field( "plan-summary", $pid );
	if( !empty( $plan_summary ) ) {
		echo '<div class="item-summary">'.$plan_summary.'</div>';
	}

	// Features
	$plan_features = get_field( 'plan-features', $pid );
	if( !empty( $plan_features ) ) {
		/**
		 * NOTE: last argument of atl_get_tax_terms() is will it be a link or no.
		 * TRUE if you want term permalink, FALSE or remove the argument if not a link
		 */
		echo '<div class="item-features"><ul>'.$mfunc->atl_get_tax_terms( $plan_features, 'feature_list', FALSE ).'</ul></div>';
	}

	// BG
	$plan_bg = get_field( "plan-bg", $pid );
	if( !empty( $plan_bg ) ) {
		//echo '<div class="item-bg">'.$plan_bg.'</div>';
		$pbg = wp_get_attachment_image_src( $plan_bg, get_field( "plan-bg-size", $pid ) );

		echo '<div class="item-background">';
			echo '<img src="'.$pbg[ 0 ].'" border="0" />';
		echo '</div>';
	}

// WRAP | CLOSE
echo '</div>';

