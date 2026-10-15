<?php

namespace Jikan\Parser\Manga;

use Jikan\Helper\Parser;
use Jikan\Helper\Time;
use Jikan\Helper\ImagePlaceholder;
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
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): ?string
    {
        $imageUrl = $this->crawler->filterXPath('//td[1]/div[1]/a')->attr('style');

        return ImagePlaceholder::normalize(
            str_replace(
                ['thumbs/', '_thumb', 'background-image:url(', ')'],
                '',
                $imageUrl
            )
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
        return Parser::parseProgress($this->crawler->filterXPath('//td[4]')->text())[0];
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getVolumesTotal(): ?int
    {
        return Parser::parseProgress($this->crawler->filterXPath('//td[4]')->text())[1];
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersRead(): ?int
    {
        return Parser::parseProgress($this->crawler->filterXPath('//td[5]')->text())[0];
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getChaptersTotal(): ?int
    {
        return Parser::parseProgress($this->crawler->filterXPath('//td[5]')->text())[1];
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        return Time::parse(
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

    public function getUserMeta(): UserMeta
    {
        return new UserMeta(
            $this->getUsername(),
            $this->getUrl(),
            $this->getImageUrl()
        );
    }
}
