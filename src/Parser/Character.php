<?php

namespace Jikan\Parser;

use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Character
 *
 * @package Jikan\Parser
 */
class Character implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Anime constructor.
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
    public function getModel(): Model\Character
    {
        return Model\Character::fromParser($this);
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        preg_match('#https://myanimelist.net/character/(\d+)#', $this->crawler->getUri(), $ids);

        return (int)$ids[1];
    }

    /**
     * @return string
     */
    public function getCharacterLink(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content');
    }
}
