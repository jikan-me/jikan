<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Parser;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;

/**
 * Class CommonMeta
 *
 * @package Jikan\Model
 */
class CommonMeta
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
     * @var CommonImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $title;

    /**
     * Genre constructor.
     *
     * @param string $title
     * @param string $url
     * @param string $imageUrl
     */
    public function __construct(string $title, string $url, string $imageUrl)
    {
        $this->url = $url;
        $this->images = CommonImageResource::factory(Parser::parseImageQuality($imageUrl));
        $this->title = $title;

        $this->malId = Parser::idFromUrl($this->url);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
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
     * @return CommonImageResource
     */
    public function getImages(): CommonImageResource
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
