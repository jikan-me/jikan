<?php
namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeMoreInfoRequest
 *
 * @package Jikan\Request\Anime
 */
class AnimeMoreInfoRequest implements RequestInterface
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
        return sprintf('https://myanimelist.net/anime/%d/_/moreinfo', $this->id);
    }
}
