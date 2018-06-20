<?php

namespace Jikan\Get;

use Jikan\Request\Anime as AnimeRequest;
use Jikan\Model\Anime as AnimeModel;
use Jikan\Parser\Anime as AnimeParser;

class Anime
{
	public $parser;
	public $response;

	public function __construct(AnimeRequest &$request) {

		$this->parser = new AnimeParser($request->model);
		$this->parser->setPath($request->getPath());
		$this->parser->loadFile();
		$this->parser->loadRules();

		$this->parser->parse();

		return $this->response = [
			'status' => $this->parser->status,
			'response' => (array) $this->parser->model
		];

	}

}