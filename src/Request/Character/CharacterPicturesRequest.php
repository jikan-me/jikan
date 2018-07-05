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
        // MyAnimeList wants <something> after /<id>/... it happily accepts jikan as a valid parameter though
        return sprintf('https://myanimelist.net/character/%d/jikan/pictures', $this->id);
    }
}
