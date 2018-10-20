<?php

namespace Jikan\Model\Search;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser;

/**
 * Class PersonSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class PersonSearchListItem
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
     * @param Parser\Search\PersonSearchListItemParser $parser
     *
     * @return PersonSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Search\PersonSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->malId = \Jikan\Helper\Parser::idFromUrl($instance->url);
        $instance->imageUrl = $parser->getImageUrl();
        $instance->name = $parser->getName();
        $instance->alternativeNames = $parser->getAlternativeNames();

        return $instance;
    }


    /**
     * @param Parser\Person\PersonParser $parser
     *
     * @return PersonSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromPersonParser(Parser\Person\PersonParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getPersonURL();
        $instance->malId = \Jikan\Helper\Parser::idFromUrl($instance->url);
        $instance->imageUrl = $parser->getPersonImageUrl();
        $instance->name = $parser->getPersonName();
        $instance->alternativeNames = $parser->getPersonAlternateNames();

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
}
