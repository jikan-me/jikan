<?php

namespace Jikan\Parser\Forum;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Forum\ForumPost;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ForumPostParser
 *
 * @package Jikan\Parser\Forum
 */
class ForumTopicParser
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
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getTopicId(): int
    {
        parse_str(explode('?', $this->getUrl())[1], $query);

        return (int)$query['topicid'];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL.$this->crawler->filterXPath('//a[2]')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//a[2]')->text();
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getPostDate(): \DateTimeImmutable
    {
        return Parser::parseForumDate($this->crawler->filterXPath('//td[2]/span[@class="lightLink"]')->text());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAuthorName(): string
    {
        return $this->crawler->filterXPath('//span[@class="forum_postusername"]/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAuthorUrl(): string
    {
        return Constants::BASE_URL.$this->crawler->filterXPath('//span[@class="forum_postusername"]/a')->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getReplies(): int
    {
        return (int)$this->crawler->filterXPath('//td[3]')->text();
    }

    /**
     * @return ForumPost
     * @throws \InvalidArgumentException
     */
    public function getLastPost(): ForumPost
    {
        $authorName = $this->crawler->filterXPath('//td[4]/a[1]')->text();
        $authorUrl = Constants::BASE_URL.$this->crawler->filterXPath('//td[4]/a[1]')->attr('href');
        $url = Constants::BASE_URL.$this->crawler->filterXPath('//td[4]/a[2]')->attr('href');
        $date = Parser::removeChildNodes($this->crawler->filterXPath('//td[4]'))->text();
        $date = JString::cleanse($date);
        $date = str_replace('by ', '', $date);
        $date = Parser::parseDate($date);

        return new ForumPost($url, $authorName, $authorUrl, $date);
    }
}
