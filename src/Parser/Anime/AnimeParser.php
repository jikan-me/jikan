<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\Constants;
use Jikan\Helper\ImagePlaceholder;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\Anime;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use Jikan\Parser\Common\AlternativeTitleParser;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\UrlParser;
use Jikan\Parser\Common\IndexesSidebarLabels;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeParser
 *
 * @package Jikan\Parser
 */
class AnimeParser implements ParserInterface
{
    use IndexesSidebarLabels;

    /**
     * Every sidebar label this parser looks up (indexed in one pass by IndexesSidebarLabels).
     */
    private const SIDEBAR_LABELS = [
        'English:',
        'Synonyms:',
        'Japanese:',
        'Type:',
        'Episodes:',
        'Status:',
        'Broadcast:',
        'Producers:',
        'Licensors:',
        'Studios:',
        'Source:',
        'Genres:',
        'Genre:',
        'Explicit Genres:',
        'Explicit Genre:',
        'Demographic:',
        'Demographics:',
        'Theme:',
        'Themes:',
        'Duration:',
        'Rating:',
        'Ranked:',
        'Popularity:',
        'Members:',
        'Favorites:',
    ];

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
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getImageURL(): ?string
    {
        return ImagePlaceholder::normalize(
            $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content')
        );
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
        $title = $this->label('English:');
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
        $title = $this->label('Synonyms:');

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
        $title = $this->label('Japanese:');
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
                return new AlternativeTitleParser($item)->getModel();
            })
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getType(): ?string
    {
        $type = $this->label('Type:');
        if (!$type->count()) {
            return null;
        }

        $type = JString::cleanse(str_replace($type->text(), '', $type->ancestors()->text()));
        return $type === Constants::DISPLAY_UNKNOWN ? null : $type;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getEpisodes(): ?int
    {
        $episodes = $this->label('Episodes:');


        if (!$episodes->count()) {
            return null;
        }

        return
            (
                trim(
                    str_replace($episodes->text(), '', $episodes->ancestors()->text())
                ) === Constants::DISPLAY_UNKNOWN
            )
                ? null
                : (int) str_replace(
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
        $status = $this->label('Status:');
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
            ->filterXPath('//span[contains(normalize-space(.), "Premiered:")]');

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
        $broadcast = $this->label('Broadcast:');

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
        $producer = $this->label('Producers:');

        if ($producer->count() && strpos($producer->ancestors()->text(), 'None found') === false) {
            return $producer->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $licensor = $this->label('Licensors:');

        if ($licensor->count() && strpos($licensor->ancestors()->text(), 'None found') === false) {
            return $licensor->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $studio = $this->label('Studios:');

        if ($studio->count() && strpos($studio->ancestors()->text(), 'None found') === false) {
            return $studio->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $source = $this->label('Source:');

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
        $genre = $this->label('Genres:');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
                }
            );
        }

        $genre = $this->label('Genre:');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $genre = $this->label('Explicit Genres:');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
                }
            );
        }

        $genre = $this->label('Explicit Genre:');

        if ($genre->count() && strpos($genre->ancestors()->text(), 'No genres have been added yet') === false) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $genre = $this->label('Demographic:');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
                }
            );
        }

        $genre = $this->label('Demographics:');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $genre = $this->label('Theme:');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
                }
            );
        }

        $genre = $this->label('Themes:');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return new MalUrlParser($crawler)->getModel();
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
        $duration = $this->label('Duration:');

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
        $rating = $this->label('Rating:');

        if (!$rating->count()) {
            return null;
        }

        $rating = JString::cleanse(
            str_replace($rating->text(), '', $rating->ancestors()->text())
        );

        if ($rating === Constants::DISPLAY_NONE) {
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
        $score = $this->crawler->filterXPath('//span[@itemprop="ratingValue"]');

        if (!$score->count()) {
            return null;
        }

        $score = JString::cleanse($score->text());

        if ($score === Constants::DISPLAY_N_A) {
            return null;
        }

        return (float) $score;
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getScoredBy(): ?int
    {
        $scoredBy = $this->crawler->filterXPath('//span[@itemprop="ratingCount"]');

        if (!$scoredBy->count()) {
            return null;
        }

        $scoredBy = JString::cleanse($scoredBy->text());

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
        $rank = $this->label('Ranked:');

        if (!$rank->count()) {
            return null;
        }

        $ranked = JString::cleanse(
            Parser::removeChildNodes($rank->ancestors())->text()
        );

        if ($ranked === Constants::DISPLAY_N_A) {
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
        $popularity = $this->label('Popularity:');

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
        $member = $this->label('Members:');

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
        $favorite = $this->label('Favorites:');

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
                return new UrlParser($c)->getModel();
            });
    }

    /**
     * @return array
     */
    public function getStreamingLinks(): array
    {
        $links = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tbody/tr/td[1]/div/div[contains(@class, "broadcast")]//div[contains(@class, "broadcast")]');

        if (!$links->count()) {
            return [];
        }

        return $links->filterXPath('//a')
            ->each(function (Crawler  $c) {
                return new UrlParser($c)->getModel();
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

        // MAL has divided relations up into tiles and table format
        // We first parse whatever there's in the tiles
        $this->crawler
            ->filterXPath('//div[contains(@class, "related-entries")]/div[contains(@class, "entries-tile")]/div[contains(@class, "entry")]')
            ->each(
                function (Crawler $c) use (&$related) {
                    $relation = $c->filterXPath('//div[@class="content"]/div[@class="relation"]');

                    // skips empty entry boxes
                    if (!$relation->count()) {
                        return;
                    }

                    $relation = $relation->text();

                    // strip entry type (if any)
                    $relation = JString::cleanse(
                        preg_replace("~\s\(.*\)~", '', $relation)
                    );

                    $links = $c->filterXPath('//div[@class="content"]/div[@class="title"]/a');

                    // Check for empty links #justMALThings
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

                    $related[$relation][] = new MalUrlParser($links)->getModel();
                }
            );

        // Then we'll parse the table
        $this->crawler
            ->filterXPath('//table[contains(@class, "entries-table")]/tbody/tr')
            ->each(
                function (Crawler $c) use (&$related) {
                    $links = $c->filterXPath('//td[2]//a');
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
                            return new MalUrlParser($c)->getModel();
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
        $node = $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs opnening"]/table/tbody/tr/td');

        if (0 === $node->count()) {
            return [];
        }

        if (str_contains($node->text(), 'No opening themes have been added to this title')) {
            return [];
        }

        // Current MAL markup interleaves empty <td> cells with real ones; exclude
        // them so a one-theme title does not return ["", theme, ""]. jikan#593.
        return array_values(array_filter(
            $node->each(function (Crawler $crawler) {
                return JString::cleanse($crawler->text());
            })
        ));
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getEndingThemes(): array
    {
        $node = $this->crawler->filterXPath('//div[@class="theme-songs js-theme-songs ending"]/table/tbody/tr');

        if (0 === $node->count()) {
            return [];
        }

        if (preg_match('~No ending themes have been added to this title~', $node->text())) {
            return [];
        }

        return array_values(array_filter(
            $node->each(function (Crawler $crawler) {
                return JString::cleanse($crawler->text());
            })
        ));
    }

    /**
     * @return \Jikan\Model\Common\DateRange
     * @throws \InvalidArgumentException
     */
    public function getAired(): DateRange
    {
        // no "Aired:" row: the empty range MAL itself shows as "Not available"
        return new DateRange($this->getAnimeAiredString() ?? 'Not available');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeAiredString(): ?string
    {
        $row = $this->crawler->filterXPath('//span[contains(text(), "Aired")]/..');
        if (!$row->count()) {
            return null;
        }
        $aired = explode("\n", trim($row->html()))[1] ?? null;

        return $aired === null ? null : trim($aired);
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
