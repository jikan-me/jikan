<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeRequest
 *
 * @package Jikan\Request
 */
class AnimeNewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeNewsRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/%s/_/news', $this->id);
    }
}
