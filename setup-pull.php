<?php
/**
 * Plugin Name: Setup Pull
 * Description: Utilize custom Guttenburg block to pull post entry fields.
 * Version: 2.0.0
 * Author: Jake Almeda & Mark Corpuz
 * Author URI: https://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// include required functions that needs to be executed in the main directory
class SetupPullVariables {

    // list of local fields to pull
    public function setup_pull_local_fields() {

        return array(
            'title'                         => 'WP Title',
            'content'                       => 'WP Content',
            'excerpt'                       => 'Excerpt',
            'featured_media'                => 'Featured Image',
            'featured_media_link'           => 'Featured Image Link',
            'modified'                      => 'Date Modified',
            'date_published'                => 'Date Published',
//            'info-title'                    => 'Title (Info)',
//            'info-summary'                  => 'Summary (Info)',
        );

    }

    // list of local default fields to pull
    public function setup_pull_local_default_fields() {
        //return array( 'title', 'content' );
        return array( 'title' );
    }

    // list of info tab (block) fields to pull
    public function setup_info_block_fields() {

        return array(
            'title'             => 'Title',
            'summary'           => 'Summary',
        );

    }

    // list of local default fields to pull
    public function setup_info_block_default_fields() {
        //return array( 'title', 'content' );
        return array( 'title' );
    }

    // list of remote fields to pull
    public function setup_pull_remote_fields() {

        return array(
            'title'             => 'WP Title',
            'content'           => 'WP Content',
            'excerpt'           => 'Excerpt',
            'featured_media'    => 'Featured Image',
            'modified'          => 'Date Modified',
        );

    }

    // simply return this plugin's main directory
    public function setup_plugin_dir_path() {

        return plugin_dir_path( __FILE__ );

    }

    // list of excluded post types from MULTI option
    public function setup_not_from_these_posttypes() {

        return array(
            'attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'user_request',
            'wp_block',
            'wp_template',
            'wp_template_part',
            'wp_global_styles',
            'wp_navigation',
            'acf-field-group',
            'acf-field',
            '_pods_pod',
            '_pods_group',
            '_pods_field',
        );

    }

}


// include file
include_once( 'lib/setup-pull-acf.php' );
include_once( 'lib/setup-pull-functions.php' );
//include_once( 'lib/setup-pull-variables.php' );

