<?php

namespace Jikan\Request\Character;

use Jikan\Request\RequestInterface;

/**
 * Class CharacterPictures
 *
 * @package Jikan\Request
 */
class CharacterPicturesRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * CharacterPicturesRequest constructor.
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
        // MyAnimeList wants <something> after /<id>/... it happily accepts jikan as a valid parameter though
        return sprintf('https://myanimelist.net/character/%d/jikan/pics', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
