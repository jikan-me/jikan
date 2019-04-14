<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\EpisodesParser;

/**
 * Class Episodes
 *
 * @package Jikan\Model
 */
class Episodes
{
    /**
     * @var int
     */
    private $episodesLastPage = 1;

    /**
     * @var EpisodeListItem[]
     */
    private $episodes = [];

    /**
     * @param EpisodesParser $parser
     *
     * @return Episodes
     * @throws \InvalidArgumentException
     */
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
