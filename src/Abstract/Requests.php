<?php

namespace Jikan\Abstract;

abstract class Requests
{

	private $path;
	private $id;

	public function setPath(string $path) {
		$this->path = $path;
	}

	public function setID(int $id) {
		$this->id = $id;
	}

	public function getPath() : string {
		return $this->path;
	}

	public function getID() : int {
		return $this->id;
	}
}