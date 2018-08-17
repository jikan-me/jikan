<?php

namespace Jikan\Parser\Schedule;

use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SeasonalParser
 *
 * @package Jikan\Parser
 */
class ScheduleParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SeasonalParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Schedule\Schedule
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Schedule\Schedule
    {
        return Model\Schedule\Schedule::fromParser($this);
    }

    /**
     * @param string $day
     *
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getShedule(string $day = 'all'): array
    {
        $parts = ['/'];
        if ($day !== 'all') {
            $parts[] = sprintf('div[contains(@class, "js-seasonal-anime-list-key-%s")]', $day);
        }
        $parts[] = 'div[contains(@class, "seasonal-anime")]';
        $query = implode('/', $parts);

        return $this->crawler->filterXPath($query)->each(
            function (Crawler $c) {
                return (new AnimeCardParser($c))->getModel();
            }
        );
    }
}
