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
    public function spull_wpquery( $args ) {

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

    }

}