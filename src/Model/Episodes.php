<?php

namespace Jikan\Model;

use Jikan\Parser\Anime\EpisodesParser;

class Episodes
{
    /**
     * @var EpisodeListItem[]
     */
    private $episodes;

    public static function fromParser(EpisodesParser $parser): self
    {
        $instance = new self();
        $instance->episodes = $parser->getEpisodes();

        return $instance;
    }

    /**
     * @return EpisodeListItem[]
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }
}
