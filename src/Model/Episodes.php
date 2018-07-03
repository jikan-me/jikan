<?php

namespace Jikan\Model;

use Jikan\Parser\Anime\EpisodesParser;

class Episodes
{
    /**
     * @var int
     */
    private $episodesLastPage;

    /**
     * @var EpisodeListItem[]
     */
    private $episodes;

    public static function fromParser(EpisodesParser $parser): self
    {
        $instance = new self();
        $instance->episodes = $parser->getEpisodes();
        $instance->episodesLastPage = $parser->getEpisodesLastPage();

        return $instance;
    }

    /**
     * @return EpisodeListItem[]
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    /**
     * @return int
     */
    public function getEpisodesLastPage(): int
    {
        return $this->episodesLastPage;
    }
}
