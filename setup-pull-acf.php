<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/**
 * Add a block category for "Setup" if it doesn't exist already.
 *
 * @ param array $categories Array of block categories.
 *
 * @ return array
 */
add_filter( 'block_categories', 'setup_block_categories_fn_pull' );
function setup_block_categories_fn_pull( $categories ) {

    $category_slugs = wp_list_pluck( $categories, 'slug' );

    return in_array( 'setup', $category_slugs, TRUE ) ? $categories : array_merge(
        array(
            array(
                'slug'  => 'setup',
                'title' => __( 'Setup', 'mydomain' ),
                'icon'  => null,
            ),
        ),
        $categories
    );

}


/**
 * LOG (Custom Blocks)
 * Register Custom Blocks
 * 
 */
add_action( 'acf/init', 'setup_pull_block_acf_init' );
function setup_pull_block_acf_init() {

    $blocks = array(

        'pull' => array(
            'name'                  => 'pull',
            'title'                 => __('Pull'),
            'render_template'       => plugin_dir_path( __FILE__ ).'partials/blocks/setup-pull-flex.php',
            'category'              => 'setup',
            'icon'                  => 'pressthis',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],
        ),

        'pull_url' => array(
            'name'                  => 'pull_url',
            'title'                 => __('Pull URL'),
            'render_template'       => plugin_dir_path( __FILE__ ).'partials/blocks/setup-pull-url.php',
            'category'              => 'setup',
            'icon'                  => 'pressthis',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'content', 'url' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),

        'pull_local' => array(
            'name'                  => 'pull_local',
            'title'                 => __('Pull Local'),
            'render_template'       => plugin_dir_path( __FILE__ ).'partials/blocks/setup-pull-local.php',
            'category'              => 'setup',
            'icon'                  => 'pressthis',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'content', 'url' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),

    );

    // Bail out if function doesn’t exist or no blocks available to register.
    if ( !function_exists( 'acf_register_block_type' ) && !$blocks ) {
        return;
    }

	foreach( $blocks as $block ) {
		acf_register_block_type( $block );
	}
  
}


/**
 * Auto fill Select options
 *
 */
add_filter( 'acf/load_field/name=pull_layout', 'acf_setup_load_template_choices_pull' );
function acf_setup_load_template_choices_pull( $field ) {
    
    // get all files found in VIEWS folder
    $view_dir = plugin_dir_path( __FILE__ ).'partials/views/';

    $data_from_database = setup_pull_view_files( $view_dir );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_database ) ) {

        foreach( $data_from_database as $field_key => $field_value ) {
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}


/**
 * Get VIEW template | this function is called by SETUP-LOG-FLEX.PHP found in PARTIALS/BLOCKS folder
 */
if( !function_exists( 'setup_acf_pull_view_template_pulls' ) ) {

	function setup_acf_pull_view_template_pulls( $layout, $args = FALSE ) {

	    $layout_file = plugin_dir_path( __FILE__ ).'partials/views/'.$layout;
	    
	    if( $args ) {

	        if( array_key_exists( 'id', $args ) ) {

	            global $pid;

	            $pid = $args[ 'id' ];

	        }
	        
	    }
	    
	    if( is_file( $layout_file ) ) {

	        ob_start();

	        include $layout_file;

            return ob_get_clean();
	        /*$new_output = ob_get_clean();
	            
	        if( !empty( $new_output ) )
	            $output = $new_output;
            */
	    } else {

	        //$output = FALSE;
            return FALSE;

	    }

	    //return $output;

	}

}


// pull all files found in $directory but get rid of the dots that scandir() picks up in Linux environments
if( !function_exists( 'setup_pull_view_files' ) ) {

    function setup_pull_view_files( $directory ) {

        $out = array();
        
        // get all files inside the directory but remove unnecessary directories
        $ss_plug_dir = array_diff( scandir( $directory ), array( '..', '.' ) );
        
        foreach ($ss_plug_dir as $value) {
            
            // combine directory and filename
            $file = basename( $directory.$value, ".php" );

            // filter files to include
            if( $file ) {
                $out[ $value ] = $file;
            }

        }

        // Return an array of files (without the directory)
        return $out;

    }
    
}

