<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime;
use Jikan\Model\DateRange;
use Jikan\Model\MalUrl;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeParser
 *
 * @package Jikan\Parser
 */
class AnimeParser implements ParserInterface
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
     * @return Anime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Anime
    {
        return Anime::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getId(): int
    {
        return Parser::idFromUrl($this->getURL());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content')
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getTitleEnglish(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//span[text()="English:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getTitleSynonyms(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//span[text()="Synonyms:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getTitleJapanese(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//span[text()="Japanese:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getType(): ?string
    {
        $type = $this->crawler
            ->filterXPath('//span[text()="Type:"]');
        if (!$type->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($type->text(), '', $type->parents()->text())
        );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): ?int
    {
        $episodes = $this->crawler
            ->filterXPath('//span[text()="Episodes:"]');

        if (!$episodes->count()) {
            return null;
        }

        return (str_replace($episodes->text(), '', $episodes->parents()->text()) === 'Unknown') ? 0 : (int)str_replace(
            $episodes->text(),
            '',
            $episodes->parents()->text()
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getStatus(): ?string
    {
        $status = $this->crawler
            ->filterXPath('//span[text()="Status:"]');
        if (!$status->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($status->text(), '', $status->parents()->text())
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPremiered(): ?string
    {
        $premiered = $this->crawler
            ->filterXPath('//span[text()="Premiered:"]');

        if (!$premiered->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($premiered->text(), '', $premiered->parents()->text())
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getBroadcast(): ?string
    {
        $broadcast = $this->crawler
            ->filterXPath('//span[text()="Broadcast:"]');

        if (!$broadcast->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($broadcast->text(), '', $broadcast->parents()->text())
        );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getProducers(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Producers:"]/following-sibling::a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getLicensors(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Licensors:"]/following-sibling::a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getStudios(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Studios:"]/following-sibling::a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSource(): ?string
    {
        $source = $this->crawler
            ->filterXPath('//span[text()="Source:"]');

        if (!$source->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($source->text(), '', $source->parents()->text())
        );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getGenres(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Genres:"]/following-sibling::a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDuration(): ?string
    {
        $duration = $this->crawler
            ->filterXPath('//span[text()="Duration:"]');

        if (!$duration->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace(
                '.',
                '',
                str_replace($duration->text(), '', $duration->parents()->text())
            )
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRating(): ?string
    {
        $rating = $this->crawler
            ->filterXPath('//span[text()="Rating:"]');

        if (!$rating->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($rating->text(), '', $rating->parents()->text())
        );
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getScore(): ?float
    {
        return $this->crawler->filterXPath('//span[@itemprop="ratingValue"]')->text();
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getScoredBy(): ?float
    {
        return str_replace(',', '', $this->crawler->filterXPath('//span[@itemprop="ratingCount"]')->text());
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getRank(): ?int
    {
        $rank = $this->crawler
            ->filterXPath('//span[text()="Ranked:"]');

        if (!$rank->count()) {
            return null;
        }

        $ranked = str_replace(
            '#',
            '',
            substr(
                explode(PHP_EOL, trim(str_replace($rank->text(), '', $rank->parents()->text())))[0],
                0,
                -1
            )
        );

        return $ranked !== 'N/A' ? $ranked : null;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getPopularity(): ?int
    {
        $popularity = $this->crawler
            ->filterXPath('//span[text()="Popularity:"]');

        if (!$popularity->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$popularity->text(), '#'], '', $popularity->parents()->text())
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMembers(): ?int
    {
        $member = $this->crawler
            ->filterXPath('//span[text()="Members:"]');

        if (!$member->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$member->text(), ','], '', $member->parents()->text())
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getFavorites(): ?int
    {
        $favorite = $this->crawler
            ->filterXPath('//span[text()="Favorites:"]');

        if (!$favorite->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$favorite->text(), ','], '', $favorite->parents()->text())
        );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRelated(): array
    {
        return $this->crawler
            ->filterXPath('//table[contains(@class, "anime_detail_related_anime")]/tr/td/a')
            ->each(
                function (Crawler $c) {
                    return (new MalUrlParser($c))->getModel();
                }
            );
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getBackground(): ?string
    {
        $background = Parser::removeChildNodes($this->crawler->filterXPath('//span[@itemprop="description"]/..'));
        $background = $background->text();
        if (preg_match('~No background information has been added to this title~', $background)) {
            return null;
        }

        return JString::cleanse($background);
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getOpeningThemes(): array
    {
        return array_filter(
            preg_split(
                '/\s?#\d+:\s/m',
                $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs opnening"]')->text()
            )
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getEndingThemes(): array
    {
        return array_filter(
            preg_split(
                '/\s?#\d+:\s/m',
                $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs ending"]')->text()
            )
        );
    }

    /**
     * @return DateRange
     * @throws \InvalidArgumentException
     */
    public function getAired(): DateRange
    {
        return new DateRange($this->getAnimeAiredString());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeAiredString(): ?string
    {
        $aired = $this->crawler->filterXPath('//span[contains(text(), "Aired")]/..')->text();
        $aired = explode(PHP_EOL, trim($aired))[1];

        return trim($aired);
    }

    /**
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getPreview(): ?string
    {
        $video = $this->crawler->filterXPath('//div[contains(@class, "video-promotion")]/a');
        if (!$video->count()) {
            return null;
        }

        return $video->attr('href');
    }
}
