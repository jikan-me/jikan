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
}