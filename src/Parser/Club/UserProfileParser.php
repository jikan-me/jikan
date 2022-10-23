<?php

namespace Jikan\Parser\Club;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model\Club\UserProfile;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UserProfileParser
 *
 * @package Jikan\Parser\Club
 */
class UserProfileParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * CharacterSearchParser constructor.
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
    public function getModel(): UserProfile
    {
        return UserProfile::fromParser($this);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->crawler->filterXPath('//a[1]')->text();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL . $this->crawler->filterXPath('//a[1]')->attr('href');
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        $imageUrl = Parser::parseImageThumbToHQ(
            $this->crawler->filterXPath('//img[1]')->attr('data-src')
        );

        if (!preg_match("~^".Constants::BASE_URL."~", $imageUrl)) {
            $imageUrl = Constants::BASE_URL.$imageUrl;
        }

        return $imageUrl;
    }
}
