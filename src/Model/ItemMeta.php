<?php

namespace Jikan\Model;

/**
 * Class ItemMeta
 *
 * @package Jikan\Model
 */
class ItemMeta
{

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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $imageUrl
     */
    public function __construct(string $name, string $url, string $imageUrl)
    {
        $this->url = $url;
        $this->imageUrl = $imageUrl;
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return MalUrl
     */
    public function getUrl(): MalUrl
    {
        return new MalUrl($this->name, $this->url);
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
}
