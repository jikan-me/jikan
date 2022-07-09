<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\VideosParser;

/**
 * Class AnimeVideos
 *
 * @package Jikan\Model
 */
class AnimeVideos
{
    /**
     * @var PromoListItem[]
     */
    private array $promo;

    /**
     * @var StreamEpisodeListItem[]
     */
    private array $episodes;

    /**
     * @var MusicVideoListItem[]
     */
    private array $musicVideos;

    /**
     * @param VideosParser $parser
     *
     * @return AnimeVideos
     * @throws \InvalidArgumentException
     */
    public static function fromParser(VideosParser $parser): self
    {
        $instance = new self();
        $instance->episodes = $parser->getEpisodes();
        $instance->promo = $parser->getPromos();
        $instance->musicVideos = $parser->getMusic();

        return $instance;
    }

    /**
     * @return PromoListItem[]
     */
    public function getPromos(): array
    {
        return $this->promo;
    }

    /**
     * @return StreamEpisodeListItem[]
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    /**
     * @return MusicVideoListItem[]
     */
    public function getMusicVideos()
    {
        return $this->musicVideos;
    }
}
