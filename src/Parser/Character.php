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
        // TODO: Implement getModel() method.
    }
}
