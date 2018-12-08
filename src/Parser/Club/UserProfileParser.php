<?php

namespace Jikan\Parser\Club;

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
        $path = $this->crawler->filterXPath('//a[1]')->attr('href');

        return str_replace('/profile/', '', $path);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->crawler->filterXPath('//img[1]')->attr('src');
    }
}
