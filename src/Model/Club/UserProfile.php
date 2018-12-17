<?php

namespace Jikan\Model\Club;

use Jikan\Parser\Club\UserProfileParser;

/**
 * Class UserProfile
 *
 * @package Jikan\Model\Club
 */
class UserProfile
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
     * @var string
     */
    private $imageUrl;

    /**
     * @param UserProfileParser $parser
     *
     * @return UserProfile
     */
    public static function fromParser(UserProfileParser $parser): self
    {
        $instance = new self();
        $instance->username = $parser->getUsername();
        $instance->imageUrl = $parser->getImage();
        $instance->url = $parser->getUrl();

        return $instance;
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
