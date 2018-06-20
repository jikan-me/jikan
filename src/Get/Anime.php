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

		$request->parser->setPath($request->getPath());
		$request->parser->loadRules();
		$request->parser->loadFile();
		$request->parser->parse();

		return $this->response = [
			'status' => $request->parser->status,
			'response' => (array) $request->parser->model
		];

	}

}