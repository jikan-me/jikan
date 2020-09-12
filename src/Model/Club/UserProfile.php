<?php

namespace Jikan\Model\Club;

use Jikan\Model\Resource\UserImageResource\UserImageResource;
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
     * @var UserImageResource
     */
    private $images;

    /**
     * @param UserProfileParser $parser
     *
     * @return UserProfile
     */
    public static function fromParser(UserProfileParser $parser): self
    {
        $instance = new self();
        $instance->username = $parser->getUsername();
        $instance->images = UserImageResource::factory($parser->getImage());
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
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }
}
