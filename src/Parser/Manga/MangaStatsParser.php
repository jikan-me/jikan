<?php

namespace Jikan\Parser\Manga;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Manga\MangaStats;
use Jikan\Model\Manga\MangaStatsScore;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaStatsParser
 *
 * @package Jikan\Parser\Common
 */
class MangaStatsParser implements ParserInterface
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
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getReading(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Reading:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @param $input
     *
     * @return int
     */
    private function sanitize($input): int
    {
        return (int)preg_replace('/\D/', '', $input);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getCompleted(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Completed:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getOnHold(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'On-Hold:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getDropped(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Dropped:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getPlanToRead(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Plan to Read:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getTotal(): int
    {
        return $this->sanitize(
            $this->crawler
                ->filterXPath('//div[@class="spaceit_pad"]/span[contains(text(), \'Total:\')]')
                ->parents()
                ->getNode(0)->textContent
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getScores(): array
    {
        $scores = [];
        $table = $this->crawler->filterXPath('//h2[text()="Score Stats"]/following-sibling::table[1]/tr');

        $table->each(function (Crawler $crawler) use (&$scores) {
            $score = (int) $crawler->filterXPath('//td[1]')->text();

            $votes = (int) $this->sanitize(
                $crawler->filterXPath('//td[2]/div/span/small')
                    ->text()
            );

            $percentage = Parser::removeChildNodes(
                $crawler->filterXPath('//td[2]/div/span')
            )->text();
            $percentage = JString::UTF8NbspTrim(
                str_replace('%', '', $percentage)
            );
            $percentage = (float) JString::cleanse($percentage);

            $scores[$score] = MangaStatsScore::setProperties($votes, $percentage);
        });

        for ($i=1; $i<=10; $i++) {
            if (!array_key_exists($i, $scores)) {
                $scores[$i] = MangaStatsScore::setProperties(0, 0);
            }
        }

        ksort($scores);

        return $scores;
    }

    /**
     * @return MangaStats
     * @throws \InvalidArgumentException
     */
    public function getModel(): MangaStats
    {
        return MangaStats::fromParser($this);
    }
}
