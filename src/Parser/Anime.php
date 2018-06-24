<?php

namespace Jikan\Parser;

use Jikan\Helper\JString as JString;
use Goutte\Client;

class Anime
{
	public $model;
	public $response;

    public function __construct(\Jikan\Request\Anime &$request)
    {

		$request->client = new Client;
		$request->crawler = $request->client->request('GET', $request->getPath());

		$request->model->set('Anime', 'title', 
			$request->crawler->filterXpath('//meta[@property=\'og:title\']')->extract(['content'])[0]
		);
		$request->model->set('Anime', 'image_url', 
			$request->crawler->filterXpath('//meta[@property=\'og:image\']')->extract(['content'])[0]
		);
		$request->model->set('Anime', 'link_canonical', 
			$request->crawler->filterXpath('//meta[@property=\'og:url\']')->extract(['content'])[0]
		);
		$request->model->set('Anime', 'synopsis', 
			$request->crawler->filterXpath('//meta[@property=\'og:description\']')->extract(['content'])[0]
		);


        return $this->response = [
            'status'   => $request->client->getResponse()->getStatus(),
            'response' => (array) $request->model,
        ];
    }
}