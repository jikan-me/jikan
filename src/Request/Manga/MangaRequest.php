<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaRequest
 *
 * @package Jikan\Request
 */
class MangaRequest implements RequestInterface
{
    /**
     * @var bool
     */
    private $characters = false;

    /**
     * @var int
     */
    private $id;

    /**
     * MangaRequest constructor.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return Manga
     */
    public function withCharacters(): self
    {
        $this->characters = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCharacters(): bool
    {
        return $this->characters;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/manga/%s/', $this->id);
    }
}
