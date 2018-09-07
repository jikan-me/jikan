<?php

namespace Jikan\Parser\User\Friends;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\User\Friend;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class FriendParser
 *
 * @package Jikan\Parser\Friend
 */
class FriendParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * FriendParser constructor.
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
     */
    public function getModel(): Friend
    {
        return Friend::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAvatar(): string
    {
        return str_replace(
            ['thumbs/', '_thumb'],
            '',
            $this->crawler->filterXPath('//div/a/img')->attr('src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//div[3]/a/strong')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//div[3]/a')->attr('href');
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \InvalidArgumentException
     */
    public function getFriendsSince(): ?\DateTimeImmutable
    {
        $count = 0;
        $text = $this->crawler->filterXPath('//div[last()]')->text();
        $text = preg_replace('/^Friends since (.*)$/', '$1', $text, -1, $count);
        if (!$count) {
            return null;
        }

        return Parser::parseDate($text);
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getLastOnline(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            JString::cleanse($this->crawler->filterXPath('//div[4]')->text()),
            new \DateTimeZone('UTC')
        );
    }
}
