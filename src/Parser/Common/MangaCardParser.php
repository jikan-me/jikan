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
        return $this->crawler->filterXPath('//div/div/h2/a')->attr('href');
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
        $node = $this->crawler->filterXPath('//div/div[2]/div/span[contains(@class, "item")][3]');

        if (!$node->count()) {
            $node = $this->crawler->filterXPath('//div/div[2]/div/span[contains(@class, "item")][2]');
        }

        if (!$node->count()) {
            return null;
        }

        $text = JString::cleanse($node->text());

        if (!preg_match('~([0-9]+)~', $text, $matches)) {
            return null;
        }

        return $matches[1];
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getType(): ?string
    {
        // This information is no longer available
        return null;
        return JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "source")]')->text());
    }

    /**
     * @return array|\Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getGenres(): array
    {
        return $this->crawler->filterXPath('//span[@class="genre"]/a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return array|\Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getExplicitGenres(): array
    {
        return $this->crawler->filterXPath('//span[@class="genre explicit"]/a')
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
        return $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/p')->text();
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getPublishDates(): ?\DateTimeImmutable
    {
        $node = $this->crawler->filterXPath('//div/div[2]/div/span[contains(@class, "item")][1]');

        if (
            !preg_match('~(.*), ([0-9]{1,})~', $node->text(), $matches)
        ) {
            return null;
        }

        $date = $matches[2];

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
        $count = $this->crawler->filterXPath('//div[contains(@class, "information")]/div/div/div[2]')->text();

        $count = JString::cleanse($count);
        $count = str_replace('K', '000', $count);
        $count = str_replace('M', '000000', $count);


        return (int)str_replace([',', '.'], '', $count);
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
        return $this->crawler->filterXPath('//div/div/h2/a')->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaImage(): ?string
    {
        $image = $this->crawler->filterXPath('//div[contains(@class, "image")]/img');

        if (!$image->count()) {
            return null;
        }

        if ($image->attr('src') !== null) {
            return Parser::parseImageQuality(
                $image->attr('src')
            );
        }

        if ($image->attr('data-src') !== null) {
            return Parser::parseImageQuality(
                $image->attr('data-src')
            );
        }

        return null;
    }

    /**
     * @return float|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaScore(): ?float
    {
        $score = JString::cleanse($this->crawler->filterXPath('//div[contains(@class, "information")]/div/div/div[1]')->text());
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
        // if anyone can fix this spaghetti code, most welcome
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]//p[contains(@class, "mb4 mt8")]');

        $malUrl = [];

        $node->each(function (Crawler $c) use (&$malUrl) {
            $node = $c->filterXPath('//span');

            if (str_contains($node->text(), "Serialization")) {
                $node->nextAll()->filterXPath('//a')
                    ->each(function (Crawler $c) use (&$malUrl) {
                        $malUrl[] = $c->text();
                    });
            }
        });

        return $malUrl;
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getThemes(): array
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/div[contains(@class, "properties")]/div[3]/span');

        $malUrl = [];

        $node->each(function (Crawler $c) use (&$malUrl) {
            $node = $c->filterXPath('//span');

            if (str_contains($node->text(), "Theme") || str_contains($node->text(), "Themes")) {
                $node->nextAll()->filterXPath('//a')
                    ->each(function (Crawler $c) use (&$malUrl) {
                        $malUrl[] = (new MalUrlParser($c))->getModel();
                    });
            }
        });

        return $malUrl;
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getDemographics(): array
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/div[contains(@class, "properties")]/div[4]/span');

        $malUrl = [];

        $node->each(function (Crawler $c) use (&$malUrl) {
            $node = $c->filterXPath('//span');

            if (str_contains($node->text(), "Demographic") || str_contains($node->text(), "Demographics")) {
                $node->nextAll()->filterXPath('//a')
                    ->each(function (Crawler $c) use (&$malUrl) {
                        $malUrl[] = (new MalUrlParser($c))->getModel();
                    });
            }
        });

        return $malUrl;
    }
}
