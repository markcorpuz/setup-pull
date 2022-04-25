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
add_filter( 'block_categories_all', 'setup_block_categories_fn_pull' );
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

    $z = new SetupPullVariables();

    $blocks = array(

        'pull_remote' => array(
            'name'                  => 'pull_remote',
            'title'                 => __('Pull Remote'),
            'render_template'       => $z->setup_plugin_dir_path().'templates/blocks/setup-pull-remote.php',
            'category'              => 'setup',
            'icon'                  => 'embed-post',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'remote', 'rest api', 'api', 'rest', 'url' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),

        'pull_local_single' => array(
            'name'                  => 'pull_local_single',
            'title'                 => __('Pull Single'),
            'render_template'       => $z->setup_plugin_dir_path().'templates/blocks/setup-pull-local-single.php',
            'category'              => 'setup',
            'icon'                  => 'embed-post',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'single' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),

        'pull_local_multi' => array(
            'name'                  => 'pull_local_multi',
            'title'                 => __('Pull Multi'),
            'render_template'       => $z->setup_plugin_dir_path().'templates/blocks/setup-pull-local-multi.php',
            'category'              => 'setup',
            'icon'                  => 'embed-post',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'multi' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),

        'pull_local_multi_flex' => array(
            'name'                  => 'pull_local_multi_flex',
            'title'                 => __('Pull Multi Flex'),
            'render_template'       => $z->setup_plugin_dir_path().'templates/blocks/setup-pull-local-multi-flex.php',
            'category'              => 'setup',
            'icon'                  => 'embed-post',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'multi', 'flex' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),
        /*
        'pull_multisite' => array(
            'name'                  => 'pull_multisite',
            'title'                 => __('Pull Multisite'),
            'render_template'       => $z->setup_plugin_dir_path().'templates/blocks/setup-pull-multisite.php',
            'category'              => 'setup',
            'icon'                  => 'pressthis',
            'mode'                  => 'edit',
            'keywords'              => array( 'pull', 'get', 'content', 'multisite' ),
            'supports'              => [
                'align'             => false,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],            
        ),*/

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
/*add_filter( 'acf/load_field/name=pull_html_view', 'acf_setup_load_template_choices_pull' );
function acf_setup_load_template_choices_pull( $field ) {
    
    $z = new SetupPullVariables();

    $file_extn = 'html';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'templates/views/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}*/


/**
 * Auto fill Select options | ENTRIES
 *
 */
add_filter( 'acf/load_field/name=pull-template', 'acf_setup_load_view_template_choices' ); // SINGLE
add_filter( 'acf/load_field/name=pull-template-global', 'acf_setup_load_view_template_choices' ); // MULTI - GLOBAL
add_filter( 'acf/load_field/name=pull-template-multi', 'acf_setup_load_view_template_choices' ); // MULTI - ENTRIES
add_filter( 'acf/load_field/name=pull-template-flex', 'acf_setup_load_view_template_choices' ); // MULTI - FLEX
add_filter( 'acf/load_field/name=pull-template-remote', 'acf_setup_load_view_template_choices' ); // REMOTE
function acf_setup_load_view_template_choices( $field ) {
    
    $z = new SetupPullVariables();

    $file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'templates/views/';

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
 * Auto fill Select options | DETAILS
 *
 */
add_filter( 'acf/load_field/name=pull-details-template-global', 'acf_setup_load_details_template_choices' ); // MULTI - ENTRIES
function acf_setup_load_details_template_choices( $field ) {
    
    $z = new SetupPullVariables();

    $file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'templates/details/';

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
 * Auto fill Checkbox options | Fields to Pull | Remote
 *
 */
add_filter( 'acf/load_field/name=pull-fields-remote', 'acf_setup_load_field_choices' ); // MULTI - ENTRIES
function acf_setup_load_field_choices( $field ) {
    
    $z = new SetupPullVariables();

    $field['choices'] = array();

    $fielders = $z->setup_pull_remote_fields();
    if( is_array( $fielders ) ) :
        
        foreach( $fielders as $key => $value ) {
            $field['choices'][$key] = $value;
        }

        return $field;

    endif;

    /*$file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'templates/details/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }*/
    
}


/**
 * Auto fill Select options | pull_from_site
 *
 */
//add_filter( 'acf/load_field/name=pull_from_site', 'acf_setup_subsite_choices' );
function acf_setup_subsite_choices( $field ) {
    
    $field['choices'] = array();

    $sites = get_sites();
    foreach ($sites as $key => $value) {
//        echo '<h1>'.$key.'</h1>';
        /*echo $value->blog_id;
        echo ' | ';
        echo $value->domain;
        echo ' | ';
        echo $value->path;*/

//        var_dump( $value );

        $field['choices'][$value->blog_id] = $value->domain.$value->path;
    }
    /*$z = new SetupPullVariables();

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

    }*/

    return $field;
    
}


/**
 * Get VIEW template | this function is called by SETUP-PULL-FLEX.PHP found in PARTIALS/BLOCKS folder
 *
 */
if( !function_exists( 'setup_acf_pull_view_template_pulls' ) ) {

	function setup_acf_pull_view_template_pulls( $layout, $args = FALSE ) {

        $z = new SetupPullVariables();

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
/*function setup_pull_get_html_template_contents( $layout ) {

    $z = new SetupPullVariables();

    $layout_file = $z->setup_plugin_dir_path().'partials/views/'.$layout;

    return file_get_contents( $layout_file );

}*/

/**
 * Auto fill Select options | DETAILS
 *
 */
add_filter( 'acf/load_field/name=pull-post-type-flex', 'acf_setup_load_posttype_choices' ); // MULTI FLEX - ENTRIES
add_filter( 'acf/load_field/name=pull-post-type-multi', 'acf_setup_load_posttype_choices' ); // MULTI - ENTRIES
function acf_setup_load_posttype_choices( $field ) {
    
    $z = new SetupPullVariables();

    $post_types = get_post_types( '', 'names' ); 

    $field[ 'choices' ] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $post_types ) ) {

        foreach( $post_types as $key => $post_type ) {

            if( !in_array( $post_type, $z->setup_not_from_these_posttypes() ) ) {

                $field[ 'choices' ][ $key ] = $post_type;
                
            }
            
        }

        return $field;

    }
    
}