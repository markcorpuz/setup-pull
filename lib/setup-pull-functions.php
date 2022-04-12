<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//global $gcounter;

class SetupPullMain {


    /**
     * Main MULTI PULL function call
     */
    public function setup_pull_multi( $block ) {

        global $bars;

        $out = ''; // declare empty variable

        $class_global = get_field( 'pull-block-class' );
        $style_global = get_field( 'pull-block-style' );

        // ENTRY | Global Settings
        $template_global_entry = get_field( 'pull-layout-entries' );
        $tge_template = $template_global_entry[ 'pull-template-global' ];
        $tge_class = $template_global_entry[ 'pull-section-class-global' ];
        $tge_style = $template_global_entry[ 'pull-section-style-global' ];

        // DETAILS | Global Settings
        $template_global_details = get_field( 'pull-layout-details' );
        $tgd_template = $template_global_details[ 'pull-details-template-global' ];
        $tgd_class = $template_global_details[ 'pull-details-class-global' ];
        $tgd_style = $template_global_details[ 'pull-details-style-global' ];
        

        if( have_rows( 'pull-flexi' ) ):
            while( have_rows( 'pull-flexi' ) ) : the_row();

                $bars = array(); // declare empty variable to clear all information gathered from the loop

                // ENTRIES
                if( get_row_layout() == 'pull-entry' ):

                    // source
                    $esource = get_sub_field( 'pull-show-source-multi' );
                    if( $esource === 'show' ) {
                        $bars[ 'sources' ] = TRUE;
                    } else {
                        $bars[ 'sources' ] = FALSE;
                    }

                    // class
                    $eclass = get_sub_field( 'pull-section-class-multi' );
                    if( !empty( $tge_class ) && !empty( $eclass ) ) {

                        $bars[ 'wrap_sel' ] = $tge_class.' '.$eclass;

                    } else {

                        if( !empty( $tge_class ) && empty( $eclass ) ) {
                            $bars[ 'wrap_sel' ] = $tge_class;
                        } else {
                            $bars[ 'wrap_sel' ] = $eclass;
                        }

                    }

                    // style
                    $estyle = get_sub_field( 'pull-section-style-multi' );
                    if( !empty( $tge_style ) && !empty( $estyle ) ) {

                        $bars[ 'wrap_sty' ] = $tge_style.' '.$estyle;

                    } else {

                        if( !empty( $tge_style ) && empty( $estyle ) ) {
                            $bars[ 'wrap_sty' ] = $tge_style;
                        } else {
                            $bars[ 'wrap_sty' ] = $estyle;
                        }

                    }

                    // determine what template to use | global or override
                    //if( $this->setup_array_validation( 'pull-override-global', $pulls ) ) {
                    $poglobal = get_sub_field( 'pull-override-global' );
                    if( $poglobal === TRUE ) {
                        $etemplate = get_sub_field( 'pull-template-multi' );
                    } else {
                        $etemplate = $tge_template;
                    }

                    // PULL ENTRY | RELATIONSHIP FIELD
                    //if( $this->setup_array_validation( 'pull-from-multi', $pulls ) && is_array( $pulls[ 'pull-from-multi' ] ) ) {
                    $pfm = get_sub_field( 'pull-from-multi' );
                    if( is_array( $pfm ) ) {
                        
                        // loop through the RELATIONSHIP field
                        foreach( $pfm as $pid ) {
                            
                            $bars[ 'pid' ] = $pid;

                            $out .= $this->setup_pull_view_template( $etemplate, 'views' );

                        }
                        
                    }

                    // loop through the TAXONOMY field
                    $ptg = get_sub_field( 'pull-tax-group' );
                    if( empty( $ptg[ 'pull-post-type' ] ) && !empty( $ptg[ 'pull-taxonomy-multi' ] ) ) {

                        // no post type selected
                        $out .= '<div class="item-missing">Please specify the <b>post type</b> to pull from</div>';

                    } elseif( !empty( $ptg[ 'pull-post-type' ] ) && empty( $ptg[ 'pull-taxonomy-multi' ] ) ) {

                        // no taxonomy selected
                        $out .= '<div class="item-missing">Please specify the <b>taxonomy</b> to pull from</div>';

                    } else {

                        // filter selected post type and taxonomy
                        if( !empty( $ptg[ 'pull-post-type' ] ) && !empty( $ptg[ 'pull-taxonomy-multi' ] ) ) {

                            // loop through the tax field
                            foreach( $ptg[ 'pull-taxonomy-multi' ] as $tax ) {

                                /*array_push( $arrays, array(
                                    'taxonomy'      => $tax->taxonomy,
                                    'field'         => 'slug',
                                    'terms'         => $tax->slug,
                                    'operator'      => 'IN',
                                ) );*/

                                /*if( array_key_exists( $tax->taxonomy, $arrays ) ) {

                                    array_push( $arrays[ $tax->taxonomy ], $tax->slug );

                                } else {

                                    $arrays[ $tax->taxonomy ] = array( $tax->slug );

                                }*/

                                // capture the taxonomy
                                if( empty( $taxes_tax ) )
                                    $taxes_tax = $tax->taxonomy;

                                $taxes[] = $tax->slug;
                                //$arrays[ $tax->taxonomy ] = $tax->slug;

                            }

                            $argz = array(
                                'post_type'     => $ptg[ 'pull-post-type' ],
                                'post_status'   => 'publish',
                                'tax_query'     => array(
                                    array(
                                        'taxonomy'      => $taxes_tax,
                                        'field'         => 'slug',
                                        'terms'         => $taxes,
                                    ),
                                ),
                            );

                            $loop = new WP_Query( $argz );
                            
                            // loop
                            if( $loop->have_posts() ):

                                // get all post IDs
                                while( $loop->have_posts() ): $loop->the_post();
                                    
                                    //$pid = get_the_ID();
//                                    echo get_the_ID().' | '.get_the_title();
//                                    echo '<br />';
                                    $bars[ 'pid' ] = get_the_ID();
                                    $bars[ 'taxonomy' ] = $taxes_tax;

                                    $out .= $this->setup_pull_view_template( $etemplate, 'views' );
                                    //$output .= '<div'.$selector.'><a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a>'.$dtn.'</div>';
                                    
                                endwhile;

                                /* Restore original Post Data 
                                 * NB: Because we are using new WP_Query we aren't stomping on the 
                                 * original $wp_query and it does not need to be reset.
                                */
                                wp_reset_postdata();

                            endif;
                        }

                    }

                endif;

                // DETAILS
                if( get_row_layout() == 'pull-details' ):

                    $dtitle = get_sub_field( 'pull-title-multi' );
                    if( !empty( $dtitle ) ) {
                        $bars[ 'title' ] = $dtitle;
                    } else {
                        $bars[ 'title' ] = '';
                    }

                    $dcredit = get_sub_field( 'pull-credit-multi' );
                    if( !empty( $dcredit ) ) {
                        $bars[ 'credit' ] = $dcredit;
                    } else {
                        $bars[ 'credit' ] = '';
                    }

                    $dsummary = get_sub_field( 'pull-summary-multi' );
                    if( !empty( $dsummary ) ) {
                        $bars[ 'summary' ] = $dsummary;
                    } else {
                        $bars[ 'summary' ] = '';
                    }

                    // class
                    if( !empty( $tgd_class ) ) {
                        $bars[ 'wrap_sel' ] = $tgd_class;
                    } else {
                        $bars[ 'wrap_sel' ] = '';
                    }

                    // selector
                    if( !empty( $tgd_style ) ) {
                        $bars[ 'wrap_sty' ] = $tgd_style;
                    } else {
                        $bars[ 'wrap_sty' ] = '';
                    }
                    
                    $out .= $this->setup_pull_view_template( $tgd_template, 'details' );

                endif;

            endwhile;
        endif; // ACF Flexible Content Field - END

        // output container
        if( !empty( $out ) ) {

            // block class
            $bclass = $this->setup_array_validation( 'className', $block );
 
            if( !empty( $bclass ) && !empty( $class_global ) ) {
                $wrap_class = ' class="'.$bclass.' '.$class_global.'"';
            } else {

                if( empty( $bclass ) && !empty( $class_global ) ) {
                    $wrap_class = ' class="'.$class_global.'"';
                } else {
                    $wrap_class = ' class="'.$bclass.'"';
                }

            }

            // wrap style
            if( empty( $style_global ) ) {
                $wrap_style = '';
            } else {
                $wrap_style = ' style="'.$style_global.'"';
            }

            // output
            echo '<div'.$wrap_class.$wrap_style.'>';
                echo $out;
            echo '</div>';
        }
        
    }


