<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Model\Common\Title;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AlternativeTitleParser parses an alternative of an anime/manga.
 *
 * @package Jikan\Parser\Common
 */
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
     * @return \Jikan\Model\Common\Title[]
     * @throws \Exception
     */
    public function getModel(): array
    {
        [$type, $title] = explode(': ', $this->crawler->text(), 2);
        if ($type !== 'Synonyms') {
            return [new Title($type, $title)];
        }

        $titles = explode(', ', $title);

        foreach ($titles as &$title) {
            $title = new Title(Title::TYPE_SYNONYM, JString::cleanse($title));
        }

        return $titles;
    }
}
