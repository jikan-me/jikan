<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
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
     * @return \Jikan\Model\Anime
     */
    public function getModel(): \Jikan\Model\Anime
    {
        return \Jikan\Model\Anime::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeId(): int
    {
        preg_match('#https?://myanimelist.net/anime/(\d+)#', $this->getAnimeURL(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content')
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeTitleEnglish(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeTitleSynonyms(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeTitleJapanese(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeType(): ?string
    {
        $type = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeEpisodes(): ?int
    {
        $episodes = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeStatus(): ?string
    {
        $status = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeAiredString(): ?string
    {
        $aired = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Aired:"]');

        if (!$aired->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($aired->text(), '', $aired->parents()->text())
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimePremiered(): ?string
    {
        $premiered = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeBroadcast(): ?string
    {
        $broadcast = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Broadcast:"]');

        if (!$broadcast->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($broadcast->text(), '', $broadcast->parents()->text())
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeProducer(): array
    {
        $producer = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Producers:"]');

        if ($producer->count() && strpos($producer->parents()->text(), 'None found') === false) {
            return $producer->parents()->first()->filter('a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `None found`
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeLicensor(): array
    {
        $licensor = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Licensors:"]');

        if ($licensor->count() && strpos($licensor->parents()->text(), 'None found') === false) {
            return $licensor->parents()->first()->filter('a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `None found`
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeStudio(): array
    {
        $studio = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Studios:"]');

        if ($studio->count() && strpos($studio->parents()->text(), 'None found') === false) {
            return $studio->parents()->first()->filter('a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `None found`
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeSource(): ?string
    {
        $source = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Source:"]');

        if (!$source->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($source->text(), '', $source->parents()->text())
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getAnimeGenre(): array
    {
        $genre = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Genres:"]');

        if ($genre->count() && strpos($genre->parents()->text(), 'No genres have been added yet') === false) {
            return $genre->parents()->first()->filter('a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `No genres have been added yet`
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeDuration(): ?string
    {
        $duration = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeRating(): ?string
    {
        $rating = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Rating:"]');

        if (!$rating->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($rating->text(), '', $rating->parents()->text())
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAnimeScore(): ?string
    {
        $score = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Score:"]');

        if (!$score->count()) {
            return null;
        }

        return explode(PHP_EOL, trim(str_replace($score->text(), '', $score->parents()->text())))[0];
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getAnimeRank(): ?int
    {
        $rank = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimePopularity(): ?int
    {
        $popularity = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeMembers(): ?int
    {
        $member = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getAnimeFavorites(): ?int
    {
        $favorite = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Favorites:"]');

        if (!$favorite->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$favorite->text(), ','], '', $favorite->parents()->text())
        );
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @todo use a sub parser for this
     */
    public function getAnimeRelated(): array
    {
        $related = [];
        $relatedNode = $this->crawler
            ->filter('table.anime_detail_related_anime')
            ->filter('tr')->each(
                function ($tr) {
                    return $tr->each(
                        function ($td) {
                            $related = [];
                            $relationType = substr($td->filter('td')->first()->text(), 0, -1);
                            $relationNodes = $td->filter('td')->last();

                            $related[$relationType] = $relationNodes->filter('a')->each(
                                function ($node) {
                                    $url = BASE_URL.substr($node->attr('href'), 1);
                                    preg_match('~https://myanimelist.net/(.*)/(.*)/(.*)~', $url, $matches);

                                    return [
                                        'mal_id' => (int)$matches[2],
                                        'type'   => $matches[1],
                                        'url'    => $url,
                                        'title'  => $node->text(),
                                    ];
                                }
                            );

                            return $related;
                        }
                    )[0];
                }
            );

        foreach ($relatedNode as $node) {
            $related = array_merge($related, $node);
        }

        return $related;
    }

    /**
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getAnimeBackground(): ?string
    {
        $background = $this->crawler
            ->filter('span[itemprop="description"]')->parents()->html();
        preg_match('~Background</h2>(.*?)<div~s', $background, $matches);

        if (empty($matches)) {
            return null;
        }

        if (preg_match('~No background information has been added to this title~', $matches[1])) {
            return null;
        }

        return JString::cleanse($matches[1]);
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getAnimeOpeningTheme(): array
    {
        $opening = [];
        $op = $this->crawler
            ->filter('div[class="theme-songs js-theme-songs opnening"] span');
        foreach ($op as $node) {
            $opening[] = $node->nodeValue;
        }

        return $opening;
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getAnimeEndingTheme(): array
    {
        $ending = [];
        $ed = $this->crawler
            ->filter('div[class="theme-songs js-theme-songs ending"] span');
        foreach ($ed as $node) {
            $ending[] = $node->nodeValue;
        }

        return $ending;
    }
}
