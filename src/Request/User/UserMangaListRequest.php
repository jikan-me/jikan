<?php

namespace Jikan\Request\User;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class UserMangaListRequest
 *
 * @package Jikan\Request
 */
class UserMangaListRequest implements RequestInterface
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
     * @var array
     */
    private $publishedFrom = [null, null, null];

    /**
     * @var array
     */
    private $publishedTo = [null, null, null];

    /**
     * @var int
     */
    private $publishingStatus;

    /**
     * @var int
     */
    private $magazine;

    /**
     * UserMangaListRequest constructor.
     *
     * @param string $username
     * @param int    $page
     * @param int    $status
     */
    public function __construct(string $username, int $page = 1, int $status = 7)
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
            'published_from_year' => $this->publishedFrom[0],
            'published_from_month' => $this->publishedFrom[1],
            'published_from_day' => $this->publishedFrom[2],
            'published_to_year' => $this->publishedTo[0],
            'published_to_month' => $this->publishedTo[1],
            'published_to_day' => $this->publishedTo[2],
            'magazine' => $this->magazine,
            'publishing_status' => $this->publishingStatus
            ]
        );
        return sprintf('https://myanimelist.net/mangalist/%s/load.json%s', $this->username, $query);
    }

    /**
     * @param  string $username
     * @return UserMangaListRequest
     */
    public function setUsername(string $username): UserMangaListRequest
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param  int $page
     * @return UserMangaListRequest
     */
    public function setPage(int $page): UserMangaListRequest
    {
        $this->page = ($page - 1) * 300;
        return $this;
    }

    /**
     * @param  int $status
     * @return UserMangaListRequest
     */
    public function setStatus(int $status): UserMangaListRequest
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param  int      $orderBy
     * @param  int|null $sort
     * @return UserMangaListRequest
     */
    public function setOrderBy(int $orderBy, ?int $sort = Constants::USER_LIST_SORT_DESCENDING): UserMangaListRequest
    {
        $this->orderBy = $sort * $orderBy;
        return $this;
    }

    /**
     * @param  int      $orderBy2
     * @param  int|null $sort
     * @return UserMangaListRequest
     */
    public function setOrderBy2(int $orderBy2, ?int $sort = Constants::USER_LIST_SORT_DESCENDING): UserMangaListRequest
    {
        $this->orderBy2 = $sort * $orderBy2;
        return $this;
    }

    /**
     * @param  string $title
     * @return UserMangaListRequest
     */
    public function setTitle(string $title): UserMangaListRequest
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param  int      $year
     * @param  int|null $month
     * @param  int|null $day
     * @return UserMangaListRequest
     */
    public function setPublishedFrom(int $year, ?int $month = null, ?int $day = null): UserMangaListRequest
    {
        $this->publishedFrom = [$year, $month, $day];
        return $this;
    }

    /**
     * @param  int      $year
     * @param  int|null $month
     * @param  int|null $day
     * @return UserMangaListRequest
     */
    public function setPublishedTo(int $year, ?int $month = null, ?int $day = null): UserMangaListRequest
    {
        $this->publishedTo = [$year, $month, $day];
        return $this;
    }

    /**
     * @param  int $publishingStatus
     * @return UserMangaListRequest
     */
    public function setPublishingStatus(int $publishingStatus): UserMangaListRequest
    {
        $this->publishingStatus = $publishingStatus;
        return $this;
    }

    /**
     * @param  int $magazine
     * @return UserMangaListRequest
     */
    public function setMagazine(int $magazine): UserMangaListRequest
    {
        $this->magazine = $magazine;
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
     * @return array
     */
    public function getPublishedFrom(): array
    {
        return $this->publishedFrom;
    }

    /**
     * @return array
     */
    public function getPublishedTo(): array
    {
        return $this->publishedTo;
    }

    /**
     * @return int
     */
    public function getPublishingStatus(): int
    {
        return $this->publishingStatus;
    }

    /**
     * @return int
     */
    public function getMagazine(): int
    {
        return $this->magazine;
    }
}
