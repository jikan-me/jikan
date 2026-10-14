<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\Time;
use Jikan\Helper\Constants;
use Jikan\Helper\ImagePlaceholder;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeCardParser
 *
 * @package Jikan\Parser
 */
class AnimeCardParser implements ParserInterface
{
    /**
     * Every card carries its type as a "js-anime-type-N" class, numbered like MyAnimeList's search filter
     * (Constants::SEARCH_ANIME_*); 0 is unknown. Labels as MyAnimeList prints them on the card.
     */
    private const TYPES = [
        Constants::SEARCH_ANIME_TV => 'TV',
        Constants::SEARCH_ANIME_OVA => 'OVA',
        Constants::SEARCH_ANIME_MOVIE => 'Movie',
        Constants::SEARCH_ANIME_SPECIAL => 'Special',
        Constants::SEARCH_ANIME_ONA => 'ONA',
        Constants::SEARCH_ANIME_MUSIC => 'Music',
        Constants::SEARCH_ANIME_CM => 'CM',
        Constants::SEARCH_ANIME_PV => 'PV',
        Constants::SEARCH_ANIME_TV_SPECIAL => 'TV Special',
    ];

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeCardParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Model\Common\AnimeCard
     */
    public function getModel(): Model\Common\AnimeCard
    {
        return Model\Common\AnimeCard::parseAnimeCard($this);
    }

    /**
     * @return \Jikan\Model\Seasonal\SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSeasonalModel(): Model\Seasonal\SeasonalAnime
    {
        return Model\Seasonal\SeasonalAnime::parseSeasonalAnime($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMalId(): int
    {
        return Parser::idFromUrl($this->getAnimeUrl());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeUrl(): string
    {
        $node = $this->crawler->filterXPath('//div/div/h2/a');

        if ($node->count()) {
            return $node->attr('href');
        }

        return $this->crawler->filterXPath('//div[contains(@class, "title")]/a')->attr('href');
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getProducer(): array
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/div[contains(@class, "properties")]/div[1]/span');

        if (!$node->count()) {
            // this information is no longer available on the producer page
            return [];
        }

        $malUrl = [];

        $node->each(function (Crawler $c) use (&$malUrl) {
            $node = $c->filterXPath('//span');

            if (str_contains($node->text(), "Studio") || str_contains($node->text(), "Studios")) {
                $node->nextAll()->filterXPath('//a')
                    ->each(function (Crawler $c) use (&$malUrl) {
                        $malUrl[] = new MalUrlParser($c)->getModel();
                    });
            }
        });

        return $malUrl;
    }

    /**
     * @return int|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): ?int
    {
        $eps = $this->crawler->filterXPath('//div/div[2]/div[2]/span[contains(@class, "item")][2]/span[1]');

        if (!$eps->count()) {
            $eps = $this->crawler->filterXPath('//div/div[2]/div[2]/span[contains(@class, "item")][3]/span[1]');
        }

        if (!$eps->count()) {
            return null;
        }

        $eps = JString::cleanse($eps->text());
        $eps = str_replace(' eps', '', $eps);

        return $eps === '?' ? null : (int) $eps;
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getSource(): ?string
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/div[contains(@class, "properties")]/div[2]/span[2]');

        if (!$node->count()) {
            return null;
        }

        return $node->text();
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
                    return new MalUrlParser($crawler)->getModel();
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
                    return new MalUrlParser($crawler)->getModel();
                }
            );
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getDescription(): ?string
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "synopsis")]/p');

        if (!$node->count()) {
            // this information is no longer available on the producer page
            return null;
        }

        return $node->text();
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getType(): ?string
    {
        // Every card carries its type as a class. It is also the most precise source: seasonal pages file
        // "TV Special" cards under their "Special" section.
        if (preg_match('~(?:^|\s)js-anime-type-([1-9])(?:\s|$)~', $this->crawler->attr('class') ?? '', $m)) {
            return self::TYPES[$m[1]];
        }

        // genre/producer cards: the info line starts with "<type>, <year>", e.g. "TV, 2013"
        $info = $this->crawler->filterXPath('//div[contains(@class, "info")]/span[contains(@class, "item")][1]');
        if ($info->count() && preg_match('/^\s*([A-Za-z][A-Za-z .\-]*?)\s*,/', $info->text(), $m)) {
            return $m[1];
        }

        // seasonal cards show only the year there; the type is the header of the section the card is in
        for ($section = $this->crawler->getNode(0)?->parentNode; $section instanceof \DOMElement; $section = $section->parentNode) {
            if (!str_contains(' ' . $section->getAttribute('class') . ' ', ' seasonal-anime-list ')) {
                continue;
            }
            foreach ($section->childNodes as $child) {
                if ($child instanceof \DOMElement && str_contains(' ' . $child->getAttribute('class') . ' ', ' anime-header ')) {
                    // "TV (New)", "TV (Continuing)" -> "TV"
                    return trim(preg_replace('/\s*\(.*\)\s*$/', '', JString::cleanse($child->textContent)));
                }
            }

            return null;
        }

        return null;
    }

    /**
     * @return \DateTimeImmutable|null ?\DateTimeImmutable
     * @throws \InvalidArgumentException
     */
    public function getAirDates(): ?\DateTimeImmutable
    {
        // Every card carries the start date as "YYYYMMDD", with 00 for what is not known yet ("20270000",
        // "20271000"; "00000000" when nothing is). Genre and producer cards show no full date otherwise.
        $start = $this->crawler->filter('.js-start_date');
        if ($start->count() && preg_match('~(\d{4})(\d{2})(\d{2})~', $start->text(), $m)) {
            if ($m[1] === '0000') {
                return null;
            }

            return self::japanMidnight($m[1], $m[2] === '00' ? '01' : $m[2], $m[3] === '00' ? '01' : $m[3]);
        }

        $node = $this->crawler->filterXPath('//div/div[2]/div[2]/span[contains(@class, "item")][1]');

        if (!$node->count()) {
            return null;
        }

        $date = JString::cleanse(str_replace('(JST)', '', $node->text()));

        // A bare year ("2027") would otherwise be read as the time 20:27 today.
        if (preg_match('~^\d{4}$~', $date)) {
            return self::japanMidnight($date, '01', '01');
        }

        try {
            return Time::parse($date, new \DateTimeZone('JST'))
                ->setTimezone(new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return null;
        }
    }

    /** Midnight in Japan on the given day, in UTC, as the visible dates on cards are read. */
    private static function japanMidnight(string $year, string $month, string $day): ?\DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', "$year-$month-$day", new \DateTimeZone('JST'));

        return $date === false ? null : $date->setTimezone(new \DateTimeZone('UTC'));
    }

