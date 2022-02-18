<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaCharactersRequest
 *
 * @package Jikan\Request
 */
class MangaCharactersRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * MangaCharactersRequest constructor.
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
        return sprintf('https://myanimelist.net/manga/%s/_/characters', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
