<?php namespace Mesh;

	class JSON_Loader {

		function __construct($file) {
			$this->import_json_file($file);
		}

		protected function import_json_file($file) {
			$data = file_get_contents($file);
			$json = json_decode($data);
			if (isset($json->users)) {
				$this->import_users($json->users);
			}
			if (isset($json->posts)) {
				$this->import_posts($json->posts);
			}
		}

		protected function import_users($array) {
			foreach($array as $user_data) {
				$user = new User($user_data->display_name);
				foreach($user_data as $key => $value) {
					if (strstr($key, ':image')) {
						//insert image
						$image_key = explode(':', $key);
						$user->set_image($image_key[0], $value);
					} else {
						$user->set($key, $value);
					}

				}
			}
		}

		protected function import_posts($array) {

		}
	}
