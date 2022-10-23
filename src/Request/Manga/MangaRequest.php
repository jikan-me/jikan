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
     * @var int
     */
    private $id;

    /**
     * MangaRequest constructor.
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
        return sprintf('https://myanimelist.net/manga/%s/', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
