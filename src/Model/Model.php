<?php

namespace Jikan\Model;

abstract class Model
{

	public function set($key, $value) {
		if (property_exists('Jikan\Model\Anime', $key)) {
			$this->{$key} = $value;
		}
	}

	public function get($key) {
		if (property_exists('Jikan\Model\Anime', $key)) {
			return $this->{$key};
		}
	}

	public function insert($key_array, $value, $key=null) {
		if (property_exists('Jikan\Model\Anime', $key)) {
			if (is_null($key)) {
				$this->{$key_array}[] = $value;
			} else {
				$this->{$key_array}[$key] = $value;
			}
		}
	}
	
}