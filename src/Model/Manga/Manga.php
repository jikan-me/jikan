<?php

namespace Jikan\Model\Manga;

/**
 * Class Manga
 *
 * @package Jikan\Model
 */

use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Manga\MangaParser;

/**
 * Class Manga
 *
 * @package Jikan\Model
 */
class Manga
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
     * @var string[]
     */
    private $titleSynonyms;

    /**
     * @var string
     */
    private $titleJapanese;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $volumes;

    /**
     * @var bool
     */
    private $volumesUnknown;

    /**
     * @var int
     */
    private $chapters;

    /**
     * @var bool
     */
    private $chaptersUnknown;

    /**
     * @var bool
     */
    private $publishing = false;

    /**
     * @var DateRange
     */
    private $published;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var float
     */
    private $score;

    /**
     * @var int
     */
    private $scoredBy;

    /**
     * @var int
     */
    private $popularity;

    /**
     * @var int
     */
    private $members;

    /**
     * @var int
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
     * @var MalUrl[]
     */
    private $related = [];

    /**
     * @var MalUrl[]
     */
    private $genres = [];

    /**
     * @var MalUrl[]
     */
    private $authors = [];

    /**
     * @var MalUrl[]
     */
    private $serializations = [];


    /**
     * Create an instance from an MangaParser parser
     *
     * @param MangaParser $parser
     *
     * @return Manga
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaParser $parser): Manga
    {
        $instance = new self();

        $instance->title = $parser->getMangaTitle();
        $instance->url = $parser->getMangaURL();
        $instance->malId = $parser->getMangaId();
        $instance->imageUrl = $parser->getMangaImageURL();
        $instance->synopsis = $parser->getMangaSynopsis();
        $instance->titleEnglish = $parser->getMangaTitleEnglish();
        $instance->titleSynonyms = $parser->getMangaTitleSynonyms();
        $instance->titleJapanese = $parser->getMangaTitleJapanese();
        $instance->type = $parser->getMangaType();
        $instance->chapters = $parser->getMangaChapters();
        $instance->volumes = $parser->getMangaVolumes();
        $instance->chaptersUnknown = $instance->chapters === 0;
        $instance->volumesUnknown = $instance->volumes === 0;
        $instance->status = $parser->getMangaStatus();
        $instance->publishing = $instance->status === 'Publishing';
        $instance->published = $parser->getPublished();
        $instance->genres = $parser->getMangaGenre();
        $instance->score = $parser->getMangaScore();
        $instance->scoredBy = $parser->getMangaScoredBy();
        $instance->rank = $parser->getMangaRank();
        $instance->popularity = $parser->getMangaPopularity();
        $instance->members = $parser->getMangaMembers();
        $instance->favorites = $parser->getMangaFavorites();
        $instance->related = $parser->getMangaRelated();
        $instance->background = $parser->getMangaBackground();
        $instance->authors = $parser->getMangaAuthors();
        $instance->serializations = $parser->getMangaSerialization();

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
     * @return string[]
     */
    public function getTitleSynonyms(): array
    {
        return $this->titleSynonyms;
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
    public function getStatus(): string
    {
        return $this->status;
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
     * @return int
     */
    public function getVolumes(): int
    {
        return $this->volumes;
    }

    /**
     * @return bool
     */
    public function isVolumesUnknown(): bool
    {
        return $this->volumesUnknown;
    }

    /**
     * @return int
     */
    public function getChapters(): int
    {
        return $this->chapters;
    }

    /**
     * @return bool
     */
    public function isChaptersUnknown(): bool
    {
        return $this->chaptersUnknown;
    }

    /**
     * @return bool
     */
    public function isPublishing(): bool
    {
        return $this->publishing;
    }

    /**
     * @return DateRange
     */
    public function getPublished(): DateRange
    {
        return $this->published;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getScoredBy(): int
    {
        return $this->scoredBy;
    }

    /**
     * @return int
     */
    public function getPopularity(): int
    {
        return $this->popularity;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
    }

    /**
     * @return int
     */
    public function getFavorites(): int
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
     * @return MalUrl[]
     */
    public function getRelated(): array
    {
        return $this->related;
    }

    /**
     * @return MalUrl[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return MalUrl[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @return MalUrl[]
     */
    public function getSerializations(): array
    {
        return $this->serializations;
    }
}
