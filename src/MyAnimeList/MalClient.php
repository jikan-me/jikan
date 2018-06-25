<?php

namespace Jikan\MyAnimeList;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Jikan\Model\Anime;
use Jikan\Request;
use Jikan\Parser;

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
     * @return Anime
     */
    public function getAnime(Request\Anime $request): Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new Parser\Anime($crawler);

        return $parser->getModel();
    }
}
