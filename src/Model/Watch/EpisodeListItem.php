<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
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
     * @var string
     */
    private $name;

    /**
     * @var CommonImageResource
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
        $instance->name = $parser->getTitle();
        $instance->images = CommonImageResource::factory($parser->getImages());
        $instance->episodes = $parser->getEpisodes();
        $instance->regionLocked = $parser->getRegionLocked();

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
     * @return AnimeResourceImages
     */
    public function getImages(): AnimeResourceImages
    {
        return $this->images;
    }

    /**
     * @return NewEpisodeListItem
     */
    public function getEpisodes(): NewEpisodeListItem
    {
        return $this->episodes;
    }

    /**
     * @return bool
     */
    public function isRegionLocked(): bool
    {
        return $this->regionLocked;
    }
}
