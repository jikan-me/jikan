<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Model;
use Jikan\Model\AnimeStats;
use Jikan\Model\MangaStats;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UserProfileParser
 *
 * @package Jikan\Parser
 */
class UserProfileParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PersonParser constructor.
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
    public function getModel(): Model\UserProfile
    {
        return Model\UserProfile::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        return (string )preg_replace('#.*/(\w+)$#', '$1', $this->getProfileUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getProfileUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): ?string
    {
        try {
            return $this->crawler->filterXPath('//div[contains(@class, "user-image")]/img')->attr('src');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getJoinDate(): string
    {
        return $this->crawler->filterXPath('//span[contains(text(), \'Joined\')]/following-sibling::span')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLastOnline(): string
    {
        return $this->crawler->filterXPath('//span[contains(text(), \'Last Online\')]/following-sibling::span')->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getGender(): ?string
    {
        $gender = $this->crawler->filterXPath('//span[contains(text(), \'Gender\')]/following-sibling::span');
        if (!$gender->count()) {
            return null;
        }

        return $gender->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getBirthday(): ?string
    {
        $gender = $this->crawler->filterXPath('//span[contains(text(), \'Birthday\')]/following-sibling::span');
        if (!$gender->count()) {
            return null;
        }

        return $gender->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getLocation(): ?string
    {
        $gender = $this->crawler->filterXPath('//span[contains(text(), \'Location\')]/following-sibling::span');
        if (!$gender->count()) {
            return null;
        }

        return $gender->text();
    }

    /**
     * @return AnimeStats
     * @throws \InvalidArgumentException
     */
    public function getAnimeStats(): AnimeStats
    {
        $this->crawler
            ->filterXPath('//div[@class=\'stats anime\']');

        return (new AnimeStatsParser($this->crawler))->getModel();
    }

    /**
     * @return MangaStats
     * @throws \InvalidArgumentException
     */
    public function getMangaStats(): MangaStats
    {
        $this->crawler
            ->filterXPath('//div[@class=\'stats anime\']');

        return (new MangaStatsParser($this->crawler))->getModel();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAbout(): ?string
    {
        $about = $this->crawler->filterXPath('//div[@class=\'profile-about-user js-truncate-inner\']/table/tr/td/div');


        if (!$about->count()) {
            return null;
        }

        return trim(
            $about->html()
        );
    }

    /**
     * @return Model\Favorites
     * @throws \InvalidArgumentException
     */
    public function getFavorites(): Model\Favorites
    {
        // $node = $this->crawler->filterXPath('//ul[@class=\'favorites-list anime\']/li')
        $node = $this->crawler->filterXPath('//div[contains(@class, \'user-favorites\')]');

        return (new FavoritesParser($node))->getModel();
    }
}
