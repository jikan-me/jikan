<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeRequest
 *
 * @package Jikan\Request
 */
class AnimeCharactersAndStaffRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeCharactersAndStaffRequest constructor.
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
        return sprintf('https://myanimelist.net/anime/%s/_/characters', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
