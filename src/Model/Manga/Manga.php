<?php

namespace Jikan\Model\Manga;

/**
 * Class Manga
 *
 * @package Jikan\Model
 */

use Jikan\Model\Common\DateRange;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\Title;
use Jikan\Model\Common\Url;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
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
    private int $malId;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string|null
     */
    private ?string $titleEnglish;

    /**
     * @var string[]
     */
    private array $titleSynonyms;

    /**
     * @var bool
     */
    private bool $approved;

    /**
     * @var string|null
     */
    private ?string $titleJapanese;

    /**
     * @var \Jikan\Model\Common\Title[]
     */
    private array $titles;

    /**
     * @var string
     */
    private string $status;

    /**
     * @var CommonImageResource
     */
    private CommonImageResource $images;

    /**
     * @var string|null
     */
    private ?string $type;

    /**
     * @var int|null
     */
    private ?int $volumes;

    /**
     * @var int|null
     */
    private ?int $chapters;

    /**
     * @var bool
     */
    private bool $publishing = false;

    /**
     * @var DateRange
     */
    private DateRange $published;

    /**
     * @var int|null
     */
    private ?int $rank;

    /**
     * @var float|null
     */
    private ?float $score;

    /**
     * @var int|null
     */
    private ?int $scoredBy;

    /**
     * @var int|null
     */
    private ?int $popularity;

    /**
     * @var int|null
     */
    private ?int $members;

    /**
     * @var int|null
     */
    private ?int $favorites;

    /**
     * @var string|null
     */
    private ?string $synopsis;

    /**
     * @var string|null
     */
    private ?string $background;

    /**
     * @var MalUrl[]
     */
    private array $related = [];

    /**
     * @var MalUrl[]
     */
    private array $genres = [];

    /**
     * @var MalUrl[]
     */
    private array $explicitGenres = [];

    /**
     * @var MalUrl[]
     */
    private array $demographics = [];

    /**
     * @var MalUrl[]
     */
    private array $themes = [];

    /**
     * @var MalUrl[]
     */
    private array $authors = [];

    /**
     * @var MalUrl[]
     */
    private array $serializations = [];

    /**
     * @var Url[]
     */
    private array $externalLinks = [];


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
        $instance->approved = $parser->getApproved();
        $instance->images = CommonImageResource::factory($parser->getMangaImageURL());
        $instance->synopsis = $parser->getMangaSynopsis();
        $instance->titleEnglish = $parser->getMangaTitleEnglish();
        $instance->titleSynonyms = $parser->getMangaTitleSynonyms();
        $instance->titleJapanese = $parser->getMangaTitleJapanese();
        $instance->titles = $parser->getTitles();
        $instance->type = $parser->getMangaType();
        $instance->chapters = $parser->getMangaChapters();
        $instance->volumes = $parser->getMangaVolumes();
        $instance->status = $parser->getMangaStatus();
        $instance->publishing = $instance->status === 'Publishing';
        $instance->published = $parser->getPublished();
        $instance->genres = $parser->getGenres();
        $instance->explicitGenres = $parser->getExplicitGenres();
        $instance->demographics = $parser->getDemographics();
        $instance->themes = $parser->getThemes();
        $instance->score = $parser->getScore();
        $instance->scoredBy = $parser->getScoredBy();
        $instance->rank = $parser->getMangaRank();
        $instance->popularity = $parser->getMangaPopularity();
        $instance->members = $parser->getMangaMembers();
        $instance->favorites = $parser->getMangaFavorites();
        $instance->externalLinks = $parser->getExternalLinks();
        $instance->related = $parser->getMangaRelated();
        $instance->background = $parser->getMangaBackground();
        $instance->authors = $parser->getMangaAuthors();
        $instance->serializations = $parser->getMangaSerialization();

        return $instance;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return MalUrl[]
     */
    public function getExplicitGenres(): array
    {
        return $this->explicitGenres;
    }

    /**
     * @return MalUrl[]
     */
    public function getDemographics(): array
    {
        return $this->demographics;
    }

    /**
     * @return MalUrl[]
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return Url[]
     */
    public function getExternalLinks(): array
    {
        return $this->externalLinks;
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
     * @deprecated Use {@link Manga::getTitles()} instead.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     * @deprecated Use {@link Manga::getTitles()} instead.
     */
    public function getTitleEnglish(): ?string
    {
        return $this->titleEnglish;
    }

    /**
     * @return string[]
     * @deprecated Use {@link Manga::getTitles()} instead.
     */
    public function getTitleSynonyms(): array
    {
        return $this->titleSynonyms;
    }

    /**
     * @return string|null
     * @deprecated Use {@link Manga::getTitles()} instead.
     */
    public function getTitleJapanese(): ?string
    {
        return $this->titleJapanese;
    }

    /**
     * @return \Jikan\Model\Common\Title[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }


    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getVolumes(): ?int
    {
        return $this->volumes;
    }

    /**
     * @return int|null
     */
    public function getChapters(): ?int
    {
        return $this->chapters;
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
     * @return int|null
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * @return int|null
     */
    public function getScoredBy(): ?int
    {
        return $this->scoredBy;
    }

    /**
     * @return int|null
     */
    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    /**
     * @return int|null
     */
    public function getMembers(): ?int
    {
        return $this->members;
    }

    /**
     * @return int|null
     */
    public function getFavorites(): ?int
    {
        return $this->favorites;
    }

    /**
     * @return string|null
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * @return string|null
     */
    public function getBackground(): ?string
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
