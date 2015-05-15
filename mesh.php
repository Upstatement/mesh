<?php
/*
Plugin Name: Mesh
Plugin URI: http://upstatement.com/mesh
Description: Mesh bootstraps content into WordPress
Author: Jared Novack + Upstatement
Version: 0.0.1
Author URI: http://upstatement.com/
*/

class Mesh {

	public static function autoload(){
		require_once('lib/mesh-object.php');
		require_once('lib/mesh-post.php');
		require_once('lib/mesh-image.php');
		require_once('lib/mesh-user.php');
		require_once('lib/mesh-term.php');

		require_once('lib/mesh-json-loader.php');
	}

	public static function add_actions() {
		add_action('wp_login', array('Mesh', 'load_json'));
	}

	public static function load_json() {
		$file = trailingslashit(get_stylesheet_directory()).'mesh.json';
		$file = apply_filters('mesh/load_json', $file);
		$loader = new Mesh\JSON_Loader($file);
	}

}

Mesh::autoload();
Mesh::add_actions();
