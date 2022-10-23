<?php

namespace Jikan\Model\Common;

/**
 * Class ClubMeta
 *
 * @package Jikan\Model
 */
class ClubMeta
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
     * @param string $name
     * @param string $url
     * @return ClubMeta
     */
    public static function factory(int  $malId, string $name, string $url) : self
    {
        $instance = new self;

        $instance->malId = $malId;
        $instance->name = $name;
        $instance->url = $url;

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
}
