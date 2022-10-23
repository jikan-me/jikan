<?php

namespace Jikan\Request\Watch;

use Jikan\Request\RequestInterface;

/**
 * Class RecentPromotionalVideosRequest
 *
 * @package Jikan\Request
 */
class RecentPromotionalVideosRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $page;

    /**
     * RecentPromotionalVideosRequest constructor.
     *
     * @param int    $page     starts at 1
     */
    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/watch/promotion?p=%d', $this->page);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
