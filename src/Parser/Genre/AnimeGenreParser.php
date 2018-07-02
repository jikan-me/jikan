<?php

namespace Jikan\Parser\Genre;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GenreParser
 *
 * @package Jikan\Parser
 */
class AnimeGenreParser implements ParserInterface
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
     * @return Model\AnimeGenre
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\AnimeGenre
    {
        return Model\AnimeGenre::fromParser($this);
    }

    /**
     * @return array|Model\GenreAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getGenreAnime(): array
    {
        return $this->crawler
            ->filter('div.seasonal-anime')
            ->each(
                function (Crawler $animeCrawler) {
                    return (new AnimeCardParser($animeCrawler))->getModel();
                }
            );
    }

    /**
     * @return Model\MalUrl
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUrl(): Model\MalUrl
    {
        return new Model\MalUrl(
            JString::cleanse(
                Parser::removeChildNodes($this->crawler->filterXPath('//span[@class=\'di-ib mt4\']'))->text()
            ),
            $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content')
        );
    }
}
