<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaCardParser
 *
 * @package Jikan\Parser
 */
class MangaCardParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaCardParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Common\MangaCard
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Common\MangaCard
    {
        return Model\Common\MangaCard::parseMangaCard($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getMangaUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaUrl(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class, "title")]/p/a')->attr('href');
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAuthor(): array
    {
        return $this->crawler
            ->filterXPath('//span[contains(@class, "producer")]/a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return int|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getVolumes(): ?int
    {

        $vols = $this->crawler->filterXPath('//div[contains(@class, "eps")]')->text();
        $vols = JString::cleanse($vols);
        $vols = str_replace(' eps', '', $vols);

        return $vols === '?' ? null : (int)$vols;
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getType(): string
    {
        return JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "source")]')->text());
    }

    /**
     * @return array|\Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getGenres(): array
    {
        return $this->crawler->filterXPath('//span[contains(@class, "genre")]/a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getDescription(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/span')->text();
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getPublishDates(): ?\DateTimeImmutable
    {
        $date = str_replace(
            '(JST)',
            '',
            JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "remain-time")]')->text())
        );

        try {
            return (new \DateTimeImmutable($date, new \DateTimeZone('JST')))
                ->setTimezone(new \DateTimeZone('UTC'))
                ->setTime(0, 0);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMembers(): int
    {
        $count = $this->crawler->filterXPath('//div[contains(@class, "scormem")]/span')->text();
        $count = JString::cleanse($count);

        return (int)str_replace(',', '', $count);
    }

    /**
     * @return \Jikan\Model\Common\MangaMeta
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaMeta(): Model\Common\MangaMeta
    {
        return new Model\Common\MangaMeta(
            $this->getTitle(),
            $this->getMangaUrl(),
            $this->getMangaImage()
        );
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//p[contains(@class,"title-text")]/a')->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaImage(): ?string
    {
        //bypass lazyloading
        $image = $this->crawler->filterXPath('//div[contains(@class, "image")]/img')->first()->attr('src');

        if (null !== $image) {
            return Parser::parseImageQuality($image);
        }

        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//div[contains(@class, "image")]/img')->first()->attr('data-src')
        );
    }

    /**
     * @return float|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaScore(): ?float
    {
        $score = JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "score")]')->text());
        if ($score === 'N/A') {
            return null;
        }

        return (float)$score;
    }

    /**
     * @return null|string[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getSerialization(): ?array
    {
        $serialization = $this->crawler->filterXPath('//p[contains(@class, "serialization")]/a');

        if (!$serialization->count()) {
            return [];
        }

        return $serialization->each(
            function (Crawler $c) {
                return $c->text();
            }
        );
    }
}