	/**
	 * Main SINGLE function call
	 */
	public function setup_pull_single( $block ) {

        global $bars;
		
        $pull_from = get_field( 'pull-from' );
        if( is_array( $pull_from ) && count( $pull_from ) >= 1 ) {
            
            $pid = $pull_from[ 0 ];

            $bars = array(
                'pid'               => $pid, // // get the post ID
                'title'             => get_field( 'pull-title' ),
                'link'              => get_field( 'pull-link' ),
                'credit'            => get_field( 'pull-credit' ),
                'summary'           => get_field( 'pull-summary' ),
                'wrap_sel'          => get_field( 'pull-section-class' ),
                'wrap_sty'          => get_field( 'pull-section-style' ),
            );

            // check if there's a block class and add to array if true
            $bclass = $this->setup_array_validation( 'className', $block );
            if( !empty( $bclass ) ) {
                $bars[ 'block_class' ] = $bclass;
            } else {
                $bars[ 'block_class' ] = 1;
            }
         
            // show source
            if( get_field( 'pull-source' ) === 'show' ) {
                $bars[ 'sources' ] = TRUE;
            }

            $out = $this->setup_pull_view_template( get_field( 'pull-template' ), 'views' );
            
        }

        if( !empty( $out ) ) {

            echo $out;
            
        }
        

	}


