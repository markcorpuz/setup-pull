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

    $z = new SetupPullPluginDirectory();

    $blocks = array(

        'pull' => array(
            'name'                  => 'pull',
            'title'                 => __('Pull'),
            'render_template'       => $z->setup_plugin_dir_path().'partials/blocks/setup-pull-flex.php',
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
            'render_template'       => $z->setup_plugin_dir_path().'partials/blocks/setup-pull-url.php',
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
            'render_template'       => $z->setup_plugin_dir_path().'partials/blocks/setup-pull-local.php',
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
 * Auto fill Select options for VIEWS (HTML)
 *
 */
add_filter( 'acf/load_field/name=pull_html_view', 'acf_setup_load_template_choices_pull' );
function acf_setup_load_template_choices_pull( $field ) {
    
    $z = new SetupPullPluginDirectory();

    $file_extn = 'html';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'partials/views/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}


/**
 * Auto fill Select options
 *
 */
add_filter( 'acf/load_field/name=pull_layout', 'acf_setup_load_view_html_template_choices' );
function acf_setup_load_view_html_template_choices( $field ) {
    
    $z = new SetupPullPluginDirectory();

    $file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'partials/views/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}


/**
 * Get VIEW template | this function is called by SETUP-PULL-FLEX.PHP found in PARTIALS/BLOCKS folder
 *
 */
if( !function_exists( 'setup_acf_pull_view_template_pulls' ) ) {

	function setup_acf_pull_view_template_pulls( $layout, $args = FALSE ) {

        $z = new SetupPullPluginDirectory();

	    $layout_file = $z->setup_plugin_dir_path().'partials/views/'.$layout;
	    
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


/**
 * Pull all files found in $directory but get rid of the dots that scandir() picks up in Linux environments
 *
 */
if( !function_exists( 'setup_pulls_view_files' ) ) {

    function setup_pulls_view_files( $directory, $file_extn ) {

        $out = array();
        
        // get all files inside the directory but remove unnecessary directories
        $ss_plug_dir = array_diff( scandir( $directory ), array( '..', '.' ) );

        foreach( $ss_plug_dir as $filename ) {
            
            if( pathinfo( $filename, PATHINFO_EXTENSION ) == $file_extn ) {
                $out[ $filename ] = pathinfo( $filename, PATHINFO_FILENAME );
            }

        }

        /*foreach ($ss_plug_dir as $value) {
            
            // combine directory and filename
            $file = basename( $directory.$value, $file_extn );
            
            // filter files to include
            if( $file ) {
                $out[ $value ] = $file;
            }

        }*/

        // Return an array of files (without the directory)
        return $out;

    }
    
}


/**
 * Get VIEW template (INCLUDE)
 *
 */
function setup_pull_get_html_template_contents( $layout ) {

    $z = new SetupPullPluginDirectory();

    $layout_file = $z->setup_plugin_dir_path().'partials/views/'.$layout;

    return file_get_contents( $layout_file );

}