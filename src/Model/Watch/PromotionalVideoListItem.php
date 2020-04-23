<?php

namespace Jikan\Model\Watch;

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
     * @var AnimeImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $promoTitle;

    /**
     * @var YoutubeMeta
     */
    private $promoMedia;

    /**
     * @param EpisodeListItemParser $parser
     *
     * @return EpisodeListItem
     * @throws \Exception
     */
    public static function fromParser(PromotionalVideoListItemParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->images = $parser->getImages();
        $instance->promoTitle = $parser->getPromoTitle();
        $instance->promoMedia = $parser->getPromoMedia();

        return $instance;
    }
}
