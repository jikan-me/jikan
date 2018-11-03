<?php

namespace Jikan\Parser\Manga;

use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Manag
 */
class MangaRecentlyUpdatedByUsersParser implements ParserInterface
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
    public function getModel(): array
    {

        return $this->crawler
            ->filterXPath('//table[@class="table-recently-updated"]/tr[1]')
            ->nextAll()
            ->each(function ($c) {
                return (new MangaRecentlyUpdatedByUsersListParser($c))->getModel();
            });
    }
}
