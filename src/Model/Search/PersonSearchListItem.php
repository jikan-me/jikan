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
     * @var \Jikan\Model\Common\MalUrl
     */
    private $malUrl;

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

        $instance->malUrl = $parser->getUrl();
        $instance->imageUrl = $parser->getImageUrl();
        $instance->name = $parser->getName();
        $instance->alternativeNames = $parser->getAlternativeNames();

        return $instance;
    }


    /**
     * @return \Jikan\Model\Common\MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
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