    /**
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMembers(): int
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "information")]/div/div/div[2]');

        if ($node->count()) {
            $count = JString::cleanse($node->text());

            $count = str_replace('K', '000', $count);
            $count = str_replace('M', '000000', $count);

            return (int) str_replace([',', '.'], '', $count);
        }

        // producers page
        $node = $this->crawler->filterXPath('//div[contains(@class, "widget")]/div[@class="users"]');

        if ($node->count()) {
            $count = JString::cleanse($node->text());

            return (int) str_replace([',', '.'], '', $count);
        }

        // Previously fell off the end, which is a TypeError for ": int" and escaped MalClient's
        // catch (\Exception). Same outcome (failure), but now a ParserException like every other parse error.
        throw new \RuntimeException('Could not find the member count on the anime card.');
    }

    /**
     * @return \Jikan\Model\Common\AnimeMeta
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeMeta(): Model\Common\AnimeMeta
    {
        return new Model\Common\AnimeMeta(
            $this->getTitle(),
            $this->getAnimeUrl(),
            $this->getAnimeImage()
        );
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        $node = $this->crawler->filterXPath('//div/div/h2/a');

        if ($node->count()) {
            return $node->text();
        }

        return $this->crawler->filterXPath('//div[contains(@class, "title")]/a')->text();
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeImage(): ?string
    {
        $image = $this->crawler->filterXPath('//div[contains(@class, "image")]/a/img');

        if (!$image->count()) {
            return null;
        }

        if ($image->attr('src') !== null) {
            return ImagePlaceholder::normalize(
                Parser::parseImageQuality(
                    $image->attr('src')
                )
            );
        }

        if ($image->attr('data-src') !== null) {
            return ImagePlaceholder::normalize(
                Parser::parseImageQuality(
                    $image->attr('data-src')
                )
            );
        }

        return null;
    }

    /**
     * @return float|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeScore(): ?float
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, "information")]/div/div/div[1]');
        if ($node->count()) {
            $score = JString::cleanse($node->text());

            if ($score === Constants::DISPLAY_N_A) {
                return null;
            }
        }

        // producers page
        $node = $this->crawler->filterXPath('//div[contains(@class, "widget")]/div[@class="stars"]');
        if ($node->count()) {
            $score = JString::cleanse($node->text());
            if ($score === Constants::DISPLAY_N_A) {
                return null;
            }
        }

        return (float) $score;
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
                        $malUrl[] = new MalUrlParser($c)->getModel();
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
                        $malUrl[] = new MalUrlParser($c)->getModel();
                    });
            }
        });

        return $malUrl;
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isR18(): bool
    {
        $classes = explode(' ', $this->crawler->attr('class'));

        return \in_array('r18', $classes, true);
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isKids(): bool
    {
        $classes = explode(' ', $this->crawler->attr('class'));

        return \in_array('kids', $classes, true);
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isContinuing(): bool
    {
        // The marker sits in the text of the nearest ancestor (the whole season section). Read the raw
        // textContent instead of Crawler::text(): the marker has no whitespace, so whitespace
        // normalisation cannot change the answer, and normalising a section-sized string once per card
        // made the seasonal page parse take over a second.
        $parent = $this->crawler->getNode(0)?->parentNode;
        if ($parent === null) {
            return false;
        }

        // Every card in a section shares one parent, so answer once per parent. DOM wrapper objects
        // are recreated on each access, so key on the (long-lived) document plus the parent's path.
        static $perDocument = null;
        $perDocument ??= new \WeakMap();

        $document = $parent->ownerDocument;
        $path = $parent->getNodePath();
        $known = $perDocument[$document] ?? [];
        if (!isset($known[$path])) {
            $known[$path] = strpos($parent->textContent, '(Continuing)') !== false;
            $perDocument[$document] = $known;
        }

        return $known[$path];
    }
}
