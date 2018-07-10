<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class MangaSearchRequest
 *
 *
 * @package Jikan\Request\Search
 */
class MangaSearchRequest implements RequestInterface
{

    /**
     * @var string
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
    private $magazine = 0;

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
     * @param int    $page
     */
    public function __construct(string $query, int $page = 1)
    {
        $this->query = $query;
        $this->page = $page;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {

        $query = http_build_query([
            'q' => $this->query,
            'show' => $this->page ? 50 * ($this->page-1) : null,
            'type' => $this->type,
            'score' => $this->score,
            'status' => $this->status,
            'mid' => $this->magazine,
            'sd' => $this->startDate[0],
            'sm' => $this->startDate[1],
            'sy' => $this->startDate[2],
            'ed' => $this->endDate[0],
            'em' => $this->endDate[1],
            'ey' => $this->endDate[2],
            'gx' => (int) $this->genreExclude,
        ]);

        // Add genre[]=
        if (!empty($this->genre)) {
            foreach ($this->genre as $genre) {
                $query .= '&genre[]=' . $genre;
            }
        }

        return sprintf(
            'https://myanimelist.net/manga.php?%s&c[]=a&c[]=b&c[]=c&c[]=f&c[]=d&c[]=e&c[]=g',
            $query
        );
    }

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param float $score
     * @return $this
     */
    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param int $magazine
     * @return $this
     */
    public function setMagazine(int $magazine): self
    {
        $this->magazine = $magazine;

        return $this;
    }

    /**
     * @param int $day , int $month, int $year
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
     * @param int $day , int $month, int $year
     * @param int $month
     * @param int $year
     * @return $this
     */
    public function setEndDate(int $day, int $month, int $year): self
    {
        $this->endDate = [$day, $month, $year];

        return $this;
    }

    /**
     * @param int ...$genre
     * @return $this
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
     * @return $this
     */
    public function setGenreExclude(bool $genreExclude): self
    {
        $this->genreExclude = $genreExclude;

        return $this;
    }
}