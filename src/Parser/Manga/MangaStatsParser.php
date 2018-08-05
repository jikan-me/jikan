<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\MangaStats;
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
        $table = $this->crawler->filterXPath('//h2[text()="Score Stats"]/following-sibling::table');
        $voteCounts = $table->filterXPath('//small[contains(text(), \'votes\')]');

        $scores = [];
        $score = 10;

        $voteCounts->each(
            function (Crawler $crawler) use (&$scores, &$score) {
                $scores[$score] = [
                    'votes'      => $this->sanitize($crawler->text()),
                    'percentage' => (double)preg_replace(
                        '/[^0-9,.]/',
                        '',
                        substr(
                            $completeText = $crawler->parents()->getNode(0)->textContent,
                            0,
                            strpos($completeText, '%')
                        )
                    ),
                ];

                $score--;
            }
        );

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
