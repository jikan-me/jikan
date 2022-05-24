<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

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
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getModel(): Model\Person\PublishedManga
    {
        return Model\Person\PublishedManga::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPosition(): string
    {
        $role = $this->crawler
            ->filterXPath('//td[2]/div[2]/small');

        return JString::cleanse(
            $role->text()
        );
    }

    /**
     * @return \Jikan\Model\Common\MangaMeta
     * @throws \InvalidArgumentException
     */
    public function getMangaMeta(): Model\Common\MangaMeta
    {
        return new Model\Common\MangaMeta(
            $this->crawler->filterXPath('//td[position() = 2]/div/a')->text(),
            $this->crawler->filterXPath('//td[position() = 2]/div/a')->attr('href'),
            $this->crawler->filterXPath('//td[position() = 1]/div/a/img')->attr('data-src')
        );
    }
}
