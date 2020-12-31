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

    	// remove these fields from being pulled
    	$remove_the_log_fields = array( 'log_layout', 'log_filter', 'log_show' );

    	$out = ''; // declare variable

    	// loop through the content
        foreach( parse_blocks( $content ) as $val ) {

            // filter variable
            if( array_key_exists( 'attrs', $val ) && is_array( $val[ 'attrs' ] ) ) {

                // filter and match to target block (css selector)
                if( array_key_exists( 'className', $val[ 'attrs' ] ) && $val[ 'attrs' ][ 'className' ] == $get_this_block ) {

                    //echo $val[ 'blockName' ].'<br /><br />';
                    $out .= $val[ 'innerHTML' ]; // or $val[ 'innerContent' ]
                    //echo $val[ 'attrs' ][ 'className' ];

//                    break; // exit loop

                }

            }
        	
        	// filter custom block
        	if( array_key_exists( 'blockName', $val ) && $val[ 'blockName' ] == 'acf/log' ) {

        		//var_dump($val);echo '<hr />';
        		foreach( $val as $keys => $values ) {
        			
        			//echo '<h1>'.$values[ 'className' ].' | '.$keys.'</h1>';
        			//var_dump($values);
        			//echo '<hr />';
					
        			if( $keys == 'attrs' ) {

        				// filter source block
        				if( array_key_exists( 'className', $values ) && $values[ 'className' ] == $get_this_block ) {
	        				//echo '<h1>'.$values[ 'className' ].'</h1>';// var_dump( $values[ 'data' ] ); echo '<br />';
	        				
	        				if( array_key_exists( 'data', $values ) ) {

		        				foreach( $values[ 'data' ] as $a_key => $a_value ) {
		        					
		        					// catch actual fields (values that don't start with underscore)
		        					$ekey = explode( "_", $a_key );
		        					if( is_array( $ekey ) && !empty( $ekey[ 0 ] ) ) {

		        						// filter fields to show
		        						if( !in_array( $a_key, $remove_the_log_fields ) && !empty( $a_value ) ) {

		        							//echo $a_key.' ====== '; var_dump( $a_value ); echo '<br />';
		        							$pass_var[ $a_key ] = $a_value;

		        						}
		        						
		        					}

		        				} // foreach( $values[ 'data' ] as $a_key => $a_value ) {

		        				$out .= setup_log_layout_validation( $pass_var );

	        				} // if( array_key_exists( 'data', $values ) ) {

	        			}

        			}

        			if( $keys == 'innerBlocks' ) {

        				if( is_array( $values ) ) {

        					foreach( $values as $ib_key => $ib_value ) {
        					
        						$same_block = ''; // set variable to check if we're still on the same block

								if( is_array( $ib_value ) ) {

									foreach( $ib_value as $key_ibs => $value_ibs ) {

										if( is_array( $value_ibs) && array_key_exists( 'className', $value_ibs ) && $value_ibs[ 'className' ] == $get_this_block ) {
											//echo $key_ibs.' ======================= '; var_dump( $value_ibs ); echo '<br />';
											//echo '<hr />';
											$same_block = 1;
										}


										if( $same_block && $key_ibs == 'innerHTML' ) {
											//echo $key_ibs.' ======================= '; var_dump( $value_ibs ); echo '<br />';
											$out .= $value_ibs;
										}

									}

								}//echo '<hr />';

        					}

        				}

        			}

        		} // foreach( $val as $keys => $values ) {

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