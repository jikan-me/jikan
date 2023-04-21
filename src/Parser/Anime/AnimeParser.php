<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\Anime;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use Jikan\Parser\Common\AlternativeTitleParser;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\UrlParser;
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
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getSynopsis(): ?string
    {
        $synopsis = JString::cleanse(
            $this->crawler->filterXPath('//p[@itemprop=\'description\']')->html()
        );

        return str_starts_with($synopsis, 'No synopsis information has been added to this title.') ? null : $synopsis;
    }

    /**
     * @return bool
     */
    public function getApproved(): bool
    {
        $node = $this->crawler->filterXPath('//*[@id="addtolist"]/span[contains(text(), "pending approval")]');

        if ($node->count()) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getTitleEnglish(): ?string
    {
        $title = $this->crawler->filterXPath('//span[text()="English:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->ancestors()->text())
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getTitleSynonyms(): array
    {
        $title = $this->crawler
            ->filterXPath('//span[text()="Synonyms:"]');

        if (!$title->count()) {
            return [];
        }

        $titles = str_replace($title->text(), '', $title->ancestors()->text());
        $titles = explode(', ', $titles);

        foreach ($titles as &$title) {
            $title = JString::cleanse($title);
        }

        return $titles;
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
            str_replace($title->text(), '', $title->ancestors()->text())
        );
    }

    /**
     * @return \Jikan\Model\Common\Title[]
     * @throws \Exception
     */
    public function getTitles(): array
    {
        $crawler = $this->crawler
            ->filterXPath(
                '//h2[text()="Alternative Titles"]/following-sibling::div[following::h2[text()="Information"]]'
            );

        $titles = [new Title(Title::TYPE_DEFAULT, $this->getTitle())];

        if ($crawler->count() === 0) {
            return $titles;
        }

        return array_merge(
            $titles,
            ...$crawler->filterXPath('//div[contains(@class, "spaceit_pad")]')->each(function ($item) {
                return (new AlternativeTitleParser($item))->getModel();
            })
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

        $type = JString::cleanse(str_replace($type->text(), '', $type->ancestors()->text()));
        return $type === 'Unknown' ? null : $type;
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

        return
            (
                trim(
                    str_replace($episodes->text(), '', $episodes->ancestors()->text())
                ) === 'Unknown'
            )
                ?
                null
                :
                (int)str_replace(
                    $episodes->text(),
                    '',
                    $episodes->ancestors()->text()
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
            str_replace($status->text(), '', $status->ancestors()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getPremiered(): ?string
    {
        $premiered = $this->crawler
            ->filterXPath('//span[text()="Premiered:"]');

        if (!$premiered->count()) {
            return null;
        }

        $premiered = JString::cleanse(
            str_replace($premiered->text(), '', $premiered->ancestors()->text())
        );

        if ($premiered === '?') {
            return null;
        }

        return $premiered;
    }

    /**
     * @return string|null
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
            str_replace($broadcast->text(), '', $broadcast->ancestors()->text())
        );
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getProducers(): array
    {
        $producer = $this->crawler
            ->filterXPath('//span[text()="Producers:"]');

        if ($producer->count() && strpos($producer->ancestors()->text(), 'None found') === false) {
            return $producer->ancestors()->first()->filterXPath('//a')->each(
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
    public function getLicensors(): array
    {
        $licensor = $this->crawler
            ->filterXPath('//span[text()="Licensors:"]');

        if ($licensor->count() && strpos($licensor->ancestors()->text(), 'None found') === false) {
            return $licensor->ancestors()->first()->filterXPath('//a')->each(
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
    public function getStudios(): array
    {
        $studio = $this->crawler->filterXPath('//span[text()="Studios:"]');

        if ($studio->count() && strpos($studio->ancestors()->text(), 'None found') === false) {
            return $studio->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `None found`
    }

    /**
     * @return string|null
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
            str_replace($source->text(), '', $source->ancestors()->text())
        );
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getGenres(): array
    {
        $genre = $this->crawler
            ->filterXPath('//span[text()="Genres:"]');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        $genre = $this->crawler
            ->filterXPath('//span[text()="Genre:"]');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return [];
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getExplicitGenres(): array
    {
        $genre = $this->crawler
            ->filterXPath('//span[text()="Explicit Genres:"]');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        $genre = $this->crawler
            ->filterXPath('//span[text()="Explicit Genre:"]');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return [];
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getDemographics(): array
    {
        $genre = $this->crawler
            ->filterXPath('//span[text()="Demographic:"]');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        $genre = $this->crawler
            ->filterXPath('//span[text()="Demographics:"]');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return [];
    }

    /**
     * @return MalUrl[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getThemes(): array
    {
        $genre = $this->crawler
            ->filterXPath('//span[text()="Theme:"]');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        $genre = $this->crawler
            ->filterXPath('//span[text()="Themes:"]');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return [];
    }

    /**
     * @return string|null
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
                str_replace($duration->text(), '', $duration->ancestors()->text())
            )
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getRating(): ?string
    {
        $rating = $this->crawler
            ->filterXPath('//span[text()="Rating:"]');

        if (!$rating->count()) {
            return null;
        }

        $rating = JString::cleanse(
            str_replace($rating->text(), '', $rating->ancestors()->text())
        );

        if ($rating === 'None') {
            return null;
        }

        return $rating;
    }

    /**
     * @return float|null
     * @throws \InvalidArgumentException
     */
    public function getScore(): ?float
    {
        $score = trim(
            $this->crawler->filterXPath('//div[@class="fl-l score"]')->text()
        );

        if ($score === 'N/A') {
            return null;
        }

        return (float) $score;

        // doesn't work for some IDs like `29711`
        //return Parser::textOrNull($this->crawler->filterXPath('//span[@itemprop="ratingValue"]'));
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getScoredBy(): ?int
    {
        $scoredBy = $this->crawler->filterXPath('//div[@class="fl-l score"]')->attr('data-user');

        $scoredByNum = str_replace(
            [',', ' users', ' user'],
            '',
            $scoredBy
        );

        if (!is_numeric($scoredByNum)) {
            return null;
        }

        return (int) $scoredByNum;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getRank(): ?int
    {
        $rank = $this->crawler
            ->filterXPath('//span[text()="Ranked:"]');

        if (!$rank->count()) {
            return null;
        }

        $ranked = JString::cleanse(
            Parser::removeChildNodes($rank->ancestors())->text()
        );

        if ($ranked === 'N/A') {
            return null;
        }

        return str_replace(
            '#',
            '',
            $ranked
        );
    }

    /**
     * @return int|null
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
            str_replace([$popularity->text(), '#'], '', $popularity->ancestors()->text())
        );
    }

    /**
     * @return int|null
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
            str_replace([$member->text(), ','], '', $member->ancestors()->text())
        );
    }

    /**
     * @return int|null
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
            str_replace([$favorite->text(), ','], '', $favorite->ancestors()->text())
        );
    }

    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        $links = $this->crawler
            ->filterXPath('//*[@id="content"]/table//div[contains(@class, "external_links")]//a[contains(@class, "link") and not(contains(@class, "js-more-links"))]');

        if (!$links->count()) {
            return [];
        }

        return $links
            ->each(function (Crawler $c) {
                return (new UrlParser($c))->getModel();
            });
    }

    /**
     * @return array
     */
    public function getStreamingLinks(): array
    {
        $links = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[1]/div/div[contains(@class, "broadcast")]//div[contains(@class, "broadcast")]');

        if (!$links->count()) {
            return [];
        }

        return $links->filterXPath('//a')
            ->each(function (Crawler  $c) {
                return (new UrlParser($c))->getModel();
            });
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getRelated(): array
    {
        $related = [];
        $this->crawler
            ->filterXPath('//table[contains(@class, "anime_detail_related_anime")]/tr')
            ->each(
                function (Crawler $c) use (&$related) {
                    $links = $c->filterXPath('//td[2]/a');
                    $relation = JString::cleanse(
                        str_replace(':', '', $c->filterXPath('//td[1]')->text())
                    );

                    if ($links->count() === 1 // if it's the only link MAL has listed
                        && empty($links->first()->text()) // and if its a bugged/empty link
                    ) {
                        $related[$relation] = [];
                        return;
                    }

                    // Remove empty/bugged links #justMALThings
                    foreach ($links as $node) {
                        if (empty($node->textContent)) {
                            $node->parentNode->removeChild($node);
                        }
                    }

                    $related[$relation] = $links->each(
                        function (Crawler $c) {
                            return (new MalUrlParser($c))->getModel();
                        }
                    );
                }
            );

        return $related;
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getBackground(): ?string
    {
        $background = Parser::removeChildNodes($this->crawler->filterXPath('//p[@itemprop="description"]/..'));

        $background->filter('p')->each(function (Crawler $c) {
            foreach ($c as $node) {
                $node->parentNode->removeChild($node);
            }
        });

        if (!$background->count()) {
            return null;
        }

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
        $node = $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs opnening"]/table/tr');

        if (preg_match('~No opening themes have been added to this title~', $node->text())) {
            return [];
        }

        return $node
            ->each(function (Crawler $crawler) {
                return JString::cleanse($crawler->text());
            });
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getEndingThemes(): array
    {
        $node = $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs ending"]/table/tr');

        if (preg_match('~No ending themes have been added to this title~', $node->text())) {
            return [];
        }

        return $node
            ->each(function (Crawler $crawler) {
                return JString::cleanse($crawler->text());
            });
    }

    /**
     * @return \Jikan\Model\Common\DateRange
     * @throws \InvalidArgumentException
     */
    public function getAired(): DateRange
    {
        return new DateRange($this->getAnimeAiredString());
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeAiredString(): ?string
    {
        $aired = $this->crawler->filterXPath('//span[contains(text(), "Aired")]/..')->html();
        $aired = explode("\n", trim($aired))[1];

        return trim($aired);
    }

    /**
     * @return string|null
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
