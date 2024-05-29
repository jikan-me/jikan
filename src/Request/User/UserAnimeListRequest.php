<?php

namespace Jikan\Request\User;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class UserAnimeListRequest
 *
 * @package Jikan\Request
 */
class UserAnimeListRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $orderBy;

    /**
     * @var int
     */
    private $orderBy2;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $season;

    /**
     * @var int
     */
    private $seasonYear;

    /**
     * @var array
     */
    private $airedFrom = [null, null, null];

    /**
     * @var array
     */
    private $airedTo = [null, null, null];

    /**
     * @var int
     */
    private $airingStatus;

    /**
     * @var int
     */
    private $producer;

    /**
     * UserAnimeListRequest constructor.
     *
     * @param string $username
     * @param int    $page
     * @param int    $status
     */
    public function __construct(string $username, int $page = 1, int $status = Constants::USER_ANIME_LIST_ALL)
    {
        $this->username = $username;
        $this->page = ($page - 1) * 300;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $query = '?'.http_build_query(
            [
            'offset' => $this->page,
            'status' => $this->status,
            'order' => $this->orderBy,
            'order2' => $this->orderBy2,
            's' => $this->title,
            'season' => $this->season,
            'season_year' => $this->seasonYear,
            'aired_from_year' => $this->airedFrom[0],
            'aired_from_month' => $this->airedFrom[1],
            'aired_from_day' => $this->airedFrom[2],
            'aired_to_year' => $this->airedTo[0],
            'aired_to_month' => $this->airedTo[1],
            'aired_to_day' => $this->airedTo[2],
            'producer' => $this->producer,
            'airing_status' => $this->airingStatus
            ]
        );

        return sprintf('https://myanimelist.net/animelist/%s/load.json%s', $this->username, $query);
    }

    /**
     * @param  string $username
     * @return UserAnimeListRequest
     */
    public function setUsername(string $username): UserAnimeListRequest
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param  int $page
     * @return UserAnimeListRequest
     */
    public function setPage(int $page): UserAnimeListRequest
    {
        $this->page = ($page - 1) * 300;
        return $this;
    }

    /**
     * @param  int $status
     * @return UserAnimeListRequest
     */
    public function setStatus(int $status): UserAnimeListRequest
    {
        $this->status = $status;
        return $this;
    }


    /**
     * @param  int      $orderBy
     * @param  int|null $sort
     * @return UserAnimeListRequest
     */
    public function setOrderBy(int $orderBy, ?int $sort = Constants::USER_LIST_SORT_DESCENDING): UserAnimeListRequest
    {
        $this->orderBy = $sort * $orderBy;
        return $this;
    }

    /**
     * @param  int      $orderBy2
     * @param  int|null $sort
     * @return UserAnimeListRequest
     */
    public function setOrderBy2(int $orderBy2, ?int $sort = Constants::USER_LIST_SORT_DESCENDING): UserAnimeListRequest
    {
        $this->orderBy2 = $sort * $orderBy2;
        return $this;
    }

    /**
     * @param  string $title
     * @return UserAnimeListRequest
     */
    public function setTitle(string $title): UserAnimeListRequest
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param  string $season
     * @return UserAnimeListRequest
     */
    public function setSeason(string $season): UserAnimeListRequest
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @param  int $seasonYear
     * @return UserAnimeListRequest
     */
    public function setSeasonYear(int $seasonYear): UserAnimeListRequest
    {
        $this->seasonYear = $seasonYear;
        return $this;
    }


    /**
     * @param  int      $year
     * @param  int|null $month
     * @param  int|null $day
     * @return UserAnimeListRequest
     */
    public function setAiredFrom(int $year, ?int $month = null, ?int $day = null): UserAnimeListRequest
    {
        $this->airedFrom = [$year, $month, $day];
        return $this;
    }

    /**
     * @param  int      $year
     * @param  int|null $month
     * @param  int|null $day
     * @return UserAnimeListRequest
     */
    public function setAiredTo(int $year, ?int $month = null, ?int $day = null): UserAnimeListRequest
    {
        $this->airedTo = [$year, $month, $day];
        return $this;
    }

    /**
     * @param  int $airingStatus
     * @return UserAnimeListRequest
     */
    public function setAiringStatus(int $airingStatus): UserAnimeListRequest
    {
        $this->airingStatus = $airingStatus;
        return $this;
    }

    /**
     * @param  int $producer
     * @return UserAnimeListRequest
     */
    public function setProducer(int $producer): UserAnimeListRequest
    {
        $this->producer = $producer;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
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
    public function getOrderBy(): int
    {
        return $this->orderBy;
    }

    /**
     * @return int
     */
    public function getOrderBy2(): int
    {
        return $this->orderBy2;
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
    public function getSeason(): string
    {
        return $this->season;
    }

    /**
     * @return int
     */
    public function getSeasonYear(): int
    {
        return $this->seasonYear;
    }

    /**
     * @return array
     */
    public function getAiredFrom(): array
    {
        return $this->airedFrom;
    }

    /**
     * @return array
     */
    public function getAiredTo(): array
    {
        return $this->airedTo;
    }

    /**
     * @return int
     */
    public function getAiringStatus(): int
    {
        return $this->airingStatus;
    }

    /**
     * @return int
     */
    public function getProducer(): int
    {
        return $this->producer;
    }
}
