<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaNewsRequest
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
     * MangaNewsRequest constructor.
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
        return sprintf('https://myanimelist.net/manga/%s/_/news', $this->id);
    }
}
