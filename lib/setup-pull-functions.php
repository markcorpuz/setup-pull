<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//global $gcounter;

class SetupPullMain {


    /**
     * Main MULTI PULL FLEXIBLE function call
     */
    public function setup_pull_multi_flex( $block ) {

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
        
        // loop through 
        if( have_rows( 'pull-flexi' ) ):
            while( have_rows( 'pull-flexi' ) ) : the_row();

                //$bars = array(); // declare empty variable to clear all information gathered from the loop

                // ENTRIES
                if( get_row_layout() == 'pull-entry' ):
                    
                    // check if included or not
                    if( get_sub_field( 'pull-exclude-flex' ) === FALSE ) {

                        /*$sptp = new SetupPullTaxonomyPull();

                        $args = array(
                            'esource'           => get_sub_field( 'pull-show-source-flex' ),
                            'eclass'            => get_sub_field( 'pull-section-class-flex' ),
                            'tge_class'         => $tge_class,
                            'estyle'            => get_sub_field( 'pull-section-style-flex' ),
                            'tge_style'         => $tge_style,
                            'poglobal'          => get_sub_field( 'pull-override-global-flex' ),
                            'etemplate'         => get_sub_field( 'pull-template-flex' ),
                            'tge_template'      => $tge_template,
                            'pull_from_flex'    => get_sub_field( 'pull-from-flex' ),
                            'ptg'               => get_sub_field( 'pull-tax-group-flex' ),
                        );

                        $out .= $sptp->sp_pull_taxes( $args );*/

                        // source
                        $esource = get_sub_field( 'pull-show-source-flex' );
                        if( $esource === 'show' ) {
                            $bars[ 'sources' ] = TRUE;
                        } else {
                            $bars[ 'sources' ] = FALSE;
                        }

                        // class
                        $eclass = get_sub_field( 'pull-section-class-flex' );
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
                        $estyle = get_sub_field( 'pull-section-style-flex' );
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
                        $poglobal = get_sub_field( 'pull-override-global-flex' );
                        if( $poglobal === TRUE ) {
                            $etemplate = get_sub_field( 'pull-template-flex' );
                        } else {
                            $etemplate = $tge_template;
                        }
                        
                        // PULL ENTRY | RELATIONSHIP FIELD
                        $pfm = get_sub_field( 'pull-from-flex' );
                        if( is_array( $pfm ) && !empty( $pfm ) ) {
                            
                            // loop through the RELATIONSHIP field
                            foreach( $pfm as $pid ) {

                                $bars[ 'pid' ] = $pid;

                                $out .= $this->setup_pull_view_template( $etemplate, 'views' );

                            }
                            
                        }

                        // loop through the TAXONOMY field
                        $ptg = get_sub_field( 'pull-tax-group-flex' );
                        if( empty( $ptg[ 'pull-post-type-flex' ] ) && !empty( $ptg[ 'pull-taxonomy-flex' ] ) ) {

                            // no post type selected
                            $out .= '<div class="item-missing">Please specify the <b>post type</b> to pull from</div>';

                        } elseif( !empty( $ptg[ 'pull-post-type-flex' ] ) && empty( $ptg[ 'pull-taxonomy-flex' ] ) ) {

                            // no taxonomy selected
                            $out .= '<div class="item-missing">Please specify the <b>taxonomy</b> to pull from</div>';

                        } else {

                            // filter selected post type and taxonomy
                            if( !empty( $ptg[ 'pull-post-type-flex' ] ) && !empty( $ptg[ 'pull-taxonomy-flex' ] ) ) {

                                // loop through the tax field
                                foreach( $ptg[ 'pull-taxonomy-flex' ] as $tax ) {

                                    // capture the taxonomy
                                    if( empty( $taxes_tax ) )
                                        $taxes_tax = $tax->taxonomy;

                                    $taxes[] = $tax->slug;

                                }

                                $argz = array(
                                    'post_type'     => $ptg[ 'pull-post-type-flex' ],
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
                                        
                                        $bars[ 'pid' ] = get_the_ID();
                                        $bars[ 'taxonomy' ] = $taxes_tax;

                                        $out .= $this->setup_pull_view_template( $etemplate, 'views' );
                                        
                                    endwhile;

                                    /* Restore original Post Data 
                                     * NB: Because we are using new WP_Query we aren't stomping on the 
                                     * original $wp_query and it does not need to be reset.
                                    */
                                    wp_reset_postdata();

                                endif;
                            }

                        }

                    } // if( get_sub_field( 'pull-exclude-flex' ) === FALSE ) {

                endif;

                // DETAILS
                if( get_row_layout() == 'pull-details' ):

                    $dtitle = get_sub_field( 'pull-title-flex' );
                    if( !empty( $dtitle ) ) {
                        $bars[ 'title' ] = $dtitle;
                    } else {
                        $bars[ 'title' ] = '';
                    }

                    $dcredit = get_sub_field( 'pull-credit-flex' );
                    if( !empty( $dcredit ) ) {
                        $bars[ 'credit' ] = $dcredit;
                    } else {
                        $bars[ 'credit' ] = '';
                    }

                    $dsummary = get_sub_field( 'pull-summary-flex' );
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
     * Main MULTI PULL function call
     */
    public function setup_pull_multi( $block ) {

        $out = ''; // declare empty variable

        $o = new SetupPullVariables();

        global $bars;

        $etemplate = get_field( 'pull-template-multi' );

        // FIELDS TO SHOW
        $fs = get_field( 'pull-show-fields-multi' );
        if( !empty( $fs ) ) {
            $bars[ 'field_control' ] = $fs;
        } else {
            $bars[ 'field_control' ] = array( 'none' );
        }

        // INFO TAB | TITLE
        $info_title = get_field( 'pull-info-title-multi' );
        if( !empty( $info_title ) && $this->setup_field_control_validation( 'info-title', $fs ) ) {
            $info_out = '<div class="item-info-title">'.$info_title.'</div>';
        } else {
            $info_out = ''; // declare empty variable for summary
        }

        // INFO TAB | SUMMARY
        $info_summary = get_field( 'pull-info-summary-multi' );
        if( !empty( $info_summary ) && $this->setup_field_control_validation( 'info-summary', $fs ) ) {
            $info_out .= '<div class="item-info-summary">'.$info_summary.'</div>';
        }

        // INFO TAB | POSITION
        $itpos = get_field( 'pull-info-position-multi' );

        // SOURCE
        $esource = get_field( 'pull-show-source-multi' );
        if( !empty( $esource ) ) {
            $bars[ 'sources' ] = TRUE;
        } else {
            $bars[ 'sources' ] = FALSE;
        }
        
        // ENTRIES
        $entries = get_field( 'pull-entries-multi' );
        if( is_array( $entries ) ) {
            
            // loop through the RELATIONSHIP field
            foreach( $entries as $pid ) {

                $bars[ 'pid' ] = $pid;

                $out .= $this->setup_pull_view_template( $etemplate, 'views' );

            }

        }

        // TAXONOMY
        $tax_post = get_field( 'pull-post-type-multi' );
        $tax_type = get_field( 'pull-taxonomy-multi' );
        $max_e = get_field( 'pull-tax-max-multi' );
        if( !empty( $tax_post ) && !empty( $tax_type ) ) {

            // loop through the tax field
            foreach( $tax_type as $tax ) {

                // capture the taxonomy
                if( empty( $taxes_tax ) )
                    $taxes_tax = $tax->taxonomy;

                $taxes[] = $tax->slug;

            }

            // post per page count (max entries to show)
            if( $max_e <= 0 ) {
                $max_ppp = -1;
            } else {
                $max_ppp = $max_e;
            }

            $argz = array(
                'post_type'         => $tax_post,
                'post_status'       => 'publish',
                'posts_per_page'    => $max_ppp,
                'tax_query'         => array(
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

                $post_ids = array();

                // get all post IDs
                while( $loop->have_posts() ): $loop->the_post();
                    
                    if( !in_array( get_the_ID(), $post_ids ) ) {

                        $post_ids[] = get_the_ID();

                        $bars[ 'pid' ] = get_the_ID();
                    
                        $out .= $this->setup_pull_view_template( $etemplate, 'views' );

                    }
                    
                endwhile;

                /* Restore original Post Data 
                 * NB: Because we are using new WP_Query we aren't stomping on the 
                 * original $wp_query and it does not need to be reset.
                */
                wp_reset_postdata();

            endif;

        } else {

            if( empty( $tax_post ) && !empty( $tax_type ) ) {
                $out .= '<div class="item-missing">Please specify the <b>post type</b> to pull from</div>';
            }

            if( !empty( $tax_post ) && empty( $tax_type ) ) {
                $out .= '<div class="item-missing">Please specify the <b>taxonomy</b> to pull from</div>';
            }

        }
        
        // SECTION CLASS
        $section_class = array(
            'block_class'               => $this->setup_array_validation( 'className', $block ) ? $block[ 'className' ] : '',
            'item_class'                => get_field( 'pull-section-class-multi' ),
            'manual_class'              => '',
        );
        $sec_class = $this->setup_pull_combine_classes( $section_class );
        if( !empty( $sec_class ) ) {
            $sc = ' class="'.$sec_class.'"';
        } else {
            $sc = '';
        }

        // SECTION STYLE
        $section_styles = array(
            'item_style'                => get_field( 'pull-section-style-multi' ),
            'manual_style'              => '',
        );
        $sec_style = $this->setup_pull_combine_styles( $section_styles );
        if( !empty( $sec_style ) ) {
            $ss = ' style="'.$sec_style.'"';
        } else {
            $ss = '';
        }
        
        // OUTPUT
        if( !empty( $info_title ) || !empty( $info_summary ) ) {

            // info tab available | check position
            if( 'top' == $itpos ) {
                echo '<div'.$sc.$ss.'>'.$info_out.$out.'</div>';
            } else {
                echo '<div'.$sc.$ss.'>'.$out.$info_out.'</div>';
            }

        } else {

            // info tab empty
            echo '<div'.$sc.$ss.'>'.$out.'</div>';

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

        $block_class = !empty( $classes[ 'block_class' ] ) ? $classes[ 'block_class' ] : '';
        $item_class = !empty( $classes[ 'item_class' ] ) ? $classes[ 'item_class' ] : '';
        $manual_class = !empty( $classes[ 'manual_class' ] ) ? $classes[ 'manual_class' ] : '';

        $return = '';

        /*if( !empty( $block_class ) ) {
            // PULL | SINGLE
            if( is_numeric( $block_class ) ) {
                return $manual_class.' '.$item_class;   
            } else {
                return $manual_class.' '.$block_class.' '.$item_class;  
            }
        } else {
            // PULL | MULTI
            return $item_class; 
        }*/
        $ar = array( $block_class, $item_class, $manual_class );
        for( $z=0; $z<=( count( $ar ) - 1 ); $z++ ) {

            if( !empty( $ar[ $z ] ) ) {

                $return .= $ar[ $z ];

                if( $z != ( count( $ar ) - 1 ) ) {
                    $return .= ' ';
                }

            }

        }

        return $return;

    }


    /**
     * Combine Classes for the template
     */
    public function setup_pull_combine_styles( $styles ) {

        $manual_style = $styles[ 'manual_style' ];
        $item_style = $styles[ 'item_style' ];

        if( !empty( $manual_style ) && !empty( $item_style ) ) {
                return $manual_style.' '.$item_style;
        } else {

            if( empty( $manual_style ) && !empty( $item_style ) ) {
                return $item_style;
            } else {
                return $manual_style;
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
                // pull using entry SLUG
                $bars = array(
                    'title'             => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'title', $array[ 0 ] ) ) ? $array[ 0 ][ 'title' ][ 'rendered' ] : '',
                    'content'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'content', $array[ 0 ] ) ) ? $array[ 0 ][ 'content' ][ 'rendered' ] : '',
                    'excerpt'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'excerpt', $array[ 0 ] ) ) ? $array[ 0 ][ 'excerpt' ][ 'rendered' ] : '',
                    'featured-image'    => $this->setup_pull_featured_image( ( $this->setup_array_validation( 'featured_media', $array[ 0 ] ) ? $array[ 0 ][ 'featured_media' ] : '' ), $url_v, $img_size ),
                    'date-modified'     => $this->setup_array_validation( 'modified', $array[ 0 ] ) ? $array[ 0 ][ 'modified' ] : '',
                );
            } else {
                // pull using entry ID
                $bars = array(
                    'title'             => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'title', $array ) ) ? $array[ 'title' ][ 'rendered' ] : '',
                    'content'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'content', $array ) ) ? $array[ 'content' ][ 'rendered' ] : '',
                    'excerpt'           => $this->setup_array_validation( 'rendered', $this->setup_array_validation( 'excerpt', $array ) ) ? $array[ 'excerpt' ][ 'rendered' ] : '',
                    'featured-image'    => $this->setup_pull_featured_image( ( $this->setup_array_validation( 'featured_media', $array ) ? $array[ 'featured_media' ] : '' ), $url_v, $img_size ),
                    'date-modified'     => $this->setup_array_validation( 'modified', $array ) ? $array[ 'modified' ] : '',
                );
            }

            $bars[ 'block_class' ]  = !empty( $block[ 'className' ] ) ? $block[ 'className' ] : '';
            $bars[ 'wrap_sel' ]     = get_field( 'pull-section-class-remote' );
            $bars[ 'wrap_sty' ]     = get_field( 'pull-section-style-remote' );

            // SOURCE
            $sources = get_field( 'pull-show-source' );
            if( $sources === TRUE ) {

                if( is_numeric( $id ) ) {
                    $bars[ 'sourced' ] = rtrim( $url_raw, "/" ).'/'.$post_type.'/?page_id='.$id;
                } else {
                    $bars[ 'sourced' ] = rtrim( $url_raw, "/" ).'/'.$id;
                }
                
            } else {
                $bars[ 'sourced' ] = '';
            }
            

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


    /**
     * Field Control Array Validation
     */
    public function setup_field_control_validation( $field, $array ) {

        if( is_array( $array ) ) {

            if( in_array( $field, $array ) ) {
                return TRUE;
            } else {
                return FALSE;
            }

        } else {

            return FALSE;

        }
        
    }
    

}