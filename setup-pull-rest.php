<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function setup_pull_rest_api( $atts ) {
    // $atts['foo'] -> get attribute contents

    // do not run in WP-Admin
    //if( is_admin() ) return;
    
    // variables | URL
    if( array_key_exists( "url", $atts ) ) {
        $url = $atts[ 'url' ];
    }
    
    // variables | Native or Custom field (yes or no)
    if( array_key_exists( "id", $atts ) ) {
        $id = $atts[ 'id' ];
    }
    
    // variables | Field
    /*if( array_key_exists( "field", $atts ) ) {
        $field = $atts[ 'field' ];
    }
    
    // variables | Template
    if( array_key_exists( "template", $atts ) ) {
        $template = $atts[ 'template' ];
    }
    
    // variables | Size
    if( array_key_exists( "size", $atts ) ) {
        $img_size = $atts[ 'size' ];
    } else {
        // assign default size
        $img_size = 'thumbnail';
    }
    
    // variables | Class (CSS)
    if( array_key_exists( "class", $atts ) ) {
        $styling = "class='".$atts[ 'class' ]."'";
    } else {
        // assign default size
        $styling = '';
    }*/

    // variables | 
    if( array_key_exists( "field", $atts ) ) {
        $field = $atts[ 'field' ];
    }
    
    // variables | Block
    if( array_key_exists( "block", $atts ) ) {
        $block = $atts[ 'block' ];
    }

    /*
    http://test.jakealmeda.com/wp-json/wp/v2/posts/1
    http://test.jakealmeda.com/wp-json/acf/v3/posts
    */

    // variables | API Extension
    if( array_key_exists( "api_url_ext", $atts ) ) {
    	$rest_api_url_extension = $atts[ 'api_url_ext' ]; // 'wp'  or 'acf'
    }

    // variables | Post Type
    if( array_key_exists( "post_type", $atts ) ) {
    	$post_type = $atts[ 'post_type' ];
    }

    // variables | Post Type
    if( array_key_exists( "version", $atts ) ) {
    	$version = $atts[ 'version' ];
    }

    //echo rtrim( $url, "/" ).'/wp-json/'.$rest_api_url_extension.'/'.$version.'/'.$post_type.'/'.$id;

    $url_combined = rtrim( $url, "/" ).'/wp-json/'.$rest_api_url_extension.'/'.$version.'/'.$post_type.'/';

    //$target = 'http://plan.smarterwebpackages.com/wp-json/wp/v2/partners';
    //$target = 'http://plan.smarterwebpackages.com/wp-json/wp/v2/partners/170';
    //if( $id ) {
    //$targ_rest = $url_combined.$post_type.'/'.$id;

    if( setup_check_for_404( $url_combined ) ) {

    	if( empty( $id ) ) {

            return "Please specify the post ID you want to retrieve from.";

        } else {

        	// check if $field is variable (post ID) or text (post name/slug or title)
        	if( is_numeric( $id ) ) {

        		// pull and decode
    			$array = json_decode( file_get_contents( $url_combined.$id ), TRUE, 512 );

        		// post ID
        		return setup_pull_through_the_url( $array, $field, $block, NULL );

        	} else {

        		//echo '<h1>'.$id.' | '.$field.'</h1>';
        		$array = json_decode( file_get_contents( $url_combined ), TRUE, 512 );

        		// post name (slug)
        		return setup_pull_through_the_url( $array, $field, $block, $id );

        	}



        }
    	
    } else {

    	return 'Error 404 - URL does not exist.';

    }

}

if( !function_exists( 'setup_check_for_404' ) ) {

    function setup_check_for_404( $url ) {
          
        // Getting page header data 
        $array = @get_headers($url); 
          
        // Storing value at 1st position because 
        // that is only what we need to check 
        $string = $array[0]; 
          
        // 404 for error, 200 for no error 
        if(strpos($string, "200")) { 
            //echo 'Specified URL Exists'; 
            return TRUE;
        }  
        else { 
            //echo 'Specified URL does not exist'; 
            return FALSE;
        }

    }

}
