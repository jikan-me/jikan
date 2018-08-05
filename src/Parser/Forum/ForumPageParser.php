<?php

namespace Jikan\Parser\Forum;

use Jikan\Model\Forum\ForumTopic;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ForumPageParser
 *
 * @package Jikan\Parser\Forum
 */
class ForumPageParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ForumPostParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return ForumTopic[]
     * @throws \InvalidArgumentException
     */
    public function getTopics(): array
    {
        return $this->crawler
            ->filterXPath('//tr[contains(@id, "topicRow")]')
            ->each(
                function (Crawler $crawler) {
                    return ForumTopic::fromParser(new ForumTopicParser($crawler));
                }
            );
    }
}
