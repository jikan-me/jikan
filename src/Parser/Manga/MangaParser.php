<?php

namespace Jikan\Parser\Manga;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use Jikan\Model\Manga\Manga;
use Jikan\Parser\Common\AlternativeTitleParser;
use Jikan\Parser\Common\MalUrlParser;
use Jikan\Parser\Common\UrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaParser
 *
 * @package Jikan\Parser
 */
class MangaParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Manga\Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Manga
    {
        return Manga::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaId(): int
    {
        preg_match('#https?://myanimelist.net/manga/(\d+)#', $this->getMangaURL(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaSynopsis(): ?string
    {
        $synopsis = JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content')
        );

        return preg_match('~^Looking for information on the manga~', $synopsis) ? null : $synopsis;
    }

    /**
     * @return bool
     */
    public function getApproved(): bool
    {
        $node = $this->crawler->filterXPath('//*[@id="addtolist"]//span[contains(text(), "pending approval")]');

        if ($node->count()) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getMangaTitleEnglish(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="English:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->ancestors()->text())
        );
    }

    /**
     * @return string[]
     * @throws \InvalidArgumentException
     */
    public function getMangaTitleSynonyms(): array
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getMangaTitleJapanese(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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

        $titles = [new Title(Title::TYPE_DEFAULT, $this->getMangaTitle())];

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
    public function getMangaType(): ?string
    {
        $type = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
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
    public function getMangaChapters(): ?int
    {
        $chapters = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Chapters:"]');

        if (!$chapters->count()) {
            return null;
        }

        return
            (
                trim(
                    str_replace($chapters->text(), '', $chapters->ancestors()->text())
                ) === 'Unknown'
            )
                ?
                null
                :
                (int)str_replace(
                    $chapters->text(),
                    '',
                    $chapters->ancestors()->text()
                );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getMangaVolumes(): ?int
    {
        $volumes = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Volumes:"]');

        if (!$volumes->count()) {
            return null;
        }

        return
            (
                trim(
                    str_replace($volumes->text(), '', $volumes->ancestors()->text())
                ) === 'Unknown'
            )
                ?
                null
                :
                (int)str_replace(
                    $volumes->text(),
                    '',
                    $volumes->ancestors()->text()
                );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getMangaStatus(): ?string
    {
        $status = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Status:"]');
        if (!$status->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($status->text(), '', $status->ancestors()->text())
        );
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaAuthors(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Authors:"]/following-sibling::a')
            ->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
    }

    /**
     * @return MalUrl[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaSerialization(): array
    {
        return $this->crawler
            ->filterXPath('//span[text()="Serialization:"]/following-sibling::a')->each(
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
            ->filterXPath('//span[text()="Demographics:"]');

        if ($genre->count()) {
            return $genre->ancestors()->first()->filterXPath('//a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        $genre = $this->crawler
            ->filterXPath('//span[text()="Demographic:"]');

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
     * @return float
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaScore(): ?float
    {
        $score = $this->crawler
            ->filter('span[itemprop="ratingValue"]');

        if (!$score->count()) {
            return null;
        }

        if (strpos($score->text(), 'N/A')) {
            return null;
        }

        return (float)$score->text();
    }

    /**
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaScoredBy(): ?int
    {
        $scoredBy = $this->crawler
            ->filter('span[itemprop="ratingCount"]');

        if (!$scoredBy->count()) {
            return null;
        }

        return (int)$scoredBy->text();
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMangaRank(): ?int
    {
        $rank = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Ranked:"]');

        if (!$rank->count()) {
            return null;
        }

        $rank = Parser::removeChildNodes($rank->ancestors());
        $ranked = trim(
            str_replace(
                '#',
                '',
                $rank->text()
            )
        );

        return $ranked !== 'N/A' ? (int)$ranked : null;
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMangaPopularity(): ?int
    {
        $popularity = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Popularity:"]');

        if (!$popularity->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$popularity->text(), '#'], '', $popularity->ancestors()->text())
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMangaMembers(): ?int
    {
        $member = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Members:"]');

        if (!$member->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$member->text(), ','], '', $member->ancestors()->text())
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getMangaFavorites(): ?int
    {
        $favorite = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Favorites:"]');

        if (!$favorite->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$favorite->text(), ','], '', $favorite->ancestors()->text())
        );
    }

    /**
     * @return array|MalUrl[]
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMangaRelated(): array
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
     * @return string
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaBackground(): ?string
    {
        $background = Parser::removeChildNodes($this->crawler->filterXPath('//span[@itemprop="description"]/..'));
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
     * @return DateRange
     * @throws \InvalidArgumentException
     */
    public function getPublished(): DateRange
    {
        return new DateRange($this->getMangaPublishedString());
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getMangaPublishedString(): ?string
    {
        $aired = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Published:"]');

        if (!$aired->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($aired->text(), '', $aired->ancestors()->text())
        );
    }
}
