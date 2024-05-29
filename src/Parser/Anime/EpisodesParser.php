<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\EpisodeListItem;
use Jikan\Model\Anime\Episodes;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EpisodseParser
 *
 * @package Jikan\Parser
 */
class EpisodesParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * EpisodesParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return EpisodeListItem[]
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): array
    {
        $episodes = $this->crawler
            ->filterXPath('//table[contains(@class, \'js-watch-episode-list\')]/tbody//tr');

        if (!$episodes->count()) {
            return [];
        }

        return $episodes->each(
            function (Crawler $crawler) {
                return (new EpisodeListItemParser($crawler))->getModel();
            }
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[2]/div[2]/div[2]/div//a[contains(@class, "link")]');

        if (!$pages->count()) {
            return 1;
        }

        $pages = $pages
            ->last();

        if (!$pages->count()) {
            return 1;
        }

        preg_match('~\?offset=(\d+)$~', $pages->attr('href'), $page);

        return ((int) $page[1]/100) + 1;
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $isBeyondLastPage = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div/div[2]/table/tbody/tr/td/div[2]');

        if (
            $isBeyondLastPage->count()
            && str_contains($isBeyondLastPage->text(), "No episode information has been added to this title")
        ) {
            return false;
        }

        $pageLinks = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[2]/div[2]/div[2]/div//a[contains(@class, "link")]');

        if (!$pageLinks->count()) {
            return false;
        }

        $isLastPage = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[2]/div[2]/div[2]/div//a[contains(@class, "current") and position() = last()]');

        if ($isLastPage->count()) {
            return false;
        }

        return true;
    }

    /**
     * Return the model
     *
     * @throws \InvalidArgumentException
     */
    public function getModel(): Episodes
    {
        return Episodes::fromParser($this);
    }
}
