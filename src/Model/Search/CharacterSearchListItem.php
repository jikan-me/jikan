<?php

namespace Jikan\Model\Search;

use Jikan\Model\Resource\CharacterImageResource\CharacterImageResource;
use Jikan\Parser;

/**
 * Class CharacterSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class CharacterSearchListItem
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
     * @var CharacterImageResource
     */
    private $images;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $alternativeNames;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    private $anime;

    /**
     * @var \Jikan\Model\Common\MalUrl[]
     */
    private $manga;

    /**
     * @param Parser\Search\CharacterSearchListItemParser $parser
     *
     * @return CharacterSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\CharacterSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->malId = \Jikan\Helper\Parser::idFromUrl($instance->url);
        $instance->images = CharacterImageResource::factory($parser->getImageUrl());
        $instance->name = $parser->getName();
        $instance->alternativeNames = $parser->getAlternativeNames();
        $instance->anime = $parser->getAnime();
        $instance->manga = $parser->getManga();

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
     * @return CharacterImageResource
     */
    public function getImages(): CharacterImageResource
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

    /**
     * @return array
     */
    public function getAlternativeNames(): array
    {
        return $this->alternativeNames;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getAnime(): array
    {
        return $this->anime;
    }

    /**
     * @return \Jikan\Model\Common\MalUrl[]
     */
    public function getManga(): array
    {
        return $this->manga;
    }
}
