<?php

namespace Jikan\Parser\User\Friends;

use Jikan\Model\User\Friend;
use Jikan\Model\User\Friends;
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
     * @return Friends
     */
    public function getModel(): Friends
    {
        return Friends::fromParser($this);
    }

    /**
     * @return Friend[]
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        return $this->crawler->filterXPath('//div[contains(@class, "boxlist-container")]/div[contains(@class, "boxlist")]')->each(
            function (Crawler $c) {
                return (new FriendParser($c))->getModel();
            }
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/div[2]/div[contains(@class, "mt12 mb12")]/div[contains(@class, "pagination")]');

        if (!$pages->count()) {
            return 1;
        }

        $pages = $pages
            ->filterXPath('//a[contains(@class, "link")]')
            ->last();

        if (empty($pages)) {
            return 1;
        }

        preg_match('~\?offset=(\d+)$~', $pages->attr('href'), $page);

        return ((int) $page[1]/100) + 1;
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/div/div[2]/div/div[2]//a[text()="Next"]');

        if (!$pages->count()) {
            return false;
        }

        return true;
    }
}
