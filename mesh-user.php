<?php namespace Mesh;

	class User implements MeshObject {

		var $default_password = 'password';
		var $id;

		function __construct($display_name, $role = 'subscriber') {
			$maybe_id = intval($display_name);
			if ($display_name === $maybe_id) {
				$this->id = $maybe_id;
				return;
			}
			$this->id = $this->maybe_create( $display_name, $role );
		}

		protected function get_recognized_fields() {
			return array( 'ID', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name');
		}

		protected function maybe_create($display_name, $role) {
			$slug = sanitize_title( $display_name );
			$id = username_exists($slug);
			if ( !$id || $id == null ) {
				$id = $this->create( $display_name, $role );
			}
			return $id;
		}

		protected function create( $display_name, $role ) {
			$slug = sanitize_title( $display_name );
			$names = explode(' ', trim($display_name));
			$first_name = $names[0];
			$non_first_names = $names;
			array_shift($non_first_names);
			$last_name = implode(' ', $non_first_names);
			$data = array( 'display_name' => $display_name, 'user_pass' => $this->default_password, 'first_name' => $first_name, 'last_name' => $last_name, 'role' => $role, 'user_login' => $slug );
			$uid = wp_insert_user($data);
			return $uid;
		}

		public function set_image( $key, $url ) {
			$image = new Image( $url );
			$this->set($key, $image->id);
		}

		public function set( $key, $value, $override = false ) {
			if (in_array($key, self::get_recognized_fields())) {
				$this->update_recognized_field( $key, $value, $override );
			} else {
				$this->update_meta($key, $value, $override);
			}
		}

		protected function update_recognized_field( $key, $value, $override ) {
			$user = get_user_by('id', $this->id);
			if (!$override && isset( $post->$key ) && strlen( $post->$key ) ) {
				return;
			}
			$update_data = array( 'ID' => $this->id, $key => $value );
			wp_update_user($update_data);
		}

		protected function update_meta( $key, $value, $override ) {
			update_user_meta( $this->id, $key, $value );
			return;
			if ( $override ) {
				update_user_meta( $this->id, $key, $value );
				return;
			}
			add_user_meta( $this->id, $key, $value, true );
		}

	}
