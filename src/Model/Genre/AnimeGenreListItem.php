<?php

namespace Jikan\Model\Genre;

use Jikan\Parser\Genre\AnimeGenreListItemParser;
use Jikan\Parser\Genre\AnimeGenreListParser;

/**
 * Class AnimeGenreListItem
 *
 * @package Jikan\Model
 */
class AnimeGenreListItem
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $url;
    /**
     * @var int
     */
    private $count;

    /**
     * @param AnimeGenreListItemParser $parser
     *
     * @return AnimeGenreListItem
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(AnimeGenreListItemParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getMalId();
        $instance->name = $parser->getName();
        $instance->url = $parser->getUrl();
        $instance->count = $parser->getCount();

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
