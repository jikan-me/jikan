<?php

namespace Jikan\Model\User;

use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\User\Friends\FriendParser;

/**
 * Class Friend
 *
 * @package Jikan\Model
 */
class Friend
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var UserImageResource
     */
    private $images;

    /**
     * @var \DateTimeImmutable
     */
    private $lastOnline;

    /**
     * @var \DateTimeImmutable
     */
    private $friendsSince;

    /**
     * @param FriendParser $parser
     *
     * @return Friend
     * @throws \Exception
     */
    public static function fromParser(FriendParser $parser): Friend
    {
        $instance = new self();
        $instance->url = $parser->getUrl();
        $instance->username = $parser->getName();
        $instance->images = UserImageResource::factory($parser->getAvatar());
        $instance->friendsSince = $parser->getFriendsSince();
        $instance->lastOnline = $parser->getLastOnline();

        return $instance;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastOnline(): \DateTimeImmutable
    {
        return $this->lastOnline;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getFriendsSince(): \DateTimeImmutable
    {
        return $this->friendsSince;
    }
}
