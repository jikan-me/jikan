<?php

namespace Jikan\Model;

/**
 * Class Ography
 *
 * @package Jikan\Model
 */
abstract class Ography
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $name;

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
    private $role;

    /**
     * @param $parser
     * @param $instance
     */
    protected static function setProperties($parser, $instance)
    {
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->name = $parser->getName();
        $instance->imageUrl = $parser->getImage();
        $instance->role = $parser->getRole();
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
    public function getName(): string
    {
        return $this->name;
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
    public function getRole(): string
    {
        return $this->role;
    }
}
