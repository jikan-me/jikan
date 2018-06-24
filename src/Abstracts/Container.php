<?php

namespace Jikan\Abstracts;

abstract class Container implements \ArrayAccess
{
	
	public $container = [];

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->Container[] = $value;
		} else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->container[$offset] : null;
	}
	
}