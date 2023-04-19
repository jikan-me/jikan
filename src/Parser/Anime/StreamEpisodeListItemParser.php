<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Parser;
use Jikan\Model\Anime\StreamEpisodeListItem;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class StreamEpisodeListItemParser
 *
 * @package Jikan\Parser\Episode
 */
class StreamEpisodeListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * EpisodeListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return int|null
     */
    public function getMalId() : ?int
    {
        preg_match('~([\d]+)$~', $this->getUrl(), $matches);

        if (!empty($matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return StreamEpisodeListItem
     * @throws \InvalidArgumentException
     */
    public function getModel(): StreamEpisodeListItem
    {
        return StreamEpisodeListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//a/div/span/span[@class="episode-title"]')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getEpisode(): string
    {
        return Parser::removeChildNodes($this->crawler->filterXPath('//a/div/span[contains(@class,"title")]'))->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): ?string
    {
        $imageUrl = $this->crawler->filterXPath('//a/img')->attr('data-src');

        if ($imageUrl === 'https://cdn.myanimelist.net/images/icon-banned-youtube.png') {
            return null;
        }

        return $imageUrl;
    }
}
