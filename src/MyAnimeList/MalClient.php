<?php

namespace Jikan\MyAnimeList;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Jikan\Model;
use Jikan\Parser;
use Jikan\Request;

/**
 * Class MalClient
 */
class MalClient
{
    /**
     * @var Client
     */
    private $ghoutte;

    /**
     * MalClient constructor.
     *
     * @param GuzzleClient|null $guzzle
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->ghoutte = new Client();
        if ($guzzle !== null) {
            $this->ghoutte->setClient($guzzle);
        }
    }

    /**
     * @param Request\Anime $request
     *
     * @return Model\Anime
     */
    public function getAnime(Request\Anime $request): Model\Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime($crawler);

        return $parser->getModel();
    }

    /**
     * @param Request\Seasonal $request
     *
     * @return Model\Seasonal
     */
    public function getSeasonal(Request\Seasonal $request): Model\Seasonal
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Seasonal($crawler);

        return $parser->getModel();
    }
}
