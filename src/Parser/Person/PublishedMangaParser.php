<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;
use Jikan\Parser\Common\MangaMetaParser;


/**
 * Class PublishedMangaParser
 *
 * @package Jikan\Parser
 */
class PublishedMangaParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PublishedMangaParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     */
    public function getModel(): Model\PublishedManga
    {
        return Model\PublishedManga::fromParser($this);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getPosition(): string
    {
        return JString::cleanse(
            $this->crawler
                ->filterXPath('//small')
                ->text()
        );
    }

    /**
     * @return Model\MangaMeta[]
     * @throws \InvalidArgumentException
     */
    public function getMangaMeta(): Model\MangaMeta
    {
        return new Model\MangaMeta(
            $this->crawler->filterXPath('//td[position() = 2]/a')->text(),
            $this->crawler->filterXPath('//td[position() = 2]/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 1]/div/a/img')->attr('data-src')
        );
    }

}
