<?php

namespace Jikan\Request\Manga;

use Jikan\Request\RequestInterface;

/**
 * Class MangaRecommendationsRequest
 *
 * @package Jikan\Request
 */
class MangaRecommendationsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * MangaRecommendationsRequest constructor.
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
        return sprintf('https://myanimelist.net/manga/%d/jikan/userrecs', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