    /**
     * Get VIEW template
     */
    public function setup_pull_view_template( $layout, $dir_ext ) {

        $o = new SetupPullVariables();

        $layout_file = $o->setup_plugin_dir_path().'templates/'.$dir_ext.'/'.$layout;

        if( is_file( $layout_file ) ) {

            ob_start();

            include $layout_file;

            $new_output = ob_get_clean();

            if( !empty( $new_output ) )
                $output = $new_output;

        } else {

            $output = FALSE;

        }

        return $output;

    }


    /**
     * Array validation
     */
    public function setup_array_validation( $needles, $haystacks, $args = FALSE ) {

        if( is_array( $haystacks ) && array_key_exists( $needles, $haystacks ) && !empty( $haystacks[ $needles ] ) ) {

            return $haystacks[ $needles ];

        } else {

            return FALSE;

        }

    }


    /**
     * Apply filters to WP-CONTENT
     */
    public function setup_pull_apply_filters_to_content( $pid ) {

        $content = get_the_content( NULL, FALSE, $pid );
        /**
         * Filters the post content.
         *
         * @since 0.71
         *
         * @param string $content Content of the current post.
         */
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );

        return $content;

    }


    /**
     * Combine Classes for the template
     */
    public function setup_pull_combine_classes( $classes ) {

        $block_class = $classes[ 'block_class' ];
        $item_class = $classes[ 'item_class' ];
        $manual_class = $classes[ 'manual_class' ];

        if( !empty( $block_class ) ) {
            // PULL | SINGLE
            if( is_numeric( $block_class ) ) {
                return $manual_class.' '.$item_class;   
            } else {
                return $manual_class.' '.$block_class.' '.$item_class;  
            }
        } else {
            // PULL | MULTI
            return $item_class; 
        }

    }


    /**
     * Combine Classes for the template
     */
    public function setup_pull_combine_styles( $styles ) {

        $manual_style = $styles[ 'manual_style' ];
        $item_style = $styles[ 'item_style' ];

        if( !empty( $manual_style ) && !empty( $item_style ) ) {
                return ' style="'.$manual_style.' '.$item_style.'"';
        } else {

            if( empty( $manual_style ) && !empty( $item_style ) ) {
                return ' style="'.$item_style.'"';
            } else {
                return ' style="'.$manual_style.'"';
            }

        }

    }


    /**
     * WP_Query
     */
    /*public function spull_wpquery( $args ) {

        $pid = array();
        
        // query
        $loop = new WP_Query( $args );
        
        // loop
        if( $loop->have_posts() ):

            // get all post IDs
            while( $loop->have_posts() ): $loop->the_post();
                
                $pid[] = get_the_ID();
                
            endwhile;

        endif;

        return $pid;

    }*/


    /**
     * REMOTE | REST API
     */
    public function setup_pull_remote( $block ) {

        /*
            5a. Possibly a list of categories 
            5b. and tags
        */
        /*
            var_dump( $block[ 'className' ] );
            ?><hr /><?php
            var_dump( get_field( 'pull-url-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-post-type-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-post-id-slug-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-fields-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-featured-image-size' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-template-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-section-class-remote' ) );
            ?><hr /><?php
            var_dump( get_field( 'pull-section-style-remote' ) );
        */

        $id = get_field( 'pull-post-id-slug-remote' ); // this can be slug or ID of the entry

        $url_raw = get_field( 'pull-url-remote' ); // URL

        $rest_api_url_extension = 'wp'; // or ACF

        $version = 'v2';

        $post_type = get_field( 'pull-post-type-remote' ); // post type

        $get_fields = get_field( 'pull-fields-remote' ); // array of fields to be pulled

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

        }

        // combine the URL
        $url_v = rtrim( $url_raw, "/" ).'/wp-json/'.$rest_api_url_extension.'/'.$version.'/';
        $url_combined = $url_v.$post_type.'/';
        //echo '<h1>'.$url_combined.'</h1>';

        if( $this->setup_check_for_404( $url_combined ) ) {

            $o = new SetupPullVariables();

            // image size
            $img_size = get_field( 'pull-featured-image-size' );

            global $bars;

            if( is_numeric( $id ) ) {
                // pull using post ID and decode
                $array = json_decode( file_get_contents( $url_combined.$id.'?'.$pull_fields ), TRUE, 512 );
            } else {
                // pull using slug and decode
                $array = json_decode( file_get_contents( $url_combined.'?slug='.$id.'&'.$pull_fields ), TRUE, 512 );
            }
            
            /**
             * TERNARY OPERATOR
             *
             * (Condition)?(thing's to do if condition true):(thing's to do if condition false);
             *
             * SAMPLE 1: echo ($requestVars->_name == '') ? $redText : '';
             *
             * SAMPLE 2: ($var > 2 ? echo "greater" : echo "smaller")
             * 
             * SAMPLE 3: $var > 2 ? echo "greater" : echo "smaller"
             */
            
            if( count( $array ) == 1 && is_array( $array[ 0 ] ) ) {
                /*var_dump( $array );
                $title_zero         =   $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'title', $array[ 0 ] ) ) ? $array[ 0 ][ 'title' ][ 'rendered' ] : '';
                $content_zero       =   $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'content', $array[ 0 ] ) ) ? $array[ 0 ][ 'content' ][ 'rendered' ] : '';
                $content_excerpt    =   $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'excerpt', $array[ 0 ] ) ) ? $array[ 0 ][ 'excerpt' ][ 'rendered' ] : '';
                $content_feat_img   =   $this->setup_pull_featured_image( ( $this->setup_array_validation( 'featured_media', $array[ 0 ] ) ? $array[ 0 ][ 'featured_media' ] : '' ), $url_v, $img_size );
                $content_modified   =   $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'modified', $array[ 0 ] ) ) ? $array[ 0 ][ 'modified' ][ 'rendered' ] : '';
                */
                $bars = array(
                    'title'             => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'title', $array[ 0 ] ) ) ? $array[ 0 ][ 'title' ][ 'rendered' ] : '',
                    'content'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'content', $array[ 0 ] ) ) ? $array[ 0 ][ 'content' ][ 'rendered' ] : '',
                    'excerpt'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'excerpt', $array[ 0 ] ) ) ? $array[ 0 ][ 'excerpt' ][ 'rendered' ] : '',
                    'featured-image'    => $this->setup_pull_featured_image( ( $this->setup_array_validation( 'featured_media', $array[ 0 ] ) ? $array[ 0 ][ 'featured_media' ] : '' ), $url_v, $img_size ),
                    'date-modified'     => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'modified', $array[ 0 ] ) ) ? $array[ 0 ][ 'modified' ][ 'rendered' ] : '',
                );
            } else {
                $bars = array(
                    'title'             => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'title', $array ) ) ? $array[ 'title' ][ 'rendered' ] : '',
                    'content'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'content', $array ) ) ? $array[ 'content' ][ 'rendered' ] : '',
                    'excerpt'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'excerpt', $array ) ) ? $array[ 'excerpt' ][ 'rendered' ] : '',
                    'featured-image'    => $this->setup_pull_featured_image( ( $this->setup_array_validation( 'featured_media', $array ) ? $array[ 'featured_media' ] : '' ), $url_v, $img_size ),
                    'date-modified'     => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'modified', $array ) ) ? $array[ 'modified' ][ 'rendered' ] : '',
                );
            }
            
            $bars[ 'block_class' ]  = !empty( $block[ 'className' ] ) ? $block[ 'className' ] : '';
            $bars[ 'wrap_sel' ]     = get_field( 'pull-section-class-remote' );
            $bars[ 'wrap_sty' ]     = get_field( 'pull-section-style-remote' );
            

            include( $o->setup_plugin_dir_path().'templates/views/'.get_field( 'pull-template-remote' ) );
            //echo $this->setup_pull_view_template( get_field( 'pull-template-remote' ), 'views' );

        } else {

            //return 'Error 404 - URL does not exist.';
            echo 'Please check your URL and variables. URL does not seem to exist.';

        }

    }


    /**
     * Pull featured image
     */
    private function setup_pull_featured_image( $media_id, $url_target, $img_size = FALSE ) {
        // featured media
        /*
            https://setup-video.basestructure.com/wp-json/wp/v2/media/742

            // get normal size
            https://setup-video.basestructure.com/wp-json/wp/v2/media/742?_fields[]=media_details

            // with sizes
            https://setup-video.basestructure.com/wp-json/wp/v2/media/742?_fields[]=link&_fields[]=media_details
        */
        $murl = $url_target.'media/'.$media_id.'?_fields[]=media_details';
        //echo (string) file_get_contents( $murl );
        $marray = json_decode( file_get_contents( $murl ), TRUE, 512 );

        // assign default size
        if( empty( $img_size ) ) {
            $img_size = 'full';
        }

//        $stringz = $marray[ 'media_details' ][ 'sizes' ][ $img_size ][ 'source_url' ];
        /*echo '<h1>'.urldecode( $stringz ).'</h1>';
        ?><hr /><h1><?php
        echo '&#215';
        ?></h1><?php
        */
        

        //echo utf8_encode( '%C3%97' );
        //echo utf8_decode( '×' );
//        echo mb_convert_encoding( '×', 'UTF-32', 'UTF-8' );
        //echo mb_convert_encoding( '&#215', 'UTF-8', "auto");

        /*foreach( $marray[ 'media_details' ][ 'sizes' ][ $img_size ] as $key => $value) {
            echo '<b>'.$key.'</b>';
            var_dump( $value );
            echo '<hr />';
        }*/

        return $media_id;

    }


    /**
     * Validate URL if accessible
     */
    private function setup_check_for_404( $url ) {

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