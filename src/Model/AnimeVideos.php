<?php

namespace Jikan\Model;

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
    private $promo;

    /**
     * @var StreamEpisodeListItem[]
     */
    private $episodes;

    /**
     * @param VideosParser $parser
     *
     * @return Videos
     */
    public static function fromParser(VideosParser $parser): self
    {
        $instance = new self();
        $instance->episodes = $parser->getPromos();
        $instance->promo = $parser->getEpisodes();

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
     * @return StreamEpisodeListItem
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }
}
