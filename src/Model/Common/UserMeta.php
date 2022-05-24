<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Parser;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Model\Resource\UserImageResource\UserImageResource;

/**
 * Class UserMeta
 *
 * @package Jikan\Model
 */
class UserMeta
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $url;

    /**
     * @var CommonImageResource
     */
    private $images;

    /**
     * Genre constructor.
     *
     * @param string $username
     * @param string $url
     * @param string $imageUrl
     */
    public function __construct(string $username, string $url, string $imageUrl)
    {
        $this->url = $url;
        $this->images = UserImageResource::factory(Parser::parseImageQuality($imageUrl));
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }
}
