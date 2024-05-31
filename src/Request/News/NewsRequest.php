<?php

namespace Jikan\Request\News;

use Jikan\Request\RequestInterface;

/**
 * Class RecentNewsRequest
 *
 * @package Jikan\Request
 */
class NewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private string $malId;

    /**
     * @param int $malId
     */
    public function __construct(int $malId)
    {
        $this->malId = $malId;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/news/%s', $this->malId);
    }

    /**
     * @return int
     */
    public function getMalId(): string
    {
        return $this->malId;
    }
}
