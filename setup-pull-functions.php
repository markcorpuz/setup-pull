<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// go though the URL to isolate what's being pulled
if( !function_exists( 'setup_pull_through_the_url' ) ) {

	function setup_pull_through_the_url( $array, $field, $block ) {

		if( is_array( $field ) ) {

			// more than 1 field is being pulled

		} else {

			// only 1 field is being pulled
			if( !empty( $block ) ) {

				//return setup_pull_parse_blocks( $array, $block );

				// PULL BLOCK
				echo '<h2>BLOCK</h2>';

			} else {

				// PULL FIELD
				//echo '<h2>NO BLOCK</h2>';
				return setup_pull_loop_though_each_field( $array, $field );

			}

		} // if( is_array( $field ) ) {

	}

}


// separate the blocks within the WP-CONTENT
if( !function_exists( 'setup_pull_parse_blocks' ) ) {

    function setup_pull_parse_blocks( $content, $get_this_block ) {

        foreach( parse_blocks( $content ) as $val ) {

            // filter variable
            if( array_key_exists( 'attrs', $val ) && is_array( $val[ 'attrs' ] ) ) {

                // filter and match to target block (css selector)
                if( array_key_exists( 'className', $val[ 'attrs' ] ) && $val[ 'attrs' ][ 'className' ] == $get_this_block ) {

                    //echo $val[ 'blockName' ].'<br /><br />';
                    $out = $val[ 'innerHTML' ]; // or $val[ 'innerContent' ]
                    //echo $val[ 'attrs' ][ 'className' ];

                    break; // exit loop

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


// Loop through the pulled information
if( !function_exists( 'setup_pull_loop_though_each_field' ) ) {

    function setup_pull_loop_though_each_field( $array, $field ) {

        foreach( $array as $key => $val ) {

            //echo '<h1>'.$key.'</h1>'; // show all fields pulled
            if( $field == $key ) {

                // apply filters if content is being pulled
                if( $field == 'content' ) {

                    return setup_pull_apply_filters_to_content( $val[ 'rendered' ] );
                    
                } else {

                    $return = $val[ 'rendered' ];

                }
                
                break; // exit loop
            }

        }

        return $return;

    }

}


// Apply filters to wp-content
if( !function_exists( 'setup_pull_apply_filters_to_content' ) ) {

    function setup_pull_apply_filters_to_content( $content ) {

        $content = apply_filters( 'the_content', $content );
        return str_replace( ']]>', ']]&gt;', $content );

    }

}