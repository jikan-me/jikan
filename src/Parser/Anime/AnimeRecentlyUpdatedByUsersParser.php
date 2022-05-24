<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeUserUpdates;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Anime
 */
class AnimeRecentlyUpdatedByUsersParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeRecentlyUpdatedByUsersParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        try {
            return $this->crawler
                ->filterXPath('//table[@class="table-recently-updated"]/tr[1]')
                ->nextAll()
                ->each(
                    function ($c) {
                        return (new AnimeRecentlyUpdatedByUsersListParser($c))->getModel();
                    }
                );
        } catch (\Exception $e) {
            return [];
        }
    }

    // @todo anime user updates pagination
    public function getHasNextPage() : bool
    {
        return false;
    }

    public function getLastPage() : int
    {
        return 1;
    }

    public function getModel(): AnimeUserUpdates
    {
        return AnimeUserUpdates::fromParser($this);
    }
}
