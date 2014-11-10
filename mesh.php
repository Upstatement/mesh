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
		require_once('mesh-post.php');
		require_once('mesh-image.php');
	}

}

Mesh::autoload();