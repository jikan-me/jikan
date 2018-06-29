<?php

namespace Jikan\Parser\Manga;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Parser\Common\MalUrlParser;
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
     * @return \Jikan\Model\Manga
     */
    public function getModel(): \Jikan\Model\Manga
    {
        return \Jikan\Model\Manga::fromParser($this);
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
    public function getMangaSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content')
        );
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
            str_replace($title->text(), '', $title->parents()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getMangaTitleSynonyms(): ?string
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
    public function getMangaTitleJapanese(): ?string
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
    public function getMangaType(): ?string
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
    public function getMangaChapters(): ?int
    {
        $chapters = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Chapters:"]');

        if (!$chapters->count()) {
            return null;
        }

        return (str_replace($chapters->text(), '', $chapters->parents()->text()) === 'Unknown') ? 0 : (int)str_replace(
            $chapters->text(),
            '',
            $chapters->parents()->text()
        );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getMangaVolumes(): ?int
    {
        $chapters = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Volumes:"]');

        if (!$chapters->count()) {
            return null;
        }

        return (str_replace($chapters->text(), '', $chapters->parents()->text()) === 'Unknown') ? 0 : (int)str_replace(
            $chapters->text(),
            '',
            $chapters->parents()->text()
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
            str_replace($status->text(), '', $status->parents()->text())
        );
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
            str_replace($aired->text(), '', $aired->parents()->text())
        );
    }


    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaAuthors(): array
    {
        $author = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Authors:"]');

        if ($author->count() && strpos($author->parents()->text(), 'None found') === false) {
            return $author->parents()->first()->filter('a')->each(
                function (Crawler $crawler) {
                    return (new MalUrlParser($crawler))->getModel();
                }
            );
        }

        return []; // If `None found`
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaSerialization(): array
    {
        $studio = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Serialization:"]');

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
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getMangaGenre(): array
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

        $rank = Parser::removeChildNodes($rank->parents());
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
            str_replace([$popularity->text(), '#'], '', $popularity->parents()->text())
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
            str_replace([$member->text(), ','], '', $member->parents()->text())
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
            str_replace([$favorite->text(), ','], '', $favorite->parents()->text())
        );
    }

    /**
     * @return array
     * @throws \RuntimeException
     * @todo Create related manga sub parser
     */
    public function getMangaRelated(): array
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
    public function getMangaBackground(): ?string
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
}
