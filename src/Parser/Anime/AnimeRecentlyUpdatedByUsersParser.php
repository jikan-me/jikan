<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeRecentlyUpdatedByUser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Common
 */
class AnimeRecentlyUpdatedByUsersParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
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
                return (new AnimeRecentlyUpdatedByUsersListParser($c))->getModel();
            });
    }
}
