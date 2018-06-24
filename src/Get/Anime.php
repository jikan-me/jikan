<?php

namespace Jikan\Get;

use Jikan\Request\AnimeRequest as AnimeRequest;
use Jikan\Model\Anime as AnimeModel;
use Jikan\Parser\Anime as AnimeParser;
use Goutte\Client;

class Anime
{
    public $response;

    public function __construct(AnimeRequest &$request)
    {

		$request->client = new AnimeParser;
		$request->crawler = $request->client->request('GET', $request->getPath());
		$data = $request->crawler->filterXpath('//meta[@name=\'description\']')->extract(['content'])[0];


        return $this->response = [
            'status'   => $request->client->getResponse()->getStatus(),
            'response' => (array) $request->parser->model,
        ];
    }

}
