<?php

namespace Jikan\Model\Common;

/**
 * Class Url
 *
 * @package Jikan\Model
 */
class TagUrl
{
    /**
     * @var string
     */
    private string $malId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $url;

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     */
    public function __construct(string $malId, string $name, string $url)
    {
        $this->malId = $malId;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getMalId(): string
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
