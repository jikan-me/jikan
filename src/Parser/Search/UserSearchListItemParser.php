<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Search\UserSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UserSearchListItemParser
 *
 * @package Jikan\Parser
 */
class UserSearchListItemParser
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
     * @return UserSearchListItem
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): UserSearchListItem
    {
        return UserSearchListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        return $this->crawler->filterXPath('//div[1]/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL . $this->crawler->filterXPath('//div[1]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageThumbToHQ(
            $this->crawler->filterXPath('//div[2]/a/img')
                ->attr('data-src')
        );
    }

    /**
     * @return ?\DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getLastOnline(): ?\DateTimeImmutable
    {
        $lastOnline = JString::UTF8NbspTrim(
            $this->crawler->filterXPath('//div[3]/small')->text()
        );

        try {
            return new \DateTimeImmutable(
                $lastOnline,
                new \DateTimeZone('UTC')
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
