<?php

namespace Jikan\Parser\Top;

use Jikan\Helper\JString;
use Jikan\Model\MalUrl;
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
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getAnimeText(): string
    {
        if ($this->animeText !== null) {
            return $this->animeText;
        }

        return JString::cleanse(
            $this->animeText = $this->crawler
                ->filterXPath('//div[contains(@class, "information")]')
                ->text()
        );
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getMalUrl(): MalUrl
    {
        return (new MalUrlParser($this->crawler->filterXPath('//a[contains(@class,"fs14 fw-b")][1]')))->getModel();
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
    public function getAnimeRating(): float
    {
        return (float)$this->crawler->filterXPath('//td[3]/div/span')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeType(): string
    {
        return preg_replace('/^(\w+).*$/', '$1', explode("\n", $this->getAnimeText())[0]);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): int
    {
        return (int)preg_replace('/.*(\d+) eps.*/', '$1', explode("\n", $this->getAnimeText())[0]);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getAnimeMembers(): int
    {
        return (int)preg_replace('/\D/', '$1', explode("\n", $this->getAnimeText())[2]);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeStartDate(): string
    {
        return JString::cleanse(explode(' - ', explode("\n", $this->getAnimeText())[1])[0]);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeEndDate(): string
    {
        return JString::cleanse(explode(' - ', explode("\n", $this->getAnimeText())[1])[1] ?? '?');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getCharacterKanji(): string
    {
        return trim($this->crawler->filterXPath('//span[@class="fs12 fn-grey6"][1]')->text(), '()');
    }

    /**
     * @return MalUrl[]
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
     * @return MalUrl[]
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
}
