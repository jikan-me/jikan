<?php

namespace Jikan\Model;

/**
 * Class Anime
 *
 * @package Jikan\Model
 */
class Anime
{

    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $titleEnglish;

    /**
     * @var string
     */
    private $titleJapanese;

    /**
     * @var string
     */
    private $titleSynonyms;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $episodes;

    /**
     * @var bool
     */
    private $episodesUnknown;

    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $airing = false;

    /**
     * @var string
     */
    private $airedString;

    /**
     * @var array
     */
    private $aired = [
        'from' => null,
        'to'   => null,
    ];

    /**
     * @var string
     */
    private $duration;

    /**
     * @var string
     */
    private $rating;

    /**
     * @var string
     */
    private $score;

    /**
     * @var string
     */
    private $scoredBy;

    /**
     * @var string
     */
    private $rank;

    /**
     * @var string
     */
    private $popularity;

    /**
     * @var string
     */
    private $members;

    /**
     * @var string
     */
    private $favorites;

    /**
     * @var string
     */
    private $synopsis;

    /**
     * @var string
     */
    private $background;

    /**
     * @var string
     */
    private $premiered;

    /**
     * @var string
     */
    private $broadcast;

    /**
     * @var array
     */
    private $related = [];

    /**
     * @var array
     */
    private $producer = [];

    /**
     * @var array
     */
    private $licensor = [];

    /**
     * @var array
     */
    private $studio = [];

    /**
     * @var array
     */
    private $genre = [];

    /**
     * @var array
     */
    private $openingTheme = [];

    /**
     * @var array
     */
    private $endingTheme = [];

    /**
     * Create an instance from an Anime parser
     *
     * @param \Jikan\Parser\Anime $parser
     *
     * @return Anime
     */
    public static function fromParser(\Jikan\Parser\Anime $parser): Anime
    {
        $instance = new self();
        $instance->title = $parser->getAnimeTitle();
        $instance->url = $parser->getAnimeURL();
        $instance->malId = $parser->getAnimeID();

        $instance->imageUrl = $parser->getAnimeImageURL();
        $instance->synopsis = $parser->getAnimeSynopsis();
        $instance->titleEnglish = $parser->getAnimeTitleEnglish();
        $instance->titleSynonyms = $parser->getAnimeTitleSynonyms();
        $instance->titleJapanese = $parser->getAnimeTitleJapanese();
        $instance->type = $parser->getAnimeType();
        $instance->episodes = $parser->getAnimeEpisodes();
        $instance->episodesUnknown = $instance->episodes === 0;
        $instance->status = $parser->getAnimeStatus();
        $instance->airing = $instance->status === 'Currently Airing';
        $instance->airedString = $parser->getAnimeAiredString();

        if (!empty($instance->airedString) && $instance->airedString != 'Not available') {
            if (strpos($instance->airedString, 'to')) {
                preg_match('~(.*) to (.*)~', $instance->airedString, $matches);
                $instance->aired = [
                    'from' => (strpos($matches[1], '?') !== false) ? null : @date_format(date_create($matches[1]), 'o-m-d'),
                    'to' => (strpos($matches[2], '?') !== false) ? null : @date_format(date_create($matches[2]), 'o-m-d')
                ];
            } else {
                if (
                    preg_match('~^[0-9]{4}$~', $instance->airedString)
                    || preg_match('~^[A-Za-z]{1,}, [0-9]{4}$~', $instance->airedString)
                    ) 
                {
                    $instance->aired = [
                        'from' => null,
                        'to' => null
                    ];
                } else {
                    $instance->aired = [
                        'from' => (strpos($instance->airedString, '?') !== false) ? null : @date_format(date_create($instance->airedString), 'o-m-d'),
                        'to' => (strpos($instance->airedString, '?') !== false) ? null : @date_format(date_create($instance->airedString), 'o-m-d')
                    ];
                }
            }
        } else {
            $instance->aired = [
                'from' => null,
                'to' => null
            ];
        }

        $instance->premiered = $parser->getAnimePremiered();
        $instance->broadcast = $parser->getAnimeBroadcast();
        $instance->producer = $parser->getAnimeProducer();
        $instance->licensor = $parser->getAnimeLicensor();
        $instance->studio = $parser->getAnimeStudio();
        $instance->source = $parser->getAnimeSource();
        $instance->genre = $parser->getAnimeGenre();
        $instance->duration = $parser->getAnimeDuration();
        $instance->rating = $parser->getAnimeRating();

        $scoreString = $parser->getAnimeScore();
        preg_match('~(.*)1 \(scored by (.*) users\)~', $scoreString, $matches);
        if ($matches[1] !== 'N/A') {
            $instance->score = (float) $matches[1];
        }
        $instance->scoredBy = str_replace(',', '', $matches[2]);
        $instance->rank = $parser->getAnimeRank();
        $instance->popularity = $parser->getAnimePopularity();
        $instance->members = $parser->getAnimeMembers();
        $instance->favorites = $parser->getAnimeFavorites();
        $instance->related = $parser->getAnimeRelated();
        $instance->background = $parser->getAnimeBackground();
        $instance->openingTheme = $parser->getAnimeOpeningTheme();
        $instance->endingTheme = $parser->getAnimeEndingTheme();



        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTitleEnglish(): string
    {
        return $this->titleEnglish;
    }

    /**
     * @return string
     */
    public function getTitleJapanese(): string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string
     */
    public function getTitleSynonyms(): string
    {
        return $this->titleSynonyms;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getEpisodes(): string
    {
        return $this->episodes;
    }

    /**
     * @return string
     */
    public function getEpisodesUnknown(): string
    {
        return $this->episodesUnknown;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isAiring(): bool
    {
        return $this->airing;
    }

    /**
     * @return string
     */
    public function getAiredString(): string
    {
        return $this->airedString;
    }

    /**
     * @return array
     */
    public function getAired(): array
    {
        return $this->aired;
    }

    /**
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getScore(): string
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getScoredBy(): string
    {
        return $this->scoredBy;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @return string
     */
    public function getPopularity(): string
    {
        return $this->popularity;
    }

    /**
     * @return string
     */
    public function getMembers(): string
    {
        return $this->members;
    }

    /**
     * @return string
     */
    public function getFavorites(): string
    {
        return $this->favorites;
    }

    /**
     * @return string
     */
    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    /**
     * @return string
     */
    public function getBackground(): string
    {
        return $this->background;
    }

    /**
     * @return string
     */
    public function getPremiered(): string
    {
        return $this->premiered;
    }

    /**
     * @return string
     */
    public function getBroadcast(): string
    {
        return $this->broadcast;
    }

    /**
     * @return array
     */
    public function getRelated(): array
    {
        return $this->related;
    }

    /**
     * @return array
     */
    public function getProducer(): array
    {
        return $this->producer;
    }

    /**
     * @return array
     */
    public function getLicensor(): array
    {
        return $this->licensor;
    }

    /**
     * @return array
     */
    public function getStudio(): array
    {
        return $this->studio;
    }

    /**
     * @return array
     */
    public function getGenre(): array
    {
        return $this->genre;
    }

    /**
     * @return array
     */
    public function getOpeningTheme(): array
    {
        return $this->openingTheme;
    }

    /**
     * @return array
     */
    public function getEndingTheme(): array
    {
        return $this->endingTheme;
    }
}
