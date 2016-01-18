<?php
/**
 * Implements example command.
 */

if (! class_exists('WP_CLI_Command')) {
	return;
}

class Mesh_Command extends WP_CLI_Command {

    /**
     * Prints a greeting.
     *
     * ## OPTIONS
     *
     * <file>
     * : File to load
     *
     * ## EXAMPLES
     *
     *     wp mesh load_json
     *
     * @synopsis <name>
     */
    function load_json( $args = ['mesh.json'], $assoc_args ) {
    	list( $filename ) = $args;
    	$filename = trailingslashit(get_stylesheet_directory()).$filename;
        $loader = new Mesh\JSON_Loader($filename);
    }
}

WP_CLI::add_command( 'mesh', 'Mesh_Command' );
