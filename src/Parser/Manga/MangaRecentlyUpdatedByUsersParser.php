<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\Manga;
use Jikan\Model\Manga\MangaUserUpdates;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Manag
 */
class MangaRecentlyUpdatedByUsersParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaRecentlyUpdatedByUsersParser constructor.
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
                        return (new MangaRecentlyUpdatedByUsersListParser($c))->getModel();
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

    public function getModel(): MangaUserUpdates
    {
        return MangaUserUpdates::fromParser($this);
    }
}
