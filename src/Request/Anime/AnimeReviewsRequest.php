<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeReviewsRequest
 *
 * @package Jikan\Request
 */
class AnimeReviewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $page;

    /**
     * @var bool
     */
    private bool $showSpoilers;

    /**
     * AnimeReviewsRequest constructor.
     *
     * @param int $id
     * @param int $page
     */
    public function __construct(int $id, int $page = 1, bool $showSpoilers = true)
    {
        $this->id = $id;
        $this->page = $page;
        $this->showSpoilers = $showSpoilers;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%d/jikan/reviews?p=%d', $this->id, $this->page);
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return [
            'reviews_inc_spoiler' => (int) $this->showSpoilers
        ];
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
     * @return bool
     */
    public function isShowSpoilers(): bool
    {
        return $this->showSpoilers;
    }

}
