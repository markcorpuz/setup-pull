<?php

if( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/*
	1. pull local and block specific - not yet done
*/


// PULL THROUGH REST API USING POST/PAGE ID
function setup_pull_through( $array, $block = NULL, $id = NULL ) {

	//$fieldz = explode( ',', $field );
        
	// remove spaces in each array value
	//$fields = array_map( 'trim', $fieldz );

	$return = ''; // initialize variable
	$ret = array();
	
	foreach( $array as $key => $val ) {
		//echo '<h1>'.$key.'</h1>'; var_dump( $val );

	    // apply filters if content is being pulled
	    if( $key == 'content' ) {

	        if( empty( $block ) ) {
	        	
	        	/* **********************
	        	 * return wp-content
	        	 * if pulled by slug, the rendered content is
	        	 * still hidden in another layer of array
	        	 * ******************* */
	        	if( is_numeric( $id ) ) {
	        		$pulled_content = $val[ 'rendered' ];
	        	} else {
	        		$pulled_content = $val[ 'content' ][ 'rendered' ];
	        	}

	        	$entry_content = setup_pull_apply_filters_to_content( $pulled_content );
	        	$entry_content_pre = '<pre id="copyme">'.setup_pull_display_code_pre( $pulled_content ).'</pre>';

	        } else {

	            // parse blocks
	            $entry_content = setup_pull_parse_blocks( $val[ 'rendered' ], $block );

	        }
	        
	    }

		// get the modified date of the entry
		if( $key == 'modified' ) {
			
			if( is_numeric( $id ) ) {
				// pulled using post ID
		    	$entry_mod_date = $val;
		    } else {
		    	// pulled using post slug
		    	$entry_mod_date = $val[ "modified" ];
		    }

		}

		// get the link to the source
		if( $key == 'link' ) {
			
			if( is_numeric( $id ) ) {
				// pulled using post ID
		    	$entry_link = $val;
		    } else {
		    	// pulled using post slug
		    	$entry_link = $val[ "link" ];
		    }

		}

	}

	// no entry found
	if( empty( $entry_content ) ) {
	    $return = 'No entry found. Please validate the source.';
	} else {

	    $return = array(
	        'output'        => $entry_content,
	        'output_pre'	=> $entry_content_pre,
	        'mod_date'      => $entry_mod_date,
	        'entry_link'    => $entry_link,
	    );

	}

	return $return;

}


function setup_pull_display_code_pre( $string ) {
	$string = str_replace( "<", "&lt;", $string );
	$string = str_replace( ">", "&gt;", $string );
	return $string;
}


// separate the blocks within the WP-CONTENT
if( !function_exists( 'setup_pull_parse_blocks' ) ) {

    function setup_pull_parse_blocks( $content, $get_this_block ) {

        $out = ''; // initialize variable to avoid the issue of undeclared variable
        
        foreach( parse_blocks( $content ) as $val ) {
            
            /*foreach( $val as $keyz => $valuez ) {

                //echo '<h1>'.$keyz.'</h1>';
                if( $keyz == 'innerHTML' ) {
                    var_dump( $valuez ); echo '<hr /><hr />';

                    //$html = 'html contents or file <img src="smiley.gif" alt="Smiley face" height="42" width="42">';
                    / *$html = $valuez;
                    $doc = new DOMDocument();
                    $doc->loadHTML($html);

                    $tags = $doc->getElementsByTagName( 'div' );
                    echo count( $tags ).'<br />';
                    foreach ($tags as $tag) {
                     echo $tag->getAttribute('src');
                    }
                    var_dump($tags);
                    * /
                }

            }*/
            // filter variable
            if( array_key_exists( 'attrs', $val ) && is_array( $val[ 'attrs' ] ) ) {

                // filter and match to target block (css selector)
                if( array_key_exists( 'className', $val[ 'attrs' ] ) && $val[ 'attrs' ][ 'className' ] == $get_this_block ) {

                    //echo $val[ 'blockName' ].'<br /><br />';
                    $out = $val[ 'innerHTML' ]; // or $val[ 'innerContent' ]
                    //echo $val[ 'attrs' ][ 'className' ];

                    //break; // exit loop

                }

            }

        }

        return $out;

    }

}


// Pull the whole WP-CONTENT
if( !function_exists( 'setup_pull_the_whole_content' ) ) {

    function setup_pull_the_whole_content( $pid ) {

        $content = get_the_content( NULL, FALSE, $pid );
        /**
         * Filters the post content.
         *
         * @since 0.71
         *
         * @param string $content Content of the current post.
         */
        //      $content = apply_filters( 'the_content', $content );
        //      return str_replace( ']]>', ']]&gt;', $content );
        return setup_pull_apply_filters_to_content( $content );

    }

}


// Apply filters to wp-content
if( !function_exists( 'setup_pull_apply_filters_to_content' ) ) {

    function setup_pull_apply_filters_to_content( $content ) {

        $content = apply_filters( 'the_content', $content );
        return str_replace( ']]>', ']]&gt;', $content );

    }

}