<?php

namespace Jikan\Abstracts;

abstract class Requests
{

	private $path;
	private $id;

	public function setPath(string $path) {
		$this->path = $path;

		return $this;
	}

	public function setID(int $id) {
		$this->id = $id;

		return $this;
	}

	public function getPath() {
		return $this->path;
	}

	public function getID() {
		return $this->id;
	}
}