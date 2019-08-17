<?php

namespace Jikan\Parser\Anime;

use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Anime
 */
class AnimeRecentlyUpdatedByUsersParser implements ParserInterface
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
    public function getModel(): array
    {

        try {
            return $this->crawler
                ->filterXPath('//table[@class="table-recently-updated"]/tr[1]')
                ->nextAll()
                ->each(function ($c) {
                    return (new AnimeRecentlyUpdatedByUsersListParser($c))->getModel();
                });
        } catch (\Exception $e) {
            return [];
        }
    }
}
