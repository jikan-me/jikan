<?php

namespace Jikan\Parser\User\Friends;

use Jikan\Model\User\Friend;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class FriendsParser
 *
 * @package Jikan\Parser\User\Friends
 */
class FriendsParser implements ParserInterface
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
     * @return Friend[]
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler->filterXPath('//div[contains(@class, "friendBlock")]')->each(
            function (Crawler $c) {
                return (new FriendParser($c))->getModel();
            }
        );
    }
}
