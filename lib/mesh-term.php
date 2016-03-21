<?php namespace Mesh;

class Term implements MeshObject {

	function __construct( $term_name, $taxonomy = 'post_tag' ) {
		if( is_string( $term_name ) && is_string( $taxonomy ) ) {
			$this->id = $this->maybe_create( $term_name, $taxonomy );
			$this->taxonomy = $taxonomy;
		}
	}

	/*
	 * @return int
	 */
	protected function maybe_create( $term_name, $taxonomy ) {
		if (!taxonomy_exists($taxonomy)) {
			register_taxonomy($taxonomy, 'post');
		}
		if( taxonomy_exists( $taxonomy ) && !term_exists( $term_name ) ) {
			return $this->create( $term_name, $taxonomy );
		}
		$result = term_exists($term_name, $taxonomy);
		if (is_array($result) && array_key_exists('term_id', $result)) {
			return $result['term_id'];
		}
	}

	protected function create( $term_name, $taxonomy ) {
		$result = wp_insert_term( $term_name, $taxonomy );
		if (is_array($result) && array_key_exists('term_id', $result)) {
			return $result['term_id'];
		}
	}

	public function set( $key, $value, $override = false ) {
		if( $key !== "name" && $key !== "taxonomy" ) {
			$data = array( $key => $value );
			wp_update_term( $this->id,  $this->taxonomy, $data );
		}
	}
}
