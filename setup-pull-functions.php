<?php

if( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// go though the URL to isolate what's being pulled
if( !function_exists( 'setup_pull_through_the_url' ) ) {

    function setup_pull_through_the_url( $array, $field, $block = NULL, $slug_or_title = NULL ) {

        if( empty( $slug_or_title ) ) {

            /*if( is_array( $field ) ) {

                // more than 1 field is being pulled

            } else {*/

                // only 1 field is being pulled
                if( !empty( $block ) ) {

                    // PULL BLOCK
                    //return setup_pull_parse_blocks( $array, $block );
                    return setup_pull_loop_though_each_field( $array, $field, NULL, $block );

                } else {

                    // PULL FIELD
                    return setup_pull_loop_though_each_field( $array, $field );

                }

            //} // if( is_array( $field ) ) {

        } else {

            // page name (slug) or title is being used to pull information
            return setup_pull_loop_though_each_field( $array, $field, $slug_or_title );

        }

    }

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


// Loop through the pulled information
if( !function_exists( 'setup_pull_loop_though_each_field' ) ) {

    function setup_pull_loop_though_each_field( $array, $field, $slug_or_title = NULL, $block = NULL ) {

        $fieldz = explode( ',', $field );
        /*foreach( $fields as $f ) {
            $fields[] = trim( $f ); // remove spaces before and after each value
        }*/
        $fields = array_map( 'trim', $fieldz );
        
        $return = ''; // initialize variable
        $ret = array();

        foreach( $array as $key => $val ) {
            
            if( empty( $slug_or_title ) ) {
                // ID is used to pull the entry

                //echo '<h1>'.$key.'</h1>'; // show all fields pulled
                if( in_array( $key, $fields ) ) {

                    // apply filters if content is being pulled
                    if( $key == 'content' ) {

                        if( empty( $block ) ) {
                        
                            // return wp-content
                            $ret[ $key ] = setup_pull_apply_filters_to_content( $val[ 'rendered' ] );

                        } else {

                            // parse blocks
                            $ret[ $key ] = setup_pull_parse_blocks( $val[ 'rendered' ], $block );

                        }
                        
                    } else {

                        if( is_array( $val ) && array_key_exists( 'rendered', $val ) ) {

                            $ret[ $key ] = $val[ 'rendered' ];

                        } else {

                            $ret[ $key ] = $val;
                            
                        }
                        
                    }
                    
                }

            } else {

                // page name (slug) or title is being used to pull information           
                if( $val[ 'slug' ] == $slug_or_title || $val[ 'title'][ 'rendered' ] == $slug_or_title ) {

                    foreach( $val as $v_key => $v_value ) {

                        //echo $v_key.'<br />';
                        if( in_array( $v_key, $fields ) ) {

                            // apply filters if content is being pulled
                            if( $v_key == 'content' ) {
                                
                                $ret[ $v_key ] = setup_pull_apply_filters_to_content( $v_value[ 'rendered' ] );
                                
                            } else {
                                
                                if( is_array( $v_value ) && array_key_exists( 'rendered', $v_value ) ) {

                                    $ret[ $v_key ] = $v_value[ 'rendered' ];

                                } else {

                                    $ret[ $v_key ] = $v_value;

                                }
                                
                            }

                        } // if( in_array( $v_key, $fields ) ) {

                    } // foreach( $val as $v_key => $v_value ) {

                }

            } // if( empty( $slug_or_title ) ) {

        }

        // arrange the layout based on how the fields are listed
        foreach( $fields as $ff ) {
            $return .= $ret[ $ff ];
        }

        // no entry found
        if( empty( $return ) ) {
            $return = 'No entry found. Please validate the source.';
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