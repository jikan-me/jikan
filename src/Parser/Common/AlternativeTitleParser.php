<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Common\AlternativeTitle;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class AlternativeTitleParser implements ParserInterface
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
     * @return \Jikan\Model\Common\AlternativeTitle
     * @throws \Exception
     */
    public function getModel(): AlternativeTitle
    {
        [$language, $title] = explode(': ', $this->crawler->text(), 2);
        return new AlternativeTitle($title, $language);
    }
}
