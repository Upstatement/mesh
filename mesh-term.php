<?php namespace Mesh;

class Term implements MeshObject {

	function __construct( $term_name, $taxonomy = 'post_tag' ) {
		if( is_string( $term_name ) && is_string( $taxonomy ) ) {
			$this->maybe_create( $term_name, $taxonomy );
		}
	}

	protected function maybe_create( $term_name, $taxonomy ) {
		if( taxonomy_exists( $taxonomy ) && !term_exists( $term_name ) ) {
			$this->create( $term_name, $taxonomy );
		}
	}

	protected function create( $term_name, $taxonomy ) {
		wp_insert_term( $term_name, $taxonomy );
	}

	public function set( $key, $value, $override = false ) {
		// placeholder
	}
}
