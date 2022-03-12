<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $gcounter;

class SetupPullMain {


	/**
	 * Main function call
	 */
	public function setup_pull_main( $block ) {

        global $bars;
		
        $pull_from = get_field( 'pull-from' );
        if( is_array( $pull_from ) && count( $pull_from ) >= 1 ) {

            $bars = array(
                'pid'               => $pull_from[ 0 ], // // get the post ID
                'source'            => get_field( 'pull-source' ),
                'title'             => get_field( 'pull-title' ),
                'credit'            => get_field( 'pull-credit' ),
                'summary'           => get_field( 'pull-summary' ),
                'wrap_sel'          => get_field( 'pull-section-class' ),
                'wrap_sty'          => get_field( 'pull-section-style' ),
                'block_class'       => $block[ 'className' ],
            );
            
            $out = $this->setup_pull_view_template( get_field( 'pull-template' ), 'views' );
            
        }

        echo $out;

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

}