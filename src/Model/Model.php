<?php

namespace Jikan\Model;

abstract class Model
{

	public function set($class, $key, $value) {
		if (property_exists('Jikan\Model\\'.$class, $key)) {
			$this->{$key} = $value;
		}
	}

	public function get($class, $key) {
		if (property_exists('Jikan\Model\\'.$class, $key)) {
			return $this->{$key};
		}
	}

	public function insert($class, $key_array, $value, $key=null) {
		if (property_exists('Jikan\Model\\'.$class, $key)) {
			if (is_null($key)) {
				$this->{$key_array}[] = $value;
			} else {
				$this->{$key_array}[$key] = $value;
			}
		}
	}
	
}