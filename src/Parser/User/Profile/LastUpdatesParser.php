<?php

namespace Jikan\Parser\User\Profile;

use Jikan\Helper\JString;
use Jikan\Model\User\BaseLastUpdate;
use Jikan\Model\User\LastAnimeUpdate;
use Jikan\Model\User\LastMangaUpdate;
use Jikan\Model\User\LastUpdates;
use Symfony\Component\DomCrawler\Crawler;
use Jikan\Helper\Parser;

class LastUpdatesParser
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * LastUpdates constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return LastUpdates
     */
    public function getModel(): LastUpdates
    {
        return LastUpdates::fromParser($this);
    }

    /**
     * @return array
     *
     */
    public function getLastAnimeUpdates(): array
    {
        $arr = $this->parseBaseListUpdates('anime');
        $results = [];
        /**
         * @var $baseLastUpdate BaseLastUpdate
         */
        foreach ($arr as $baseLastUpdate) {
            $results[] = new LastAnimeUpdate($baseLastUpdate);
        }
        return $results;
    }

    /**
     * @return array
     *
     */
    public function getLastMangaUpdates(): array
    {
        $arr = $this->parseBaseListUpdates('manga');
        $results = [];
        /**
         * @var $baseLastUpdate BaseLastUpdate
         */
        foreach ($arr as $baseLastUpdate) {
            $results[] = new LastMangaUpdate($baseLastUpdate);
        }
        return $results;
    }

    private function parseBaseListUpdates(string $updateType): array
    {
        return $this->crawler->filterXPath("//div[contains(@class, 'updates {$updateType}')]/div")
            ->each(function (Crawler $crawler) {
                $a = $crawler->filterXPath('//a')->first();
                $img = $a->filterXPath('//img');
                $title = $img->attr('alt');
                $url = $a->attr('href');
                $imageUrl = $img->attr('data-src');
                $date = Parser::parseDate($crawler->filterXPath('//div/div[1]/span')->text());
                $progressScoreDiv = $crawler->filterXPath('//div/div[2]');
                $text = $progressScoreDiv->text();
                $scoreUnparsed = trim(substr($text, strpos($text, 'Scored') + strlen('Scored')));
                $score = ctype_digit($scoreUnparsed) ? intval($scoreUnparsed) : 0;
                $status = JString::cleanse(explode('·', $text)[0]);
                $progressed = null;
                $total = null;

                // "Completed 148/148", "Watching 623/?" (total not known yet), "On-Hold -/355" (none read yet),
                // or just the status: "Plan to Watch".
                if (preg_match('~^(.*?)\s*(\d+|-)\s*/\s*(\d+|\?)$~', $status, $progress)) {
                    $status = $progress[1];
                    $progressed = $progress[2] === '-' ? 0 : (int) $progress[2];
                    $total = $progress[3] === '?' ? null : (int) $progress[3];
                }

                return new BaseLastUpdate($url, $title, $imageUrl, $progressed, $total, $status, $score, $date);
            });
    }
}
