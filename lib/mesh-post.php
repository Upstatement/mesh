<?php namespace Mesh;

class Post implements MeshObject {

	var $id;

	function __construct( $title, $post_type = 'post' ) {
		$maybe_id = intval( $title );
		if ( $title === $maybe_id ) {
			$this->id = $maybe_id;
			return;
		}
		$this->id = $this->maybe_create( $title, $post_type );
	}

	protected function get_recognized_fields() {
		return array( 'ID', 'post_title', 'post_content', 'post_name', 'post_status', 'post_type', 'post_author', 'ping_status', 'post_parent', 'menu_order', 'to_ping', 'pinged', 'post_password', 'guid', 'post_excerpt', 'post_date', 'post_date_gmt', 'comment_status', 'post_content_filtered' );
	}

	protected function maybe_create( $title, $post_type ) {
		$slug = sanitize_title( $title );
		$id = $this->check_if_post_exists( $slug, $post_type );
		if ( !$id ) {
			$id = $this->create( $title, $post_type );
		}
		return $id;

	}

	protected function create( $title, $post_type ) {
		//insert post
		$data = array( 'post_title' => $title, 'post_type' => $post_type, 'post_status' => 'publish' );
		return wp_insert_post( $data );
	}

	protected function check_if_post_exists( $slug, $post_type ) {
		global $wpdb;
		$row = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_name = '$slug'" );
		if ( $row && isset( $row->ID ) ) {
			return $row->ID;
		}
		return false;
	}

	protected function update_meta( $key, $value, $override ) {
		if ( $override ) {
			update_post_meta( $this->id, $key, $value );
			return;
		}
		add_post_meta( $this->id, $key, $value, true );
	}

	protected function update_recognized_field( $key, $value, $override = false ) {
		$post = get_post( $this->id );
		if ( !$override && isset( $post->$key ) && strlen( $post->$key ) ) {
			return;
		}
		$update_data = array( 'ID' => $this->id, $key => $value );
		wp_update_post( $update_data );
	}

	protected function update_thumbnail( $url, $override = false ) {
		$thumbnail_id = get_post_meta( $this->id, '_thumbnail_id', true );
		if ( $thumbnail_id && !$override ) {
			return;
		}
		$image = new Image( $url );
		$this->set( '_thumbnail_id', $image->id );
		$image->set( 'post_parent', $this->id );
	}

	public function set_image( $key, $url, $override = false ) {
		if ($key === 'thumbnail') {
			$this->update_thumbnail( $url, $override );
		} else {
			$image = new Image( $url );
			$this->set( $key, $image->id );
		}
	}

	public function set_terms( $key, $terms ) {
		foreach( $terms as $term_data ) {
			$term = new Term($term_data->name, $term_data->taxonomy);
			wp_set_object_terms( $this->id, intval($term->id), $term_data->taxonomy, true );
		}
	}

	public function set( $key, $value, $override = false ) {
		if ( in_array( $key, self::get_recognized_fields() ) ) {
			$this->update_recognized_field( $key, $value, $override = true );
		} else {
			$this->update_meta( $key, $value, $override );
		}
	}

}
