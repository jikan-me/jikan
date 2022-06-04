<?php

namespace Jikan\Model\Genre;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\Genre\MangaGenreParser;

/**
 * Class MangaGenre
 *
 * @package Jikan\Model
 */
class MangaGenre extends Results implements Pagination
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    public $count;

    /**
     * @var bool
     */
    private $hasNextPage = false;

    /**
     * @var int
     */
    private $lastVisiblePage = 1;

    /**
     * @param MangaGenreParser $parser
     *
     * @return MangaGenre
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(MangaGenreParser $parser): self
    {
        $instance = new self();

        $instance->count = $parser->getCount();
        $instance->results = $parser->getResults();
        $instance->name = $parser->getName();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->hasNextPage = $parser->getHasNextPage();
        $instance->lastVisiblePage = $parser->getLastPage();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return $this->lastVisiblePage;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
