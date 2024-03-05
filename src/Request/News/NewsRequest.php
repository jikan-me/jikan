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
     * @var string
     */
    private string $malId;

    /**
     * @param string $malId
     */
    public function __construct(string $malId)
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
     * @return string
     */
    public function getMalId(): string
    {
        return $this->malId;
    }
}
