<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\StreamEpisodeListItemParser;

/**
 * Class StreamEpisodeListItemParser
 *
 * @package Jikan\Model
 */
class StreamEpisodeListItem
{
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
     * @var string
     */
    public $imageUrl;

    /**
     * @param StreamEpisodeListItemParser $parser
     *
     * @return StreamEpisodeListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(StreamEpisodeListItemParser $parser): self
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->episode = $parser->getEpisode();
        $instance->url = $parser->getUrl();
        $instance->imageUrl = $parser->getImageUrl();

        return $instance;
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
