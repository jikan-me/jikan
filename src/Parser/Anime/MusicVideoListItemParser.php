<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\MusicVideoListItem;
use Jikan\Model\Common\MusicMeta;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MusicVideoListItemParser
 *
 * @package Jikan\Parser\Anime
 */
class MusicVideoListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * MusicVideoListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }


    /**
     * @return MusicVideoListItem
     */
    public function getModel(): MusicVideoListItem
    {
        return MusicVideoListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//a/div/span')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getVideoUrl(): string
    {
        return $this->crawler->filterXPath('//a')->attr('href');
    }

    /**
     * @return MusicMeta
     */
    public function getMusic(): MusicMeta
    {
        $node = $this->crawler->filterXPath('//div/div');

        if (!$node->count()) {
            return new MusicMeta(null, null);
        }

        preg_match('~(.*) by (.*)~', $node->text(), $matches);

        return new MusicMeta($matches[1] ?? null, $matches[2] ?? null);
    }
}
