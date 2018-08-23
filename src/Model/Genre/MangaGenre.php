<?php

namespace Jikan\Model\Genre;

use Jikan\Parser\Genre\MangaGenreParser;
use Jikan\Model\Common\MalUrl;

/**
 * Class MangaGenre
 *
 * @package Jikan\Model
 */
class MangaGenre
{

    /**
     * @var \Jikan\Model\Common\MalUrl
     */
    public $malUrl;

    /**
     * @var int
     */
    public $itemCount;

    /**
     * @var array|MangaGenre[]
     */
    public $manga = [];

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
        $instance->itemCount = $parser->getCount();
        $instance->manga = $parser->getGenreManga();
        $instance->malUrl = new MalUrl(
            $parser->getName(),
            $parser->getUrl()
        );

        return $instance;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    /**
     * @return array|MangaGenre[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
