<?php

namespace Jikan\Parser\Top;

use Jikan\Exception\ParserException;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Common\MalUrlParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TopListItemParser
 *
 * @package Jikan\Parser\Top
 */
class TopListItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var string
     */
    private $animeText;

    /**
     * CharacterListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     * @throws \InvalidArgumentException
     */
    public function getMalUrl(): MalUrl
    {
        // For Anime
        $node = $this->crawler->filterXPath('//td[contains(@class, "title")]/div/div/h3/a');

        if ($node->count()) {
            return (new MalUrlParser($node))->getModel();
        }

        // For Manga
        $node = $this->crawler->filterXPath('//td[contains(@class, "title")]/div/h3/a');

        if ($node->count()) {
            return (new MalUrlParser($node))->getModel();
        }

        // For Characters/People
        $node = $this->crawler->filterXPath('//td[contains(@class, "people")]/div/a');

        if ($node->count()) {
            return (new MalUrlParser($node))->getModel();
        }

        throw new ParserException('Failed to parse MalUrl');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImage(): ?string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//img[1]')->attr('data-src')
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getRank(): int
    {
        return (int)$this->crawler->filterXPath('//td[1]/span')->text();
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getScore(): float
    {
        return (float) $this->crawler->filterXPath('//td[3]/div/span')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getType(): string
    {
        return preg_replace('~(.*)\s\(.*~', '$1', $this->getTextArray()[0]);
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getTextArray(): array
    {
        $parts = explode("\n", $this->getText());
        $parts = str_replace(["\n", "<br>"], '', $parts);
        $parts = array_map('trim', $parts);
        $parts = array_filter($parts);

        return array_values($parts);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getText(): string
    {
        if ($this->animeText !== null) {
            return $this->animeText;
        }

        return JString::cleanse(
            $this->animeText = $this->crawler
                ->filterXPath('//div[contains(@class, "information")]')
                ->html()
        );
    }

    /**
     * @return int|null ?int
     */
    public function getEpisodes(): ?int
    {
        $episodes = (int) preg_replace('/.*\((\d+) eps\).*/', '$1', $this->getTextArray()[0]);
        return $episodes === 0 ? null : $episodes;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getVolumes(): ?int
    {
        $count = 0;
        $vols = preg_replace('/.*\((\d+) vols\).*/', '$1', $this->getTextArray()[0], -1, $count);

        return $count ? (int)$vols : null;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMembers(): int
    {
        return (int)preg_replace('/\D/', '', $this->getTextArray()[2]);
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getStartDate(): ?string
    {
        $date = JString::cleanse(explode('-', $this->getTextArray()[1])[0]);

        return empty($date) ? null : $date;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getEndDate(): ?string
    {
        $date = JString::cleanse(explode('-', $this->getTextArray()[1])[1] ?? '?');

        return empty($date) ? null : $date;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getKanjiName(): ?string
    {
        try {
            return trim($this->crawler->filterXPath('//span[@class="fs12 fn-grey6"][1]')->text(), '()');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \InvalidArgumentException
     */
    public function getAnimeography(): array
    {
        return $this->crawler->filterXPath('//td[3]/div/a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \InvalidArgumentException
     */
    public function getMangaography(): array
    {
        return $this->crawler->filterXPath('//td[4]/div/a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getFavorites(): int
    {
        return (int)preg_replace('/\D/', '', $this->crawler->filterXPath('//td[5]')->text());
    }


    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getPeopleFavorites(): int
    {
        return (int)preg_replace('/\D/', '', $this->crawler->filterXPath('//td[4]')->text());
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \InvalidArgumentException
     */
    public function getBirthday(): ?\DateTimeImmutable
    {
        return Parser::parseDate($this->crawler->filterXPath('//td[3]')->text());
    }
}
