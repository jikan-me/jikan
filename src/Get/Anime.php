<?php

namespace Jikan\Get;

use Jikan\Lib\Parser\AnimeParse;


class Anime extends Get
{



	public function __construct($id) {
		$this->id = $id;
        $this->parser = new AnimeParse("t");

		return $this;
	}

	public function episodes() {

	    return $this;
	}

	public function charactersStaff() {
	    return $this;
	}

}