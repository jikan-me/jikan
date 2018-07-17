<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeRequest
 *
 * @package Jikan\Request
 */
class MangaNewsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeRequest constructor.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/manga/%s/_/news', $this->id);
    }
}
