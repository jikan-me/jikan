<?php

namespace Jikan\Model;

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
    private $name;

    /**
     * @var string
     */
    private $avatar;


    /**
     * @var string
     */
    private $lastOnline;

    /**
     * @var \DateTimeImmutable
     */
    private $friendsSince;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param FriendParser $parser
     *
     * @return Friend
     * @throws \InvalidArgumentException
     */
    public static function fromParser(FriendParser $parser): Friend
    {
        $instance = new self();
        $instance->url = $parser->getUrl();
        $instance->name = $parser->getName();
        $instance->avatar = $parser->getAvatar();
        $instance->friendsSince = $parser->getFriendsSince();
        $instance->lastOnline = $parser->getLastOnline();

        return $instance;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return string
     */
    public function getLastOnline(): string
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
