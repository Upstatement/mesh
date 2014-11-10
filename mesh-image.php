<?php namespace Mesh;

class Image extends Post {

	function __construct( $url ) {
		parent::__construct( $url, 'attachment' );
	}

	protected function maybe_create_post( $url, $post_type ) {
		$image_exists = $this->check_if_image_exists( $url );
		if ($image_exists) {
			$upload_info = $this->get_image_data( $url );
			$id = $this->create_post( $upload_info );
		} else {
			$upload_info = $this->upload_image( $url );
			$id = $this->create_post( $upload_info );
		}
		return $id;
	}

	protected function create_post( $image_info, $post_type = 'attachment' ) {
		$filename = $image_info['file'];
		$pathinfo = pathinfo( $filename );
		$filetype = wp_check_filetype( basename( $filename ), null );
		$data = array( 
			'post_title' => $pathinfo['basename'], 
			'post_mime_type' => $filetype['type'],
			'guid' => $image_info['url'], 
			'post_type' => $post_type, 
			'post_content' => '',
			'post_status' => 'inherit' 
		);
		$pid = wp_insert_attachment( $data, $filename, 1 );
		if ( !function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}
		$metadata = wp_generate_attachment_metadata( $pid, $image_info['file'] );
		wp_update_attachment_metadata( $pid, $metadata );
		return $pid;
	}

	protected function get_image_data( $url ) {
		$location = self::get_sideloaded_file_loc( $url );
		$new_url = str_replace(ABSPATH, '', $location);
		$new_url = get_site_url().'/'.$new_url;
		$data = array('file' => $location, 'url' => $new_url);
		return $data;
	}

	protected function upload_image( $url ) {
		$location = self::get_sideloaded_file_loc( $url );
		if ( !function_exists( 'download_url' ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}
		$tmp = download_url( $url );
		$file_array = array();
		$file_array['tmp_name'] = $tmp;
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}
		// do the validation and storage stuff
		$locinfo = pathinfo( $location );
		return wp_upload_bits( $locinfo['basename'], null, file_get_contents( $file_array['tmp_name'] ) );

	}

	protected function check_if_image_exists( $url ) {
		$file_name_in_fs = self::get_sideloaded_file_loc( $url );
		if ( file_exists( $file_name_in_fs ) ) {
			return true;
		}
		return false;
	}

	//Image utils

	public static function get_sideloaded_file_loc( $url ) {
		$upload = wp_upload_dir();
		$dir = $upload['path'];
		$file = parse_url( $url );
		$path_parts = pathinfo( $file['path'] );
		$basename = md5( $url );
		$ext = 'jpg';
		if ( isset( $path_parts['extension'] ) ) {
			$ext = $path_parts['extension'];
		}
		return $dir . '/' . $basename . '.' . $ext;
	}

}
