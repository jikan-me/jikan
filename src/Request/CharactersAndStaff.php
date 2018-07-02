<?php

namespace Jikan\Request;

/**
 * Class AnimeRequest
 *
 * @package Jikan\Request
 */
class CharactersAndStaff implements RequestInterface
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
        return sprintf('https://myanimelist.net/anime/%s/_/characters', $this->id);
    }
}
