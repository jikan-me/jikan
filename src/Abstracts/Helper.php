<?php

namespace Jikan\Abstracts;

abstract class Helper extends \Jikan\Abstracts\Container
{

	public function __construct() {
		return $this;
	}

	public function set($key, $value) {
		$this->offsetSet($key, $value);
		return $this;
	}

	public function get($key) {
		return $this->offsetGet($key);
	}

	abstract public function build();

}