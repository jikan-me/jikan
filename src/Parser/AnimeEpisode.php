<?php

namespace Jikan\Parser;

class AnimeEpisode extends \Skraypar\Skraypar
{
	public $model;

	public function __construct(&$model) {
		$this->model = &$model;
	}

    public function loadRules() {

    }
}