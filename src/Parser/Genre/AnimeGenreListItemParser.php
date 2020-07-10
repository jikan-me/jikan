<?php

namespace Jikan\Parser\Genre;

use Jikan\Helper\Constants;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GenreParser
 *
 * @package Jikan\Parser
 */
class AnimeGenreListItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * GenreParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Genre\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Genre\AnimeGenreListItem
    {
        return Model\Genre\AnimeGenreListItem::fromParser($this);
    }

    /**
     * @return int|null
     */
    public function getMalId() : ?int
    {
        preg_match('~(\d+)/.*$~', $this->getUrl(), $matches);

        if (!empty($matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL . $this->crawler->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getName(): string
    {
        preg_match('~(.+)\s\(.+\)~', $this->crawler->text(), $node);
        $count = str_replace(',', '', $node[1]);

        return $count;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getCount(): int
    {
        preg_match('~.+\s\((.+)\)~', $this->crawler->text(), $node);
        $count = str_replace(',', '', $node[1]);

        return $count;
    }
}
