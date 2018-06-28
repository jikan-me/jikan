<?php

namespace Jikan\Parser;

use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

class UserProfile
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Person constructor.
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
    public function getModel(): Model\UserProfile
    {
        return Model\UserProfile::fromParser($this);
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
    public function getImageUrl(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class, "user-image")]/img')->attr('src');
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
     * @return \Jikan\Model\AnimeStats
     * @throws \InvalidArgumentException
     */
    public function getAnimeStats(): \Jikan\Model\AnimeStats
    {
        $this->crawler
            ->filterXPath('//div[@class=\'stats anime\']');
        
        return (new AnimeStats($this->crawler))->getModel();
    }

    /**
     * @return \Jikan\Model\MangaStats
     * @throws \InvalidArgumentException
     */
    public function getMangaStats(): \Jikan\Model\MangaStats
    {
        $this->crawler
            ->filterXPath('//div[@class=\'stats anime\']');
        
        return (new MangaStats($this->crawler))->getModel();
    }
}