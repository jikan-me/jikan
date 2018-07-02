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
     * @var MalUrl
     */
    private $url;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $imageUrl
     */
    public function __construct($name, $url, $imageUrl)
    {
        $this->url = new MalUrl(
            $name,
            $url
        );
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return MalUrl
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
}
