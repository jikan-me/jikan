<?php

namespace Jikan\Parser\User\Friends;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\UserMeta;
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
     * @return Friend
     * @throws \Exception
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
            $this->crawler->filterXPath('//div/a/img')->attr('data-src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//div[3]/div/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//div[3]/div/a')->attr('href');
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \InvalidArgumentException
     */
    public function getFriendsSince(): ?\DateTimeImmutable
    {
        $count = 0;
        $node = $this->crawler->filterXPath('//div[contains(@class, "data")]/div[3]');

        if (!$node->count()) {
            return null;
        }

        $text = JString::cleanse($node->text());
        $text = preg_replace('/^Friends since (.*)$/', '$1', $text, -1, $count);

        if (!$count) {
            return null;
        }

        return Parser::parseDate($text);
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getLastOnline(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            JString::cleanse($this->crawler->filterXPath('//div[contains(@class, "data")]/div[2]')->text()),
            new \DateTimeZone('UTC')
        );
    }

    public function getUserMeta() : UserMeta
    {
        return new UserMeta(
            $this->getName(),
            $this->getUrl(),
            $this->getAvatar()
        );
    }
}
