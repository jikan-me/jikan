<?php

namespace Jikan\Abstracts;

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

}