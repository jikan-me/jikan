<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\AnimeMeta;
use Jikan\Model\Common\YoutubeMeta;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\Watch\EpisodeListItemParser;
use Jikan\Parser\Watch\PromotionalVideoListItemParser;

/**
 * Class PromotionalVideoListItem
 *
 * @package Jikan\Model
 */
class PromotionalVideoListItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var AnimeMeta
     */
    private $entry;

    /**
     * @var YoutubeMeta
     */
    private $trailer;

    /**
     * @param EpisodeListItemParser $parser
     *
     * @return EpisodeListItem
     * @throws \Exception
     */
    public static function fromParser(PromotionalVideoListItemParser $parser): self
    {
        $instance = new self();
        $instance->entry = new AnimeMeta(
            $parser->getTitle(),
            $parser->getUrl(),
            $parser->getImages()
        );
        $instance->title = $parser->getPromoTitle();
        $instance->trailer = $parser->getPromoMedia();

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
     * @return AnimeMeta
     */
    public function getEntry(): AnimeMeta
    {
        return $this->entry;
    }

    /**
     * @return YoutubeMeta
     */
    public function getTrailer(): YoutubeMeta
    {
        return $this->trailer;
    }
}
