<?php

namespace Jikan\Model\Watch;

use Jikan\Parser\Watch\EpisodeListItemParser;

/**
 * Class AnimeListItem
 *
 * @package Jikan\Model
 */
class EpisodeListItem
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
     * @var strin
     */
    private $title;

    /**
     * @var AnimeResourceImages
     */
    private $images;

    /**
     * @var NewEpisodeListItem
     */
    private $episodes;

    /**
     * @var bool
     */
    private $regionLocked;

    /**
     * @param EpisodeListItemParser $parser
     *
     * @return EpisodeListItem
     * @throws \Exception
     */
    public static function fromParser(EpisodeListItemParser $parser): EpisodeListItem
    {
        $instance = new self();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->images = $parser->getImages();
        $instance->episodes = $parser->getEpisodes();
        $instance->regionLocked = $parser->getRegionLocked();

        return $instance;
    }
}
