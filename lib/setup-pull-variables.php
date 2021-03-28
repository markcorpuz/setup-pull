<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function setup_pull_rest_api( $atts ) {
    
    // variables | URL
    if( array_key_exists( "url", $atts ) ) {
        $url = $atts[ 'url' ];
    } else {
    	$url = '';
    }
    
    // variables | Native or Custom field (yes or no)
    if( array_key_exists( "id", $atts ) ) {
        $id = $atts[ 'id' ];
    } else {
    	$id = '';
    }
    
    // variables | Flter
    if( array_key_exists( "pull_filter", $atts ) ) {
        $pull_filter = $atts[ 'pull_filter' ];
    } else {
        $pull_filter = 'rest';
    }
    
    // variables | Template
    /*if( array_key_exists( "template", $atts ) ) {
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

    // variables | Fields
    if( array_key_exists( "field", $atts ) ) {
        $field = $atts[ 'field' ];
    } else {
    	$field = 'content';
    }
    
    // variables | Block
    if( array_key_exists( "block", $atts ) ) {
        $block = $atts[ 'block' ];
    } else {
    	$block = '';
    }

    // variables | API Extension
    if( array_key_exists( "api_url_ext", $atts ) ) {
    	$rest_api_url_extension = $atts[ 'api_url_ext' ]; // 'wp'  or 'acf'
    } else {
    	$rest_api_url_extension = 'wp';
    }

    // variables | Post Type
    if( array_key_exists( "post_type", $atts ) ) {
    	$post_type = $atts[ 'post_type' ];
    } else {
    	$post_type = 'posts';
    }

    // variables | Post Type
    if( array_key_exists( "version", $atts ) ) {
    	$version = $atts[ 'version' ];
    } else {
    	$version = 'v2';
    }
  
    /***************
     
     * PULL USING SLUG
     * https://setup-be.basestructure.com/wp-json/wp/v2/pages?slug=about

     * *************
    
     * PULL USING SLUG BUT FILTER FIELDS TO BE PULLED
     * https://setup-be.basestructure.com/wp-json/wp/v2/pages?slug=about&_fields[]=content&_fields[]=modified&_fields[]=link

     * *************

     * PULL ALL ENTRIES BASED ON THE POST TYPE
     * https://setup-be.basestructure.com/wp-json/wp/v2/posts
     * https://setup-be.basestructure.com/wp-json/wp/v2/posts?per_page=100
    
     * *************

     * PULL ENTRY USING POST ID
     * https://setup-be.basestructure.com/wp-json/wp/v2/pages/11

     * *************

     * PULL ENTRY USING POST ID BUT FILTER FIELDS TO BE PULLED
     * https://setup-be.basestructure.com/wp-json/wp/v2/pages/11?_fields[]=content&_fields[]=modified&_fields[]=link

     ************ */
    
    // combine the URL
    $url_combined = rtrim( $url, "/" ).'/wp-json/'.$rest_api_url_extension.'/'.$version.'/'.$post_type.'/';
    //echo '<h1>'.$url_combined.'</h1>';

    if( setup_check_for_404( $url_combined ) ) {

    	if( empty( $id ) ) {

            return "Please specify the post ID/slug you want to retrieve from.";

        } else {

        	// check if $field is variable (post ID) or text (post name/slug or title)
        	/*if( is_numeric( $id ) ) {

        		// pull and decode
    			$array = json_decode( file_get_contents( $url_combined.$id ), TRUE, 512 );
    			
        		// post ID
                if( $pull_filter == 'rest' ) {
                    return setup_pull_through( $array, $field );
                } else {
                    // pull from local
                    return setup_pull_through( $array, $field, $block );
                }

        	} else {

        		//echo '<h1>'.$id.' | '.$field.'</h1>';
        		$array = json_decode( file_get_contents( $url_combined ), TRUE, 512 );

        		// post name (slug)
                if( $pull_filter == 'rest' ) {
                    return setup_pull_through( $array, $field, NULL, $id );
                } else {
                    return setup_pull_through( $array, $field, $block, $id );
                }

        	}*/

            // default fields to be pulled
            $default_fields = array( 'modified', 'link' );

            // parse $field variable
            $exp_field = explode( ',', $field );

            // merge arrays
            $get_fields = array_merge( array_map( 'trim', $exp_field ), $default_fields );

            $pull_fields = ''; // initialize variable

            $max_count = count( $get_fields )-1;

            // loop through the array
            for( $x=0; $x<=$max_count; $x++ ) {
                
                // add & after each field but not at the end
                if( $x == $max_count ) {
                    $ampersand = '';
                } else {
                    $ampersand = '&';
                }

                $pull_fields .= '_fields[]='.trim( $get_fields[ $x ] ).$ampersand;
                
            }// echo '<h1>'.$url_combined.$id.'?'.$pull_fields.'</h1>';

            if( is_numeric( $id ) ) {
                
                // pull using post ID and decode
                $array = json_decode( file_get_contents( $url_combined.$id.'?'.$pull_fields ), TRUE, 512 );
                
            } else {
                
                // pull using slug and decode
                $array = json_decode( file_get_contents( $url_combined.'?slug='.$id.'&'.$pull_fields ), TRUE, 512 );
                
            }

            if( $pull_filter == 'rest' ) {
                return setup_pull_through( $array, NULL, $id );
            } else {
                // pull from local
                return setup_pull_through( $array, $block );
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

        if( is_array( $array ) ) {
            // Storing value at 1st position because 
            // that is only what we need to check 
            $string = $array[0]; 
              
            // 404 for error, 200 for no error 
            if(strpos($string, "200")) { 
                //echo 'Specified URL Exists'; 
                return TRUE;
            } else { 
                //echo 'Specified URL does not exist'; 
                return FALSE;
            }

        } else {
            return FALSE;
        }

    }

}
