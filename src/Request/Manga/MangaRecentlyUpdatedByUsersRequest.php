<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaRecentlyUpdatedByUsersRequest
 *
 * @package Jikan\Request
 */
class MangaRecentlyUpdatedByUsersRequest implements RequestInterface
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
     * MangaRecentlyUpdatedByUsersRequest constructor.
     *
     * @param int $id
     * @param int $page
     */
    public function __construct(int $id, int $page = 1)
    {
        $this->id = $id;
        $this->page = ($page - 1) * 75;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/manga/%d/jikan/stats?show=%d', $this->id, $this->page);
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
    public function getPage()
    {
        return $this->page;
    }
}
