<?php

namespace Jikan\Parser\Search;

use Jikan\Model\Search\UserSearch;
use Jikan\Model\Search\UserSearchListItem;
use Jikan\Parser\User\Profile\UserProfileParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UserSearchParser
 *
 * @package Jikan\Parser
 */
class UserSearchParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * UserSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return UserSearch
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): UserSearch
    {
        return UserSearch::fromParser($this);
    }

    /**
     * @return UserSearchListItem[]
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        $node = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td');

        $data = $node
            ->each(function (Crawler $c) {
                return (new UserSearchListItemParser($c))->getModel();
            });

        // If only a single result is found, the $data array will be empty
        // (redirect occurs here to the User Page)
        if (empty($data)) {
            $data = $this->crawler
                ->each(
                    function (Crawler $c) {
                        return UserSearchListItem::fromUserSearchParser(new UserProfileParser($c));
                    }
                );
        }

        return $data;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//div[contains(@class, "normal_header")]/div/div/span/a');

        if (!$pages->count()) {
            return 1;
        }

        return (int)$pages->last()->text();
    }
}
