<?php

namespace Jikan\Parser;

use Jikan\Helper\JString;
use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

class SeasonalAnime implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SeasonalAnime constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Model\SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\SeasonalAnime
    {
        return Model\SeasonalAnime::fromParser($this);
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getStudio(): ?string
    {
        $node = $this->crawler->filterXPath('//span[contains(@class, "producer")]/a');
        if (!$node->count()) {
            return null;
        }

        return $node->text();
    }

    /**
     * @return int|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): ?int
    {

        $eps = $this->crawler->filterXPath('//div[contains(@class, "eps")]')->text();
        $eps = JString::cleanse($eps);
        str_replace(' eps', '', $eps);

        return $eps === '?' ? null : (int)$eps;
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSource(): string
    {
        return $this->crawler->filterXPath('//span[contains(@class, "source")]')->text();
    }

    /**
     * @return array
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
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//p[contains(@class,"title-text")]/a')->text();
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
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getType(): string
    {
        $text = $this->crawler->filterXPath('//div[contains(@class, "info")]')->text();
        $text = JString::cleanse($text);
        preg_match('/^([\w\.]+)/', $text, $matches);

        return $matches[1];
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAirDates(): string
    {
        return JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "remain-time")]')->text());
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
     * @return string
     * @throws \RuntimeException
     */
    public function getAnimeUrl(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class, "title")]/p/a')->extract(['href'])[0];
    }

    /**
     * @return int
     * @throws \RuntimeException
     */
    public function getAnimeId(): int
    {
        preg_match('#https?://myanimelist.net/anime/(\d+)#', $this->getAnimeUrl(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getAnimeImage(): string
    {
        return $this->crawler->filterXPath('//div[contains(@class, "image")]/img')->extract(['src'])[0];
    }

    /**
     * @return float|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeScore(): ?float
    {
        $score = JString::cleanse($this->crawler->filterXPath('//span[contains(@class, "score")]')->text());
        if ($score === 'N/A') {
            return null;
        }

        return (float)$score;
    }

    /**
     * @return null|string
     * @throws \RuntimeException
     */
    public function getLicensors(): ?string
    {
        $licensors = $this->crawler->filterXPath('//p[contains(@class, "licensors")]');
        if (!$licensors->count()) {
            return null;
        }

        return trim(JString::cleanse($licensors->extract(['data-licensors'])[0]), ',');
    }

    /**
     * @return bool
     */
    public function isR18(): bool
    {
        $classes = explode(' ', $this->crawler->extract(['class'])[0]);

        return \in_array('r18', $classes, true);
    }

    /**
     * @return bool
     */
    public function isKids(): bool
    {
        $classes = explode(' ', $this->crawler->extract(['class'])[0]);

        return \in_array('kids', $classes, true);
    }

    /**
     * @return bool
     */
    public function isContinuing(): bool
    {
        return strpos(
                $this->crawler->parents()->text(),
                '(Continuing)'
            ) !== false;
    }
}