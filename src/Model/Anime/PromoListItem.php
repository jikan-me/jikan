<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\YoutubeMeta;
use Jikan\Parser\Anime\PromoListItemParser;

/**
 * Class PromoListItem
 *
 * @package Jikan\Model
 */
class PromoListItem
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $imageUrl;

    /**
     * @var YoutubeMeta
     */
    public $trailer;

    /**
     * @param PromoListItemParser $parser
     *
     * @return PromoListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(PromoListItemParser $parser): self
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->trailer = YoutubeMeta::factory($parser->getVideoUrl());
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
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return YoutubeMeta
     */
    public function getTrailer(): YoutubeMeta
    {
        return $this->trailer;
    }
}
