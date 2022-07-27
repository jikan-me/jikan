<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\MusicMeta;
use Jikan\Model\Common\YoutubeMeta;
use Jikan\Parser\Anime\MusicVideoListItemParser;

/**
 * Class MusicVideoListItem
 *
 * @package Jikan\Model\Anime
 */
class MusicVideoListItem
{
    /**
     * @var string
     */
    private string $title;

    /**
     * @var YoutubeMeta
     */
    private YoutubeMeta $video;

    /**
     * @var MusicMeta
     */
    private MusicMeta $meta;


    /**
     * @param MusicVideoListItemParser $parser
     * @return static
     */
    public static function fromParser(MusicVideoListItemParser $parser): self
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->video = YoutubeMeta::factory($parser->getVideoUrl());
        $instance->meta = $parser->getMusic();

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
     * @return YoutubeMeta
     */
    public function getVideo(): YoutubeMeta
    {
        return $this->video;
    }

    /**
     * @return MusicMeta
     */
    public function getMeta(): MusicMeta
    {
        return $this->meta;
    }
}
