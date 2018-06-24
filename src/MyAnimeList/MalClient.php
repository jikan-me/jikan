<?php


use GuzzleHttp\Client as GuzzleClient;
use Jikan\Model\Anime;
use Jikan\Request\AnimeRequest;

/**
 * Class MalClient
 */
class MalClient
{
    /**
     * @var \Goutte\Client
     */
    private $ghoutte;

    /**
     * MalClient constructor.
     *
     * @param GuzzleClient|null $guzzle
     *
     * @internal param \Goutte\Client $ghoutte
     */
    public function __construct(GuzzleClient $guzzle = null)
    {
        $this->ghoutte = new \Goutte\Client();
        if ($guzzle !== null) {
            $this->ghoutte->setClient($guzzle);
        }
    }

    /**
     * @param AnimeRequest $request
     *
     * @return Anime
     */
    public function getAnime(AnimeRequest $request): Anime
    {
        $crawler = $this->ghoutte->request('GET', $request->getPath());
        $parser = new \Jikan\Parser\Anime($crawler);

        return $parser->getModel();
    }
}
