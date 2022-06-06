<?php

namespace Jikan\Model\User;

use Jikan\Parser\User\Profile\LastUpdatesParser;

class LastUpdates
{
    /**
     * @var array
     */
    private $anime;

    /**
     * @var array
     */
    private $manga;

    public static function fromParser(LastUpdatesParser $parser): self
    {
        $instance = new self();
        $instance->anime = $parser->getLastAnimeUpdates();
        $instance->manga = $parser->getLastMangaUpdates();
        return $instance;
    }

    /**
     * @return array
     */
    public function getAnime(): array
    {
        return $this->anime;
    }

    /**
     * @return array
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
