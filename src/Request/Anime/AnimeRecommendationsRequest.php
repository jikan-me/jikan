<?php

namespace Jikan\Request\Anime;

use Jikan\Request\RequestInterface;

/**
 * Class AnimeRecommendationsRequest
 *
 * @package Jikan\Request
 */
class AnimeRecommendationsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * AnimeRecommendationsRequest constructor.
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
        return sprintf('https://myanimelist.net/anime/%d/jikan/userrecs', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
