<?php

namespace Jikan\Model\Search;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser;

/**
 * Class CharacterSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class CharacterSearchListItem
{

    /**
     * @var \Jikan\Model\Common\MalUrl
     */
    private $url;

    /**
     * @var string
     */
    private $imageUrl;

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
        $instance->imageUrl = $parser->getImageUrl();
        $instance->name = $parser->getName();
        $instance->alternativeNames = $parser->getAlternativeNames();
        $instance->anime = $parser->getAnime();
        $instance->manga = $parser->getManga();

        return $instance;
    }


    /**
     * @return \Jikan\Model\Common\MalUrl
     */
    public function getUrl(): MalUrl
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
