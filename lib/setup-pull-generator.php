<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class SetupPullGen {

    public function setup_pull_gen_details() {

        return array(

        // DO NOT GO BEFORE THIS LINE
        // ################################

            // COPY FROM THE LINE BELOW ----------------------
            'pull-taxonomy' => array(
                
                'block' => array(
                    'name'                              => 'pull-taxonomy',
                    'title'                             => __('Pull Taxonomy'),
                    'icon'                              => 'block-default', // https://developer.wordpress.org/resource/dashicons/
                    'keywords'                          => array( 'pull', 'taxonomy' ),
                    'template'                          => 'setup-pull-general.php',
                ),

                'fields' => array(
                    // change the following to you exact fields
                    'post_type'                         => 'pull-tax-post-type',
                    'tax_type'                          => 'pull-tax-type',
                    'max_tax'                           => 'pull-tax-max',
                    'order_by'                          => 'pull-tax-order-by',
                    'order'                             => 'pull-tax-order',
                    'show_fields'                       => 'pull-tax-fields',
                    'title'                             => 'pull-tax-title',
                    'summary'                           => 'pull-tax-summary',
                    'info_tax_show_fields'              => 'pull-tax-fields-info',
                    'info_hide_all_fields'              => 'pull-tax-hide-all-fields',
                    'template'                          => 'pull-tax-template',
                    'wrap_sel'                          => 'pull-tax-sec-class',
                    'wrap_sty'                          => 'pull-tax-sec-style',
                    'show_source'                       => 'pull-tax-source',
                    'info_position'                     => 'pull-tax-info-pos',
                ),
                
            ),
            // COPY UNTIL THE LINE ABOVE ---------------------

            /*'info-block-media' => array(
                
                'block' => array(
                    'name'                              => 'info_block_media',
                    'title'                             => __('Info Block Media'),
                    'icon'                              => 'block-default', // https://developer.wordpress.org/resource/dashicons/
                    'keywords'                          => array( 'setup', 'information', 'info', 'media' ),
                    'template'                          => 'setup-blocks.php',
                ),

                'fields' => array(
                    // change the following to you exact fields
                    'title'                             => 'blocks-title',
                    'summary'                           => 'blocks-summary',
                    'blocks-show-fields'                => 'blocks-show-fields',
                    'blocks-hide-all-fields'            => 'blocks-hide-all-fields',
                    'image'                             => 'blocks-image',
                    'image_size'                        => 'blocks-image-size',
                    'video'                             => 'blocks-video',
                    'blocks-show-fields-media'          => 'blocks-show-fields-media',
                    'blocks-hide-all-fields-media'      => 'blocks-hide-all-fields-media',
                    'wrap_sel'                          => 'blocks-section-class',
                    'wrap_sty'                          => 'blocks-section-style',
                    'template'                          => 'blocks-template',
                ),
                
            ),*/

        // ################################
        // DO NOT GO AFTER THIS LINE

        );

    }

}