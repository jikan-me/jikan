<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\Anime\AnimeRecentlyUpdatedByUser;
use Jikan\Model\Common\UserMeta;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Common
 */
class AnimeRecentlyUpdatedByUsersListParser implements ParserInterface
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
    public function getEpisodesSeen(): ?int
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
    public function getEpisodesTotal(): ?int
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
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            $this->crawler->filterXPath('//td[5]')->text(),
            new \DateTimeZone('UTC')
        );
    }

    /**
     * @return AnimeRecentlyUpdatedByUser
     * @throws \Exception
     */
    public function getModel(): AnimeRecentlyUpdatedByUser
    {
        return AnimeRecentlyUpdatedByUser::fromParser($this);
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
