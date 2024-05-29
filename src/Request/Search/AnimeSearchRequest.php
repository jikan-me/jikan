<?php

namespace Jikan\Request\Search;

use Jikan\Exception\BadResponseException;
use Jikan\Request\RequestInterface;

/**
 * Class AnimeSearchRequest
 *
 * @package Jikan\Request\Search
 */
class AnimeSearchRequest implements RequestInterface
{
    /**
     * @var string|null
     */
    private $query;

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
    private $char;

    /**
     * @var string
     */
    private $type = 0;

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
    private $startDate = [0, 0, 0];

    /**
     * @var int[]
     */
    private $endDate = [0, 0, 0];

    /**
     * @var int[]
     */
    private $genre = [];

    /**
     * @var bool
     */
    private $genreExclude = false;

    /**
     * @var int
     */
    private $orderBy;

    /**
     * @var int
     */
    private $sort;

    /**
     * AnimeSearchRequest constructor.
     *
     * @param string|null $query
     * @param int         $page
     */
    public function __construct(?string $query = null, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;

        $this->query = $this->query ?? '';

        $querySize = strlen($this->query);

        if ($querySize > 0 && $querySize < 3) {
            throw new BadResponseException('Search with queries require at least 3 characters');
        }
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        $query = http_build_query(
            [
                'q'      => $this->query,
                'show'   => ($this->page !== 1) ? 50 * ($this->page - 1) : null,
                'letter' => $this->char,
                'type'   => $this->type,
                'score'  => $this->score,
                'status' => $this->status,
                'p'      => $this->producer,
                'r'      => $this->rated,
                'sd'     => $this->startDate[0],
                'sm'     => $this->startDate[1],
                'sy'     => $this->startDate[2],
                'ed'     => $this->endDate[0],
                'em'     => $this->endDate[1],
                'ey'     => $this->endDate[2],
                'gx'     => (int)$this->genreExclude,
                'o'      => $this->orderBy,
                'w'      => $this->sort
            ]
        );

        // Add genre[]=
        if (!empty($this->genre)) {
            foreach ($this->genre as $genre) {
                $query .= '&genre[]='.$genre;
            }
        }

        return sprintf(
            'https://myanimelist.net/anime.php?%s&c[]=a&c[]=b&c[]=c&c[]=f&c[]=d&c[]=e&c[]=g',
            $query
        );
    }

    /**
     * @param null|string $query
     *
     * @return $this
     */
    public function setQuery(?string $query = null): self
    {
        $this->query = $query;
        $this->query = $this->query ?? '';

        return $this;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param string $char
     *
     * @return $this
     */
    public function setStartsWithChar(string $char): self
    {
        $this->char = $char;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param float $score
     *
     * @return $this
     */
    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param int $producer
     *
     * @return $this
     */
    public function setProducer(int $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * @param int $rated
     *
     * @return $this
     */
    public function setRated(int $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    /**
     * @param int $day   , int $month, int $year
     * @param int $month
     * @param int $year
     *
     * @return $this
     */
    public function setStartDate(int $day, int $month, int $year): self
    {
        $this->startDate = [$day, $month, $year];

        return $this;
    }

    /**
     * @param int $day   , int $month, int $year
     * @param int $month
     * @param int $year
     *
     * @return $this
     */
    public function setEndDate(int $day, int $month, int $year): self
    {
        $this->endDate = [$day, $month, $year];

        return $this;
    }

    /**
     * @param array|int ...$genre
     *
     * @return AnimeSearchRequest
     */
    public function setGenre(...$genre): self
    {
        $this->genre = array_unique(
            array_merge($genre, $this->genre)
        );


        return $this;
    }

    /**
     * @param bool $genreExclude
     *
     * @return $this
     */
    public function setGenreExclude(bool $genreExclude): self
    {
        $this->genreExclude = $genreExclude;

        return $this;
    }

    /**
     * @param  string $char
     * @return AnimeSearchRequest
     */
    public function setChar(string $char): AnimeSearchRequest
    {
        $this->char = $char;
        return $this;
    }

    /**
     * @param  int $orderBy
     * @return AnimeSearchRequest
     */
    public function setOrderBy(int $orderBy): AnimeSearchRequest
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param  int $sort
     * @return AnimeSearchRequest
     */
    public function setSort(int $sort): AnimeSearchRequest
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getChar(): string
    {
        return $this->char;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getProducer(): int
    {
        return $this->producer;
    }

    /**
     * @return int
     */
    public function getRated(): int
    {
        return $this->rated;
    }

    /**
     * @return int[]
     */
    public function getStartDate(): array
    {
        return $this->startDate;
    }

    /**
     * @return int[]
     */
    public function getEndDate(): array
    {
        return $this->endDate;
    }

    /**
     * @return int[]
     */
    public function getGenre(): array
    {
        return $this->genre;
    }

    /**
     * @return bool
     */
    public function isGenreExclude(): bool
    {
        return $this->genreExclude;
    }

    /**
     * @return int
     */
    public function getOrderBy(): int
    {
        return $this->orderBy;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }
}
