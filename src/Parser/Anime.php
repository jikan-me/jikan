<?php

namespace Jikan\Parser;

use Jikan\Helper\JString;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Anime
 *
 * @package Jikan\Parser
 */
class Anime implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Anime constructor.
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
     * @throws \RuntimeException
     */
    public function getAnimeId(): int
    {
        preg_match('#https?://myanimelist.net/anime/(\d+)#', $this->getAnimeUrl(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     */
    public function getAnimeTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->extract(['content'])[0]
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
     */
    public function getAnimeProducer(): array
    {
        $producer = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Producers:"]');

        $producers = [];
        if (strpos($producer->parents()->text(), 'None found') === false && $producer->count() > 0) {
            $producers = $producer->parents()->first()->filter('a')->each(function($node) {
                return [
                    'url' => BASE_URL . substr($node->attr('href'), 1),
                    'name' => $node->text()
                ];
            });
        }
        return $producers;
    }

    /**
     * @return array
     */
    public function getAnimeLicensor(): array
    {
        $licensor = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Licensors:"]');

        $licensors = [];
        if (strpos($licensor->parents()->text(), 'None found') === false && $licensor->count() > 0) {
            $licensors = $licensor->parents()->first()->filter('a')->each(function($node) {
                return [
                    'url' => BASE_URL . substr($node->attr('href'), 1),
                    'name' => $node->text()
                ];
            });
        }
        return $licensors;
    }

    /**
     * @return array
     */
    public function getAnimeStudio(): array
    {
        $studio = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Studios:"]');

        $studios = [];
        if (strpos($studio->parents()->text(), 'None found') === false && $studio->count() > 0) {
            $studios = $studio->parents()->first()->filter('a')->each(function($node) {
                return [
                    'url' => BASE_URL . substr($node->attr('href'), 1),
                    'name' => $node->text()
                ];
            });
        }
        return $studios;
    }

    /**
     * @return string
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
     */
    public function getAnimeGenre(): array
    {
        $genre = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Genres:"]');

        $genres = [];
        if (strpos($genre->parents()->text(), 'No genres have been added yet') === false && $genre->count() > 0) {
            $genres = $genre->parents()->first()->filter('a')->each(function($node) {
                return [
                    'url' => BASE_URL . substr($node->attr('href'), 1),
                    'name' => $node->text()
                ];
            });
        }
        return $genres;
    }

    /**
     * @return string
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
            str_replace('.', '',
                str_replace($duration->text(), '', $duration->parents()->text())
            )
        );
    }

    /**
     * @return string
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
     */
    public function getAnimeRank(): ?int
    {
        $rank = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Ranked:"]');

        if (!$rank->count()) {
            return null;
        }
            
        $ranked = str_replace('#', '',
            substr(
                explode(PHP_EOL, trim(str_replace($rank->text(), '', $rank->parents()->text())))[0],
                0, -1
            )
        );

        return $ranked !== 'N/A' ? $ranked : null;
    }

    /**
     * @return int
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
     */
    public function getAnimeRelated(): array
    {
        $related = [];
        $relatedNode = $this->crawler
            ->filter('table.anime_detail_related_anime')
            ->filter('tr')->each(function($tr) {
                return $tr->each(function($td) {
                    $related = [];
                    $relationType = substr($td->filter('td')->first()->text(), 0, -1);
                    $relationNodes = $td->filter('td')->last();
                    
                    $related[$relationType] = $relationNodes->filter('a')->each(function($node) {
                        $url = BASE_URL . substr($node->attr('href'), 1);
                        preg_match('~https://myanimelist.net/(.*)/(.*)/(.*)~', $url, $matches);

                        return [
                            'mal_id' => (int) $matches[2],
                            'type' => $matches[1],
                            'url' => $url,
                            'title' => $node->text()
                        ];
                    });

                    return $related;
                })[0];
            });

        foreach ($relatedNode as $node) {
            $related = array_merge($related, $node);
        }

        return $related;
    }

    /**
     * @return string
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
