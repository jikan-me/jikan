<?php

namespace Jikan\Get;

use Jikan\Model\Anime as AnimeModel;

class Anime
{

	public $id;
	public $model;


	public function __construct($id) {
		$this->id = $id;
        $this->model = new AnimeModel();



		return $this;
	}

	public function episodes() {

	    return $this;
	}

	public function charactersStaff() {
	    return $this;
	}

}