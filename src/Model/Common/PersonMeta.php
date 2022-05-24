<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Parser;
use Jikan\Model\Resource\PersonImageResource\PersonImageResource;

/**
 * Class PersonMeta
 *
 * @package Jikan\Model
 */
class PersonMeta
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
     * @var PersonImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $name;

    /**
     * Genre constructor.
     *
     * @param string $title
     * @param string $url
     * @param string $imageUrl
     */
    public function __construct(string $name, string $url, string $imageUrl)
    {
        $this->url = $url;
        $this->images = PersonImageResource::factory(Parser::parseImageQuality($imageUrl));
        $this->name = $name;

        $this->malId = Parser::idFromUrl($this->url);
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
     * @return PersonImageResource
     */
    public function getImages(): PersonImageResource
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
