<?php

namespace Jikan\Request\Manga;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class MangaReviewsRequest
 *
 * @package Jikan\Request
 */
class MangaReviewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var int
     */
    private int $page;

    /**
     * @var string
     */
    private string $sort;

    /**
     * @var bool
     */
    private bool $spoilers;

    /**
     * @var bool
     */
    private bool $preliminary;

    /**
     * MangaReviewsRequest constructor.
     *
     * @param int $id
     * @param int $page
     */
    public function __construct(int $id, int $page = 1, string $sort = Constants::REVIEWS_SORT_MOST_VOTED, bool $spoilers = true, bool $preliminary = true)
    {
        $this->id = $id;
        $this->page = $page;
        $this->sort = $sort;
        $this->spoilers = $spoilers;
        $this->preliminary = $preliminary;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $query = '?'.http_build_query(
            [
                    'spoiler' => $this->spoilers ? 'on' : 'off',
                    'preliminary' => $this->preliminary ? 'on' : 'off',
                    'sort' => $this->sort,
                    'p' => $this->page
                ]
        );

        return sprintf('https://myanimelist.net/manga/%d/jikan/reviews%s', $this->id, $query);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @return bool
     */
    public function isSpoilers(): bool
    {
        return $this->spoilers;
    }

    /**
     * @return bool
     */
    public function isPreliminary(): bool
    {
        return $this->preliminary;
    }
}
