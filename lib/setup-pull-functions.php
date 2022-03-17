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
        

        $pull_from = get_field( 'pull-flexi' );
        if( is_array( $pull_from ) ) {

            // declare empty block class to tell the template that this is multi pull
            $bars[ 'block_class' ] = '';

            // loop through flexible content field
            foreach( $pull_from as $pulls ) {

                // ENTRIES
                if( 'pull-entry' == $pulls[ 'acf_fc_layout' ] ) {

                    // source
                    $esource = $pulls[ 'pull-show-source-multi' ];
                    if( $esource === 'show' ) {
                        $bars[ 'sources' ] = TRUE;
                    } else {
                        $bars[ 'sources' ] = FALSE;
                    }

                    // class
                    $eclass = $pulls[ 'pull-section-class-multi' ];
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
                    $estyle = $pulls[ 'pull-section-style-multi' ];
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
                    if( $this->setup_array_validation( 'pull-override-global', $pulls ) ) {
                        $etemplate = $pulls[ 'pull-template-multi' ];
                    } else {
                        $etemplate = $tge_template;
                    }

                    // PULL ENTRY | RELATIONSHIP FIELD
                    if( $this->setup_array_validation( 'pull-from-multi', $pulls ) && is_array( $pulls[ 'pull-from-multi' ] ) ) {                
                        
                        // loop through the RELATIONSHIP field
                        foreach( $pulls[ 'pull-from-multi' ] as $pid ) {
                            
                            $bars[ 'pid' ] = $pid;

                            $out .= $this->setup_pull_view_template( $etemplate, 'views' );

                        }
                        
                    }

                    // loop through the TAXONOMY field
                    if( $this->setup_array_validation( 'pull-tax-group', $pulls ) && is_array( $pulls[ 'pull-tax-group' ] ) ) {

                        $pids = array(); // declare empty variable

                        $pt_group = $pulls[ 'pull-tax-group' ];

                        // tax variable
                        $ptm = $pt_group[ 'pull-taxonomy-multi' ];

                        if( $this->setup_array_validation( 'pull-post-type', $pt_group ) && is_array( $pt_group[ 'pull-post-type' ] ) ) {

                            // Post Type loop
                            foreach( $pt_group[ 'pull-post-type' ] as $pt_id ) {

                                // Tax Type loop
                                foreach( $ptm as $tax ) {

                                    $args = array(
                                        'post_type'         => $pt_id,
                                        'post_status'       => 'publish',
                                        'posts_per_page'    => -1,
                                        'post__not_in'      => $pids, // variable must be array
                                        'orderby'           => 'date',
                                        'order'             => 'DESC',
                                        'tax_query'         => array(
                                            array(
                                                'taxonomy'      => $tax->taxonomy,
                                                'field'         => 'term_id',
                                                'terms'         => $tax->term_taxonomy_id,
                                            ),
                                        ),
                                    );

                                    $pids = array_unique( array_merge( $pids, $this->spull_wpquery( $args ) ) );
                                    
                                }

                            }

                        }
                        
                        // handle taxonomy output
                        if( count( $pids ) > 0 ) {
                            foreach( $pids as $ids ) {

                                $bars[ 'pid' ] = $ids;

                                $out .= $this->setup_pull_view_template( $etemplate, 'views' );

                            }
                        }

                    }

                }

                // DETAILS
                if( 'pull-details' == $pulls[ 'acf_fc_layout' ] ) {
                    
                    $dtitle = $pulls[ 'pull-title-multi' ];
                    if( !empty( $dtitle ) ) {
                        $bars[ 'title' ] = $dtitle;
                    } else {
                        $bars[ 'title' ] = '';
                    }

                    $dcredit = $pulls[ 'pull-credit-multi' ];
                    if( !empty( $dcredit ) ) {
                        $bars[ 'credit' ] = $dcredit;
                    } else {
                        $bars[ 'credit' ] = '';
                    }

                    $dsummary = $pulls[ 'pull-summary-multi' ];
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

                }
                
            }

        }

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