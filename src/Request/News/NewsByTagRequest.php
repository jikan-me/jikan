<?php

namespace Jikan\Request\News;

use Jikan\Request\RequestInterface;

/**
 * Class RecentNewsRequest
 *
 * @package Jikan\Request
 */
class NewsByTagRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $page;

    /**
     * @var string
     */
    private string $malId;

    /**
     * AnimeRequest constructor.
     *
     * @param int $page
     */
    public function __construct(string $malId, int $page)
    {
        $this->malId = $malId;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/news/tag/%s?p=%d', $this->malId, $this->page);
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
    public function getMalId(): string
    {
        return $this->malId;
    }


}
