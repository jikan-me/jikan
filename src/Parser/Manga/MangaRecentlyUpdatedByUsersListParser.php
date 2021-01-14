<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Common\UserMeta;
use Jikan\Model\Manga\MangaRecentlyUpdatedByUser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaRecentlyUpdatedByUsersListParser
 *
 * @package Jikan\Parser\Common
 */
class MangaRecentlyUpdatedByUsersListParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUsername(): string
    {
        return $this->crawler->filterXPath('//td[1]/div[2]/a')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//td[1]/div[2]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        $imageUrl = $this->crawler->filterXPath('//td[1]/div[1]/a')->attr('style');

        return str_replace(
            ['thumbs/', '_thumb', 'background-image:url(', ')'],
            '',
            $imageUrl
        );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getScore(): ?int
    {
        $score = $this->crawler->filterXPath('//td[2]')->text();

        if ($score === '-') {
            return null;
        }

        return (int) $score;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getStatus(): string
    {
        return $this->crawler->filterXPath('//td[3]')->text();
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getVolumesRead(): ?int
    {
        $node = $this->crawler->filterXPath('//td[4]');
        $nodeText = str_replace(' ', '', trim($node->text()));

        if (empty($nodeText)) {
            return null;
        }

        $episodesSeen = explode('/', $nodeText)[0];

        if ($episodesSeen === '-') {
            return null;
        }

        return (int) $episodesSeen;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getVolumesTotal(): ?int
    {
        $node = $this->crawler->filterXPath('//td[4]');
        $nodeText = str_replace(' ', '', trim($node->text()));

        if (empty($nodeText)) {
            return null;
        }

        $episodesTotal = explode('/', $nodeText)[1];

        if ($episodesTotal === '-') {
            return null;
        }

        return (int) $episodesTotal;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersRead(): ?int
    {
        $node = $this->crawler->filterXPath('//td[5]');
        $nodeText = str_replace(' ', '', trim($node->text()));

        if (empty($nodeText)) {
            return null;
        }

        $episodesSeen = explode('/', $nodeText)[0];

        if ($episodesSeen === '-') {
            return null;
        }

        return (int) $episodesSeen;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersTotal(): ?int
    {
        $node = $this->crawler->filterXPath('//td[5]');
        $nodeText = str_replace(' ', '', trim($node->text()));

        if (empty($nodeText)) {
            return null;
        }

        $episodesTotal = explode('/', $nodeText)[1];

        if ($episodesTotal === '-') {
            return null;
        }

        return (int) $episodesTotal;
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            $this->crawler->filterXPath('//td[6]')->text(),
            new \DateTimeZone('UTC')
        );
    }

    /**
     * @return MangaRecentlyUpdatedByUser
     * @throws \Exception
     */
    public function getModel(): MangaRecentlyUpdatedByUser
    {
        return MangaRecentlyUpdatedByUser::fromParser($this);
    }

    public function getUserMeta() : UserMeta
    {
        return new UserMeta(
            $this->getUsername(),
            $this->getUrl(),
            $this->getImageUrl()
        );
    }
}
