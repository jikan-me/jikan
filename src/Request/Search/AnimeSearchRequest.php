<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class SearchRequest
 *
 * NOTE: These were the only types available at the time of coding this
 * Add to it if more searches get enabled
 *
 * @package Jikan\Request\Search
 */
class SearchRequest implements RequestInterface
{
    public const ANIME = 'anime';
    public const MANGA = 'manga';
    public const CHARACTER = 'character';
    public const PEOPLE = 'people';
    //public const NEWS = 'news'; // for later version

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $page;

    /**
     * Advanced Search
     */

    /**
     * @var string
     */
    private $subType = 0;

    /**
     * @var float
     */
    private $score = 0;

    /**
     * @var int
     */
    private $status = 0;

    /**
     * @var int
     */
    private $producer = 0;

    /**
     * @var int
     */
    private $rated = 0;

    /**
     * @var int[]
     */
    private $startDate = [0,0,0];

    /**
     * @var int[]
     */
    private $endDate = [0,0,0];

    /**
     * @var int[]
     */
    private $genre = [];

    /**
     * @var bool
     */
    private $genreExclude = false;

    /**
     * SearchRequest constructor.
     *
     * @param string $query
     * @param string $type
     * @param int    $page
     */
    public function __construct(string $query, string $type, int $page = 0)
    {
        $this->query = $query;
        $this->type = $type;
        $this->page = $page;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        if (@\constant(self::class.'::'.strtoupper($this->type)) === null) {
            throw new \InvalidArgumentException('Invalid search type');
        }


        $query = http_build_query([
            'q' => $this->query,
            'show' => $this->page ? 50 * $this->page : null,
            'type' => $this->subType,
            'score' => $this->score,
            'status' => $this->status,
            'p' => $this->producer,
            'r' => $this->rated,
            'sd' => $this->startDate[0],
            'sm' => $this->startDate[1],
            'sy' => $this->startDate[2],
            'ed' => $this->endDate[0],
            'em' => $this->endDate[1],
            'ey' => $this->endDate[2],
            'gx' => (int) $this->genreExclude,
            'genre' => empty($this->genre) ?: $this->genre;
        ]);

        var_dump(sprintf(
            'https://myanimelist.net/%s.php?%s&c[]=a&c[]=b&c[]=c&c[]=f&c[]=d&c[]=e&c[]=g',
            $this->type,
            $query
        );
        die;
        return sprintf(
            'https://myanimelist.net/%s.php?%s&c[]=a&c[]=b&c[]=c&c[]=f&c[]=d&c[]=e&c[]=g',
            $this->type,
            $query
        );
    }

    public function setSubType(string $subType)
    {
        $this->subType = $subType;
    }

    /**
     * @param float $score
     */
    public function setScore(float $score)
    {
        $this->score = $score;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @param int $producer
     */
    public function setProducer(int $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param int $rated
     */
    public function setRated(int $rated)
    {
        $this->rated = $rated;
    }

    /**
     * @param int $day, int $month, int $year
     */
    public function setStartDate(int $day, int $month, int $year)
    {
        $this->startDate = [$day, $month, $year];
    }

    /**
     * @param int $day, int $month, int $year
     */
    public function setEndDate(int $day, int $month, int $year)
    {
        $this->endDate = [$day, $month, $year];
    }

    /**
     * @param int[] $genre
     */
    public function setGenre($genre)
    {
        if (is_array($genre)) {
            $this->genre = array_unique(
                array_merge($genre, $this->genre)
            );

            return;
        }

        if (!in_array($genre, $this->genre)) {
            $this->genre[] = $genre;
        }
    }

    /**
     * @param bool $genreExclude
     */
    public function setGenreExclude(bool $genreExclude)
    {
        $this->genreExclude = $genreExclude;
    }
}
