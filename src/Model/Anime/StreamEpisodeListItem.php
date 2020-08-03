<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\Anime\StreamEpisodeListItemParser;

/**
 * Class StreamEpisodeListItemParser
 *
 * @package Jikan\Model
 */
class StreamEpisodeListItem
{
    /**
     * @var int
     */
    public $malId;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $episode;

    /**
     * @var string
     */
    public $url;

    /**
     * @var WrapImageResource
     */
    public $images;

    /**
     * @param StreamEpisodeListItemParser $parser
     *
     * @return StreamEpisodeListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(StreamEpisodeListItemParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->title = $parser->getTitle();
        $instance->episode = $parser->getEpisode();
        $instance->url = $parser->getUrl();
        $instance->images = WrapImageResource::factory($parser->getImageUrl());

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getEpisode(): string
    {
        return $this->episode;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return WrapImageResource
     */
    public function getImages(): WrapImageResource
    {
        return $this->images;
    }
}
